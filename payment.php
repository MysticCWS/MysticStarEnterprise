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

date_default_timezone_set('Singapore');

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


//Move cart items to orders
if (isset($_POST['btnPayment'])){
    $uid = $_POST['uid'];
    $attn = $_POST['attn'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $postcode = $_POST['postcode'];
    $state = $_POST['state'];
    $ptotal = $_POST['ptotal'];
    $datetime = $_POST['datetime'];
    
    $txnID = $_POST['txnID'];
    
    foreach ($cartUIDItems as $cartUIDItem){
        $cartUIDSKU = $cartUIDItem['item_sku'];
        $cartUIDpqty = $cartUIDItem['purchase_qty']; 
        $orderData = [
            'uid' => $uid,
            'attn' => $attn,
            'address1' => $address1,
            'address2' => $address2,
            'postcode' => $postcode,
            'state' => $state,
            'ptotal' => $ptotal,
            'datetime' => $datetime,
            'txnID' => $txnID,
            'item_sku' => $cartUIDSKU,
            'purchase_qty' => $cartUIDpqty,
            'status' => 'Pending'
        ];

        $order_table = 'order';
        $postOrderRef = $database->getReference($order_table)->push($orderData)->getKey();
        $orderUpdate = [
            'orderID' => $postOrderRef
        ];

        $updateOrder_table = 'order/'.$postOrderRef;
        $updateOrderRef = $database->getReference($updateOrder_table)->update($orderUpdate);
        
        $cart_id = $cartUIDItem['cart_id'];
    
        $deleteCart_table = 'cart/'.$cart_id;
        $deleteCartRef = $database->getReference($deleteCart_table)->remove();
        
        foreach ($products as $product){
            $productSKU = $product['sku'];
            $productSB = $product['stockbalance'];
            
            if ($productSKU == $cartUIDSKU){
                $productSB = $productSB - $cartUIDpqty;
                
                $productSBUpdate = [
                    'stockbalance' => $productSB
                ];
                $updateProductSB_table = $ref_table.'/'.$productSKU;
                $updateProductSBRef = $database->getReference($updateProductSB_table)->update($productSBUpdate);
            }
        }
    }
    
    if($updateOrderRef){
        $_SESSION['status'] = "Order Placed Successfully.";
        header("Location: products.php#product_list");
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
            <h5 class="text-danger">Warning: Successfully placed orders are non-cancellable. Please confirm before placing order.</h5>
        </div>
        <div class="container c-wrapper">
            <p>
                <img src="https://firebasestorage.googleapis.com/v0/b/mysticstarenterprise.appspot.com/o/images%2FMSE_HLB_DN_QR.png?alt=media" width="50%" alt="DuitNow QR Not Loaded Properly? You may pay to the Bank Account: HONG LEONG BANK 29301018606"/>
            </p>
        </div>
        
        <?php foreach ($deliveryDatas as $deliveryData): ?>
        <?php 
        $deliveryUID = $deliveryData['uid'];
        if ($deliveryUID == $uid): ?>
        <div class="container">
            <form id="orderForm" class="was-validated" method="POST" >
                <input type="text" class="form-control" id="uid" name="uid" value="<?php echo $deliveryData['uid']; ?>" hidden="">
                <input type="text" class="form-control" id="attn" name="attn" value="<?php echo $deliveryData['attn']; ?>" hidden="">
                <input type="text" class="form-control" id="address1" name="address1" value="<?php echo $deliveryData['address1']; ?>" hidden="">
                <input type="text" class="form-control" id="address2" name="address2" value="<?php echo $deliveryData['address2']; ?>" hidden="">
                <input type="text" class="form-control" id="postcode" name="postcode" value="<?php echo $deliveryData['postcode']; ?>" hidden="">
                <input type="text" class="form-control" id="state" name="state" value="<?php echo $deliveryData['state']; ?>" hidden="">
                <input type="text" class="form-control" id="ptotal" name="ptotal" value="<?php echo $purchase_total; ?>" hidden="">
                <input type="datetime-local" class="form-control" id="datetime" name="datetime" value="<?php echo date("Y/m/d H:i:s"); ?>" hidden="">
                
                <label for="txnID">Transaction ID: </label>
                <input type="text" class="form-control" id="txnID" name="txnID" value="" required="">
                
                <br>
                <a href="checkout.php" class="btn btn-outline-secondary">Back to Checkout</a>
                <button type="submit" class="btn btn-outline-secondary" name="btnPayment">Complete Order</button>
            </form>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
<br>
<?php
include 'includes\footer.php';