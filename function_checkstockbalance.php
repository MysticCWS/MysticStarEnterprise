<?php
session_start();
include 'dbcon.php';
include 'includes\header.php'; 
echo ' | Checking Stock Balance';
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

foreach ($products as $product){
    $productSKU = $product['sku'];
    $productStockBalance = $product['stockbalance'];
    
    foreach ($cartItems as $cartItem){
        $cartUID = $cartItem['uid'];
        if ($uid == $cartUID){
            $cartQty = $cartItem['purchase_qty'];
            if ($cartQty > $productStockBalance){
                $_SESSION['status'] = "Some of your items purchase quantity exceeds the stock balance, please double check and try again.";
                header("Location: cart.php");
                die();
            }
        }
    }
}

header ("Location: checkout.php");
die();