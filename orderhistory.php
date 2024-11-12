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
                        <?php endif;?>
                        <?php endforeach; ?>
                        <td><?php echo $orderItem['purchase_qty']; ?></td>
                        <td><?php echo $orderItem['status']; ?></td>
                        <td>
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editCartModal-<?php echo $cartItem['item_sku']; ?>">
                                Reorder
                            </button>
                        </td>
                    </tr>
                <?php endif; ?>
                <?php endforeach; ?>
                
                <?php foreach ($orderItems as $orderItem): ?>
                <?php 
                $orderItemStatus = $orderItem['status'];
                if ($orderItemStatus != 'Pending' && $orderItemStatus != 'Completed'): ?>
                
                <?php endif; ?>
                <?php endforeach; ?>
                
                <?php foreach ($orderItems as $orderItem): ?>
                <?php 
                $orderItemStatus = $orderItem['status'];
                if ($orderItemStatus == 'Completed'): ?>
                
                <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php endif; ?>
    </div>