<?php
session_start();
include 'dbcon.php';
include 'includes\header.php'; 
echo ' | Order History';
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

//Fetch cart items from database for all and this user
$cart_table = 'cart';
$cartItems = $database->getReference($cart_table)->getValue($uid);
$cartUIDItems = $database->getReference($cart_table)->orderByChild('uid')->equalTo($uid)->getValue();

//Fetch user delivery address from database
$delivery_table = 'delivery';
$deliveryDatas = $database->getReference($delivery_table)->getValue($uid);

//Fetch orders from database for the user
$order_table = 'order';
$orderItems = $database->getReference($order_table)->orderByChild('uid')->equalTo($uid)->getValue();


//Add reorder item to cart
if (isset($_POST['btnReorder'])){
    $user_id = $_POST['user_id'];
    $item_sku = $_POST['item_sku'];
    $item_name = $_POST['item_name'];
    $item_imgurl = $_POST['item_imgurl'];
    $item_price = $_POST['item_price'];
    $purchase_qty = $_POST['purchase_qty'];
    $purchase_remark = $_POST['purchase_remark'];
    $cart_key = '';
    
    if (empty($cartUIDItems)){
        $cartData = [
            'uid'=>$user_id,
            'item_sku'=>$item_sku,
            'item_name'=>$item_name,
            'item_imgurl'=>$item_imgurl,
            'item_price'=>$item_price,
            'purchase_qty'=>$purchase_qty,
            'purchase_remark'=>$purchase_remark,
            'cart_key'=>''
        ];
        $postCartRef = $database->getReference($cart_table)->push($cartData)->getKey();
        $cartUpdate = [
            'uid'=>$user_id,
            'item_sku'=>$item_sku,
            'item_name'=>$item_name,
            'item_imgurl'=>$item_imgurl,
            'item_price'=>$item_price,
            'purchase_qty'=>$purchase_qty,
            'purchase_remark'=>$purchase_remark,
            'cart_key'=>$postCartRef
        ];

        $updateCart_table = 'cart/'.$postCartRef;
        $updateCartRef = $database->getReference($updateCart_table)->update($cartUpdate);

        if($updateCartRef){
            $_SESSION['status'] = "Added Reorder Item to Cart Successfully.";
            header("Location: orderhistory.php#order_history");
            die();
        }
    } else {
        foreach ($cartItems as $cartItem){
            $cartItemSKU = $cartItem['item_sku'];
            $cartUserID = $cartItem['uid'];
            if($item_sku === $cartItemSKU){
                if ($user_id === $cartUserID){
                    $newPurchase_qty = $purchase_qty + $cartItem['purchase_qty'];
                    $cartData = [
                        'uid'=>$user_id,
                        'item_sku'=>$item_sku,
                        'item_name'=>$item_name,
                        'item_imgurl'=>$item_imgurl,
                        'item_price'=>$item_price,
                        'purchase_qty'=>$newPurchase_qty,
                        'purchase_remark'=>$purchase_remark,
                        'cart_key'=>$cartItem['cart_key']
                    ];

                    $cartKey = $cartItem['cart_key'];
                    $updateCart_table = 'cart/'.$cartKey;
                    $postCartRef = $database->getReference($updateCart_table)->update($cartData);

                    if($postCartRef){
                        $_SESSION['status'] = "Added Reorder Item to Cart Successfully.";
                        header("Location: orderhistory.php#order_history");
                        die();
                    }
                }

            } else {
                $cartData = [
                    'uid'=>$user_id,
                    'item_sku'=>$item_sku,
                    'item_name'=>$item_name,
                    'item_imgurl'=>$item_imgurl,
                    'item_price'=>$item_price,
                    'purchase_qty'=>$purchase_qty,
                    'purchase_remark'=>$purchase_remark,
                    'cart_key'=>''
                ];
                $postCartRef = $database->getReference($cart_table)->push($cartData)->getKey();
                $cartUpdate = [
                    'uid'=>$user_id,
                    'item_sku'=>$item_sku,
                    'item_name'=>$item_name,
                    'item_imgurl'=>$item_imgurl,
                    'item_price'=>$item_price,
                    'purchase_qty'=>$purchase_qty,
                    'purchase_remark'=>$purchase_remark,
                    'cart_key'=>$postCartRef
                ];

                $updateCart_table = 'cart/'.$postCartRef;
                $updateCartRef = $database->getReference($updateCart_table)->update($cartUpdate);

                if($updateCartRef){
                    $_SESSION['status'] = "Added Reorder Item to Cart Successfully.";
                    header("Location: orderhistory.php#order_history");
                    die();
                }
            }
        }
    }
}


