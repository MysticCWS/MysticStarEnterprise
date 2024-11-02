<?php 
session_start();
include 'dbcon.php';
include 'includes\header.php'; 
echo ' | Shopping Cart';
include 'includes\header2.php';
include 'includes\navbar.php';

//Get UID
if(isset($_SESSION['verified_user_id'])){
    $uid = $_SESSION['verified_user_id'];
    try {
        $user = $auth->getUser($uid);
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
        echo $e->getMessage();
    }
}

//Fetch product from database
$ref_table = 'product';
$products = $database->getReference($ref_table)->getValue();

//Fetch cart items from database for this user
$cart_table = 'cart';
$cartItems = $database->getReference($cart_table)->getValue($uid);

//Edit cart item
if (isset($_POST['btnEditCart'])){
    $user_id = $_POST['user_id'];
    $item_sku = $_POST['item_sku'];
    $item_name = $_POST['item_name'];
    $item_imgurl = $_POST['item_imgurl'];
    $item_price = $_POST['item_price'];
    $purchase_qty = $_POST['purchase_qty'];
    $purchase_remark = $_POST['purchase_remark'];
    
    foreach ($cartItems as $cartItem){
        $cartItemSKU = $cartItem['item_sku'];
        $cartUserID = $cartItem['uid'];
        if($item_sku === $cartItemSKU){
            if ($user_id === $cartUserID){
                $cartData = [
                    'uid'=>$user_id,
                    'item_sku'=>$item_sku,
                    'item_name'=>$item_name,
                    'item_imgurl'=>$item_imgurl,
                    'item_price'=>$item_price,
                    'purchase_qty'=>$purchase_qty,
                    'purchase_remark'=>$purchase_remark,
                    'cart_key'=>$cartItem['cart_key']
                ];

                $cartKey = $cartItem['cart_key'];
                $updateCart_table = 'cart/'.$cartKey;
                $postCartRef = $database->getReference($updateCart_table)->update($cartData);

                if($postCartRef){
                    $_SESSION['status'] = "Cart item updated successfully.";
                    header("Location: cart.php");
                    die();
                }
            }
        }
    }
}

//Remove cart item
if (isset($_POST['btnRemoveCart'])){
    $cart_id = $_POST['cart_id'];
    
    $deleteCart_table = 'cart/'.$cart_id;
    $deleteCartRef = $database->getReference($deleteCart_table)->remove();
    
    if($deleteCartRef){
        $_SESSION['status'] = "Cart item removed successfully.";
        header("Location: cart.php");
        die();
    }
}

