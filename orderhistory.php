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
            
        </table>
        
        <?php endif; ?>
    </div>