//Remove from order history and add to cart again
if (isset($_POST['btnRemReorder'])){
    $orderID = $_POST['orderID'];
    $user_id = $_POST['user_id'];
    $item_sku = $_POST['item_sku'];
    $item_name = $_POST['item_name'];
    $item_imgurl = $_POST['item_imgurl'];
    $item_price = $_POST['item_price'];
    $purchase_qty = $_POST['purchase_qty'];
    $purchase_remark = $_POST['purchase_remark'];
    $cart_key = '';
    
    $deleteOrder_table = 'order/'.$orderID;
    $deleteOrderRef = $database->getReference($deleteOrder_table)->remove();
    
    //Add back stock balance to rejected order
    foreach ($products as $product){
        $productSKU = $product['sku'];
        if ($productSKU == $item_sku){
            $newSB = $product['stockbalance'] + $purchase_qty;
            
            $productSBUpdate = [
                'stockbalance' => $newSB
            ];
            
            $updateProductSB_table = $ref_table.'/'.$productSKU;
            $updateProductSBRef = $database->getReference($updateProductSB_table)->update($productSBUpdate);
        }
    }
    
    if (empty($cartUIDItems)){
        $cartData = [
            'uid'=>$user_id,
            'item_sku'=>$item_sku,
            'item_name'=>$item_name,
            'item_imgurl'=>$item_imgurl,
            'item_price'=>$item_price,
            'purchase_qty'=>$purchase_qty,
            'purchase_remark'=>$purchase_remark,
            'cart_key'=>''
        ];
        $postCartRef = $database->getReference($cart_table)->push($cartData)->getKey();
        $cartUpdate = [
            'uid'=>$user_id,
            'item_sku'=>$item_sku,
            'item_name'=>$item_name,
            'item_imgurl'=>$item_imgurl,
            'item_price'=>$item_price,
            'purchase_qty'=>$purchase_qty,
            'purchase_remark'=>$purchase_remark,
            'cart_key'=>$postCartRef
        ];

        $updateCart_table = 'cart/'.$postCartRef;
        $updateCartRef = $database->getReference($updateCart_table)->update($cartUpdate);

        if($updateCartRef){
            $_SESSION['status'] = "Added Reorder Item to Cart Successfully.";
            header("Location: orderhistory.php#order_history");
            die();
        }
    } else {
        foreach ($cartItems as $cartItem){
            $cartItemSKU = $cartItem['item_sku'];
            $cartUserID = $cartItem['uid'];
            if($item_sku === $cartItemSKU){
                if ($user_id === $cartUserID){
                    $newPurchase_qty = $purchase_qty + $cartItem['purchase_qty'];
                    $cartData = [
                        'uid'=>$user_id,
                        'item_sku'=>$item_sku,
                        'item_name'=>$item_name,
                        'item_imgurl'=>$item_imgurl,
                        'item_price'=>$item_price,
                        'purchase_qty'=>$newPurchase_qty,
                        'purchase_remark'=>$purchase_remark,
                        'cart_key'=>$cartItem['cart_key']
                    ];

                    $cartKey = $cartItem['cart_key'];
                    $updateCart_table = 'cart/'.$cartKey;
                    $postCartRef = $database->getReference($updateCart_table)->update($cartData);

                    if($postCartRef){
                        $_SESSION['status'] = "Added Reorder Item to Cart Successfully.";
                        header("Location: orderhistory.php#order_history");
                        die();
                    }
                }

            } else {
                $cartData = [
                    'uid'=>$user_id,
                    'item_sku'=>$item_sku,
                    'item_name'=>$item_name,
                    'item_imgurl'=>$item_imgurl,
                    'item_price'=>$item_price,
                    'purchase_qty'=>$purchase_qty,
                    'purchase_remark'=>$purchase_remark,
                    'cart_key'=>''
                ];
                $postCartRef = $database->getReference($cart_table)->push($cartData)->getKey();
                $cartUpdate = [
                    'uid'=>$user_id,
                    'item_sku'=>$item_sku,
                    'item_name'=>$item_name,
                    'item_imgurl'=>$item_imgurl,
                    'item_price'=>$item_price,
                    'purchase_qty'=>$purchase_qty,
                    'purchase_remark'=>$purchase_remark,
                    'cart_key'=>$postCartRef
                ];

                $updateCart_table = 'cart/'.$postCartRef;
                $updateCartRef = $database->getReference($updateCart_table)->update($cartUpdate);

                if($updateCartRef){
                    $_SESSION['status'] = "Added Reorder Item to Cart Successfully.";
                    header("Location: orderhistory.php#order_history");
                    die();
                }
            }
        }
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
        <h2>Order History</h2>
    </div>
    <div class="container mt-5 px-4 py-4 border rounded bg-white" id="order_history">
        <?php if (empty($orderItems)): ?>
        <p>No orders yet.</p>
        <div class="text-end">
            <a href="products.php#product_list" class="btn btn-outline-secondary">Continue Shopping</a>
        </div>
        
        <?php else: ?>
        <table class="table">
            <thead>
                <tr style="text-align: center">
                    <th scope="col">Transaction ID</th>
                    <th scope="col">Ordered On</th>
                    <th scope="col">SKU</th>
                    <th scope="col">Item</th>
                    <th scope="col">Item Name</th>
                    <th scope="col">Price (RM)</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderItems as $orderItem): ?>
                <?php 
                $orderItemStatus = $orderItem['status'];
                $orderItemSKU = $orderItem['item_sku'];
                if ($orderItemStatus == 'Rejected'): ?>
                    <tr style="vertical-align: middle; text-align: center;">
                        <td><?php echo $orderItem['txnID']; ?></td>
                        <td><?php echo $orderItem['datetime']; ?></td>
                        <td><?php echo $orderItem['item_sku']; ?></td>
                        <?php foreach ($products as $product): ?>
                        <?php 
                        $productSKU = $product['sku'];
                        if ($productSKU == $orderItemSKU): ?>
                        <td><img src="<?php echo $product['product_imgurl']; ?>" style="min-width:100px; overflow:hidden; max-height:100px;" alt="<?php echo $product['product_name']?>"/></td>
                        <td><?php echo $product['product_name']; ?></td>
                        <td><?php echo $product['product_price']; ?></td>

                        <td><?php echo $orderItem['purchase_qty']; ?></td>
                        <td><font color="#FF0000"><?php echo $orderItem['status']; ?></font></td>
                        <td>
                            <form id="reorderForm" method="POST">
                                <input type="hidden" name="user_id" value="<?php echo $uid; ?>">
                                <input type="hidden" name="orderID" value="<?php echo $orderItem['orderID']; ?>">
                                <input type="hidden" name="item_sku" value="<?php echo $orderItem['item_sku']; ?>">
                                <input type="hidden" name="item_name" value="<?php echo $product['product_name']; ?>">
                                <input type="hidden" name="item_imgurl" value="<?php echo $product['product_imgurl']; ?>">
                                <input type="hidden" name="item_price" value="<?php echo $product['product_price']; ?>">
                                <input type="number" name="purchase_qty" value="<?php echo $orderItem['purchase_qty'];?>" hidden="">
                                <input type="text" name="purchase_remark" value="<?php echo $orderItem['purchase_remark']; ?>" hidden="">

                                <button type="submit" class="btn btn-outline-secondary btn-sm" name="btnRemReorder">Remove and Reorder</button>
                            </form>
                        </td>
                        <?php endif;?>
                        <?php endforeach; ?>
                    </tr>
                <?php endif; ?>
                <?php endforeach; ?>
                
                <?php foreach ($orderItems as $orderItem): ?>
                <?php 
                $orderItemStatus = $orderItem['status'];
                $orderItemSKU = $orderItem['item_sku'];
                if ($orderItemStatus == 'Pending'): ?>
                    <tr style="vertical-align: middle; text-align: center;">
                        <td><?php echo $orderItem['txnID']; ?></td>
                        <td><?php echo $orderItem['datetime']; ?></td>
                        <td><?php echo $orderItem['item_sku']; ?></td>
                        <?php foreach ($products as $product): ?>
                        <?php 
                        $productSKU = $product['sku'];
                        if ($productSKU == $orderItemSKU): ?>
                        <td><img src="<?php echo $product['product_imgurl']; ?>" style="min-width:100px; overflow:hidden; max-height:100px;" alt="<?php echo $product['product_name']?>"/></td>
                        <td><?php echo $product['product_name']; ?></td>
                        <td><?php echo $product['product_price']; ?></td>

                        <td><?php echo $orderItem['purchase_qty']; ?></td>
                        <td><?php echo $orderItem['status']; ?></td>
                        <td>
                            <form id="reorderForm" method="POST">
                                <input type="hidden" name="user_id" value="<?php echo $uid; ?>">
                                <input type="hidden" name="item_sku" value="<?php echo $orderItem['item_sku']; ?>">
                                <input type="hidden" name="item_name" value="<?php echo $product['product_name']; ?>">
                                <input type="hidden" name="item_imgurl" value="<?php echo $product['product_imgurl']; ?>">
                                <input type="hidden" name="item_price" value="<?php echo $product['product_price']; ?>">
                                <input type="number" name="purchase_qty" value="<?php echo $orderItem['purchase_qty'];?>" hidden="">
                                <input type="text" name="purchase_remark" value="<?php echo $orderItem['purchase_remark']; ?>" hidden="">

                                <button type="submit" class="btn btn-outline-secondary btn-sm" name="btnReorder">Reorder</button>
                            </form>
                        </td>
                        <?php endif;?>
                        <?php endforeach; ?>
                    </tr>
                <?php endif; ?>
                <?php endforeach; ?>
                
                <?php foreach ($orderItems as $orderItem): ?>
                <?php 
                $orderItemStatus = $orderItem['status'];
                $orderItemSKU = $orderItem['item_sku'];
                if ($orderItemStatus != 'Pending' && $orderItemStatus != 'Completed' && $orderItemStatus != 'Rejected'): ?>
                    <tr style="vertical-align: middle; text-align: center;">
                        <td><?php echo $orderItem['txnID']; ?></td>
                        <td><?php echo $orderItem['datetime']; ?></td>
                        <td><?php echo $orderItem['item_sku']; ?></td>
                        <?php foreach ($products as $product): ?>
                        <?php 
                        $productSKU = $product['sku'];
                        if ($productSKU == $orderItemSKU): ?>
                        <td><img src="<?php echo $product['product_imgurl']; ?>" style="min-width:100px; overflow:hidden; max-height:100px;" alt="<?php echo $product['product_name']?>"/></td>
                        <td><?php echo $product['product_name']; ?></td>
                        <td><?php echo $product['product_price']; ?></td>

                        <td><?php echo $orderItem['purchase_qty']; ?></td>
                        <td><?php echo $orderItem['status']; ?></td>
                        <td>
                            <form id="reorderForm" method="POST">
                                <input type="hidden" name="user_id" value="<?php echo $uid; ?>">
                                <input type="hidden" name="item_sku" value="<?php echo $orderItem['item_sku']; ?>">
                                <input type="hidden" name="item_name" value="<?php echo $product['product_name']; ?>">
                                <input type="hidden" name="item_imgurl" value="<?php echo $product['product_imgurl']; ?>">
                                <input type="hidden" name="item_price" value="<?php echo $product['product_price']; ?>">
                                <input type="number" name="purchase_qty" value="<?php echo $orderItem['purchase_qty'];?>" hidden="">
                                <input type="text" name="purchase_remark" value="<?php echo $orderItem['purchase_remark']; ?>" hidden="">

                                <button type="submit" class="btn btn-outline-secondary btn-sm" name="btnReorder">Reorder</button>
                            </form>
                        </td>
                        <?php endif;?>
                        <?php endforeach; ?>
                    </tr>
                <?php endif; ?>
                <?php endforeach; ?>
                
                <?php foreach ($orderItems as $orderItem): ?>
                <?php 
                $orderItemStatus = $orderItem['status'];
                $orderItemSKU = $orderItem['item_sku'];
                if ($orderItemStatus == 'Completed'): ?>
                    <tr style="vertical-align: middle; text-align: center;">
                        <td><?php echo $orderItem['txnID']; ?></td>
                        <td><?php echo $orderItem['datetime']; ?></td>
                        <td><?php echo $orderItem['item_sku']; ?></td>
                        <?php foreach ($products as $product): ?>
                        <?php 
                        $productSKU = $product['sku'];
                        if ($productSKU == $orderItemSKU): ?>
                        <td><img src="<?php echo $product['product_imgurl']; ?>" style="min-width:100px; overflow:hidden; max-height:100px;" alt="<?php echo $product['product_name']?>"/></td>
                        <td><?php echo $product['product_name']; ?></td>
                        <td><?php echo $product['product_price']; ?></td>

                        <td><?php echo $orderItem['purchase_qty']; ?></td>
                        <td><?php echo $orderItem['status']; ?></td>
                        <td>
                            <form id="reorderForm" method="POST">
                                <input type="hidden" name="user_id" value="<?php echo $uid; ?>">
                                <input type="hidden" name="item_sku" value="<?php echo $orderItem['item_sku']; ?>">
                                <input type="hidden" name="item_name" value="<?php echo $product['product_name']; ?>">
                                <input type="hidden" name="item_imgurl" value="<?php echo $product['product_imgurl']; ?>">
                                <input type="hidden" name="item_price" value="<?php echo $product['product_price']; ?>">
                                <input type="number" name="purchase_qty" value="<?php echo $orderItem['purchase_qty'];?>" hidden="">
                                <input type="text" name="purchase_remark" value="<?php echo $orderItem['purchase_remark']; ?>" hidden="">

                                <button type="submit" class="btn btn-outline-secondary btn-sm" name="btnReorder">Reorder</button>
                            </form>
                        </td>
                        <?php endif;?>
                        <?php endforeach; ?>
                    </tr>
                <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php endif; ?>
    </div>
    <br>
</div>
<?php
include 'includes\footer.php';
?>