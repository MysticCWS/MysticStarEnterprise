<?php
session_start();
include 'dbcon.php';
include 'includes\header.php'; 
echo ' | Checkout';
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

//Fetch user delivery address from database
$delivery_table = 'delivery';
$deliveryDatas = $database->getReference($delivery_table)->getValue($uid);

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
        <h2>Payment</h2>
    </div>
    
    <div class="container mt-5 px-4 py-4 border rounded bg-white" id="shopping_cart">
        <?php 
        $purchase_total = 0;
        foreach ($cartItems as $cartItem): ?>
        <?php 
        $cartUserID = $cartItem['uid'];
        if ($uid === $cartUserID): ?>
        
        <?php $cartItemTotalPrice = $cartItem['item_price'] * $cartItem['purchase_qty']; ?>
        
        <?php $purchase_total = $purchase_total + $cartItemTotalPrice; ?>
        <?php endif; ?>
        <?php endforeach; ?>
        
        <div class="title">
            <h2>Order Confirmation</h2>
            <br>
        </div>
        <div class="container c-wrapper">
            <h5>
                Please pay the exact amount of <b>
                <?php
                    echo "RM";
                    echo number_format($purchase_total, 2);
                ?>
                </b> to the DuitNow QR below and key in the transaction ID in the field below.
            </h5>
        </div>
        <div class="container c-wrapper">
            <p>
                <img src="https://firebasestorage.googleapis.com/v0/b/mysticstarenterprise.appspot.com/o/images%2FMSE_HLB_DN_QR.png?alt=media" width="50%" alt="DuitNow QR Not Loaded Properly? You may pay to the Bank Account: HONG LEONG BANK 29301018606"/>
            </p>
        </div>
        <div class="container">
            <form id="orderForm" class="was-validated" method="POST" >
                <input type="text" class="form-control" id="uid" name="uid" value="<?php echo $deliveryData['uid']; ?>" hidden="">
        </div>
    </div>
</div>