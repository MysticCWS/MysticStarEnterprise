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

?>
<div class="content">
    <div class="title">
        <h2>Shopping Cart</h5>
    </div>
    
    <div class="container mt-5 px-4 py-4 border rounded bg-white" id="shopping_cart">
        <?php if (empty($cartItemss)): ?>
                        <p>Your cart is empty.</p>
                        <div class="text-end">
                            <a href="products.php#product_list" class="btn btn-outline-secondary">Continue Shopping</a>
                        </div>
                    <?php else: ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">SKU</th>
                                    <th scope="col">Item</th>
                                    <th scope="col">Item Name</th>
                                    <th scope="col">Unit Price (RM)</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Remark</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $cartItem): ?>
                                <?php 
                                $cartUserID = $cartItem['uid'];
                                if ($uid === $cartUserID): ?>
                                    <tr>
                                        <td><?php echo $cartItem['item_sku']; ?></td>
                                        <td><img src="<?php echo $cartItem['item_imgurl']; ?>" style="min-width:10%; overflow:hidden; max-height:10%;" alt="<?php echo $cartItem['item_name']?>"/></td>
                                        <td><?php echo $cartItem['item_name']; ?></td>
                                        <td>RM <?php echo number_format($cartItem['item_price'], 2); ?></td>
                                        <td><?php echo $cartItem['purchase_qty']; ?></td>
                                        <td><?php echo $cartItem['purchase_remark']; ?></td>
                                        <td>RM <?php echo number_format($cartItem['item_price'] * $cartItem['purchase_qty'], 2); ?></td>
                                        <td>
                                            <a href="remove_from_cart.php?purchase_id=<?php echo $purchase_item['purchase_id']; ?>" class="btn btn-sm btn-outline-danger">Remove</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" class="text-end"><strong>Total:</strong></td>
                                    <td colspan="1"><strong>
                                        <?php 
                                            $purchase_total = array_reduce($purchase_items, function($carry, $purchase_item) {
                                                return $carry + ($purchase_item['item_price'] * $purchase_item['purchase_qty']);
                                            }, 0);
                                            echo "RM";
                                            echo number_format($purchase_total, 2);
                                        ?>
                                    </strong></td>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="text-end">
                            <a href="homepage.php#items_list" class="btn btn-outline-secondary">Continue Shopping</a>
                            <a href="checkout.php" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#checkoutModal">Checkout</a>
                        </div>
<?php endif;?>