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

//Save address
if (isset($_POST['btnSaveAddress'])){
    $attn = $_POST['attn'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $postcode = $_POST['postcode'];
    $state = $_POST['state'];
    
    $addressData = [
        'uid' => $uid,
        'attn' => $attn,
        'address1' => $address1,
        'address2' => $address2,
        'postcode' => $postcode,
        'state' => $state
    ];
    
    $deliveryKey = $uid;
    $updateDelivery_table = 'delivery/'.$deliveryKey;
    $updateDeliveryRef = $database->getReference($updateDelivery_table)->update($addressData);
    
    if($updateDeliveryRef){
        $_SESSION['status'] = "Delivery Address Updated Successfully.";
        header("Location: checkout.php");
        die();
    }
}

//Save and proceed to payment
if (isset($_POST['btnSaveAddressProceed'])){
    $attn = $_POST['attn'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $postcode = $_POST['postcode'];
    $state = $_POST['state'];
    
    $addressData = [
        'uid' => $uid,
        'attn' => $attn,
        'address1' => $address1,
        'address2' => $address2,
        'postcode' => $postcode,
        'state' => $state
    ];
    
    $deliveryKey = $uid;
    $updateDelivery_table = 'delivery/'.$deliveryKey;
    $updateDeliveryRef = $database->getReference($updateDelivery_table)->update($addressData);
    
    if($updateDeliveryRef){
        $_SESSION['status'] = "Details Confirmed Successfully.";
        header("Location: payment.php");
        die();
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
        <h2>Checkout</h2>
    </div>
    
    <div class="container mt-5 px-4 py-4 border rounded bg-white" id="shopping_cart">
        <div class="title">
            <h2>Order Summary</h2>
        </div>
        <br>
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
                        
                    </tr>
                    
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
            <a href="cart.php" class="btn btn-outline-secondary">Back to Cart</a>
        </div>
    </div>
    
    <?php foreach ($deliveryDatas as $deliveryData): ?>
    <?php 
    $deliveryUID = $deliveryData['uid'];
    if ($uid == $deliveryUID): ?>
    <div class="container mt-5 px-4 py-4 border rounded bg-white" id="deliveryAddress">
        <div class="title">
            <h2>Confirm Delivery Address</h2>
        </div>
        <br>
        <form id="deliveryAddressForm" class="was-validated" method="POST" >
            <input type="text" class="form-control" id="uid" name="uid" value="<?php echo $deliveryData['uid']; ?>" hidden="">
            
            <label for="attn">Attention: </label>
            <input type="text" class="form-control" id="attn" name="attn" value="<?php echo $deliveryData['attn']; ?>" required=""><br>
            
            <label for="address1">Address Line 1: </label>
            <input type="text" class="form-control" id="address1" name="address1" value="<?php echo $deliveryData['address1']; ?>" required=""><br>
            
            <label for="address2">Address Line 2 (Optional): </label>
            <input type="text" class="form-control" id="address2" name="address2" value="<?php echo $deliveryData['address2']; ?>"><br>
            
            <label for="postcode">Postcode: </label>
            <input type="text" class="form-control" id="postcode" name="postcode" value="<?php echo $deliveryData['postcode']; ?>" required=""><br>
            
            <label for="state">State (Pick from Dropdown): </label>
            <select class="form-control" id="state" name="state" value="<?php echo $deliveryData['state']; ?>" required="">
                <option value="<?php echo $deliveryData['state']; ?>"><?php echo $deliveryData['state']; ?></option>
                <option value="Johor">Johor</option>
                <option value="Kedah">Kedah</option>
                <option value="Kelantan">Kelantan</option>
                <option value="Malacca">Melacca</option>
                <option value="Negeri Sembilan">Negeri Sembilan</option>
                <option value="Pahang">Pahang</option>
                <option value="Penang">Penang</option>
                <option value="Perak">Perak</option>
                <option value="Perlis">Perlis</option>
                <option value="Sabah">Sabah</option>
                <option value="Sarawak">Sarawak</option>
                <option value="Selangor">Selangor</option>
                <option value="Terengganu">Terengganu</option>
            </select><br>
            
            <button type="submit" class="btn btn-outline-secondary" name="btnSaveAddress">Save</button>
            <button type="submit" class="btn btn-outline-secondary" name="btnSaveAddressProceed">Proceed</button>
        </form>
    </div>
    <?php break; ?>
    <?php else: ?>
    <div class="container mt-5 px-4 py-4 border rounded bg-white" id="deliveryAddress">
        <div class="title">
            <h2>Confirm Delivery Address</h2>
        </div>
        <br>
        <form id="deliveryAddressForm" class="was-validated" method="POST" >
            <input type="text" class="form-control" id="uid" name="uid" value="<?php echo $uid; ?>" hidden="">
            
            <label for="attn">Attention: </label>
            <input type="text" class="form-control" id="attn" name="attn" value="<?php echo $user->displayName; ?>" required=""><br>
            
            <label for="address1">Address Line 1: </label>
            <input type="text" class="form-control" id="address1" name="address1" value="" required=""><br>
            
            <label for="address2">Address Line 2 (Optional): </label>
            <input type="text" class="form-control" id="address2" name="address2" value=""><br>
            
            <label for="postcode">Postcode: </label>
            <input type="text" class="form-control" id="postcode" name="postcode" value="" required=""><br>
            
            <label for="state">State (Pick from Dropdown): </label>
            <select class="form-control" id="state" name="state" required="">
                <option value="">Select a State</option>
                <option value="Johor">Johor</option>
                <option value="Kedah">Kedah</option>
                <option value="Kelantan">Kelantan</option>
                <option value="Malacca">Melacca</option>
                <option value="Negeri Sembilan">Negeri Sembilan</option>
                <option value="Pahang">Pahang</option>
                <option value="Penang">Penang</option>
                <option value="Perak">Perak</option>
                <option value="Perlis">Perlis</option>
                <option value="Sabah">Sabah</option>
                <option value="Sarawak">Sarawak</option>
                <option value="Selangor">Selangor</option>
                <option value="Terengganu">Terengganu</option>
            </select><br>
            
            <button type="submit" class="btn btn-outline-secondary" name="btnSaveAddress">Save</button>
            <button type="submit" class="btn btn-outline-secondary" name="btnSaveAddressProceed">Proceed</button>
        </form>
    </div>
    <?php break; ?>
    <?php endif; ?>
    <?php endforeach; ?>


</div>
<br>

<?php
include 'includes\footer.php';
?>