?>
<div class="content">
    <!--Show Status-->
    <?php
        if(isset($_SESSION['status'])){
            echo "<h5 class='alert alert-success'>".$_SESSION['status']."</h5>";
            unset($_SESSION['status']);
        }
    ?>
    
    <div class="title">
        <h2>Shopping Cart</h2>
    </div>
    
    <div class="container mt-5 px-4 py-4 border rounded bg-white" id="shopping_cart">
        <table class="table">
            <thead>
                <tr style="text-align: center">
                    <th scope="col">SKU</th>
                    <th scope="col">Item</th>
                    <th scope="col">Item Name</th>
                    <th scope="col">Unit Price (RM)</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Remark</th>
                    <th scope="col">Total (RM)</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $purchase_total = 0;
                foreach ($cartItems as $cartItem): ?>
                <?php 
                $cartUserID = $cartItem['uid'];
                if ($uid === $cartUserID): ?>
                    <tr style="vertical-align: middle; text-align: center;">
                        <td><?php echo $cartItem['item_sku']; ?></td>
                        <td><img src="<?php echo $cartItem['item_imgurl']; ?>" style="min-width:100px; overflow:hidden; max-height:100px;" alt="<?php echo $cartItem['item_name']?>"/></td>
                        <td><?php echo $cartItem['item_name']; ?></td>
                        <td>RM <?php echo number_format($cartItem['item_price'], 2); ?></td>
                        <td><?php echo $cartItem['purchase_qty']; ?></td>
                        <td><?php echo $cartItem['purchase_remark']; ?></td>
                        <?php $cartItemTotalPrice = $cartItem['item_price'] * $cartItem['purchase_qty']; ?>
                        <td>RM <?php echo number_format($cartItemTotalPrice, 2); ?></td>
                        <td>
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editCartModal-<?php echo $cartItem['item_sku']; ?>">
                                Edit
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#removeCartModal-<?php echo $cartItem['item_sku']; ?>">
                                Remove
                            </button>
                        </td>
                    </tr>

                    <!-- Edit Cart Modal -->
                    <div class="modal fade" id="editCartModal-<?php echo $cartItem['item_sku']; ?>" tabindex="-1" aria-labelledby="editCartModalLabel-<?php echo $cartItem['item_sku']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editCartModalLabel-<?php echo $cartItem['item_sku']; ?>">Edit Cart Item: <?php echo $cartItem['item_name']; ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Form for modifying order -->
                                    <form id="editcartForm" method="POST" >
                                        <div class="mb-3">
                                            <input type="hidden" name="user_id" value="<?php echo $uid; ?>">
                                            <input type="hidden" name="item_sku" value="<?php echo $cartItem['item_sku']; ?>">
                                            <input type="hidden" name="item_name" value="<?php echo $cartItem['item_name']; ?>">
                                            <input type="hidden" name="item_imgurl" value="<?php echo $cartItem['item_imgurl']; ?>">
                                            <input type="hidden" name="item_price" value="<?php echo $cartItem['item_price']; ?>">
                                            <p>Price per Unit: RM <?php echo number_format($cartItem['item_price'], 2);?></p>
                                            <?php foreach ($products as $product):?>
                                            <?php 
                                            $productUnitSKU = $product['sku']; 
                                            $cartItemUnitSKU = $cartItem['item_sku']; ?>
                                            <?php if($cartItemUnitSKU == $productUnitSKU): ?>
                                            <p>Stock Balance: <?php echo $product['stockbalance'];?></p>
                                            <label for="quantity-<?php echo $cartItem['item_sku']; ?>" class="form-label">Quantity</label>
                                            <input type="number" class="form-control" name="purchase_qty" id="quantity-<?php echo $cartItem['item_sku']; ?>" value="<?php echo $cartItem['purchase_qty']; ?>" min="1" max="<?php echo $product['stockbalance'];?>">
                                            <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="mb-3">
                                            <label for="remark-<?php echo $cartItem['item_sku']; ?>" class="form-label">Order Remarks (Optional)</label>
                                            <input type="text" class="form-control" id="remark-<?php echo $cartItem['item_sku']; ?>" name="purchase_remark" value="<?php echo $cartItem['purchase_remark'] ?>">
                                        </div>
                                        <button type="submit" class="btn btn-outline-secondary" name="btnEditCart">Save Edit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Remove Cart Modal -->
                    <div class="modal fade" id="removeCartModal-<?php echo $cartItem['item_sku']; ?>" tabindex="-1" aria-labelledby="removeCartModalLabel-<?php echo $cartItem['item_sku']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="removeCartModalLabel-<?php echo $cartItem['item_sku']; ?>">Confirm to Remove Cart Item: <?php echo $cartItem['item_name']; ?>?</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Form for modifying order -->
                                    <form id="removecartForm" method="POST" >
                                        <input type="hidden" name="cart_id" value="<?php echo $cartItem['cart_key']; ?>">
                                        <button type="submit" class="btn btn-outline-danger" name="btnRemoveCart">Remove</button>
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php $purchase_total = $purchase_total + $cartItemTotalPrice; ?>
                <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr style="text-align: center;">
                    <td colspan="6" class="text-end"><strong>Total:</strong></td>
                    <td colspan="1"><strong>
                        <?php
                            echo "RM";
                            echo number_format($purchase_total, 2);
                        ?>
                    </strong></td>
                    <td colspan="1"></td>
                </tr>
            </tfoot>
        </table>
        <div class="text-end">
            <a href="products.php#product_list" class="btn btn-outline-secondary">Continue Shopping</a>
            <a href="function_checkstockbalance.php" class="btn btn-outline-secondary" >Checkout</a>
        </div>
    </div>
</div>
<?php
include 'includes\footer.php';
?>