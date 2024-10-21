<?php 
session_start();
include 'dbcon.php';
include 'includes\header.php'; 
echo ' | Products';
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


//Fetch coffee item from database
//    $item_query = "select * from item";
//    $item_result = mysqli_query($con, $item_query);
//
//    $items = [];
//    if ($item_result->num_rows > 0) {
//        while($item_row = $item_result->fetch_assoc()) {
//            $items[] = $item_row;
//        }
//    }
    
//$carouselurlprefix = "https://firebasestorage.googleapis.com/v0/b/mysticstarenterprise.appspot.com/o/carousel%2F";
//$carouselurlsuffix = "?alt=media";
//$carouselurl = $carouselurlprefix.$carouselnum.$carouselurlsuffix;
?>

<div class="content">
    <div class="title">
        <h2>Our Products</h5>
    </div>
    
    <div class="container mt-5 px-4 py-4 border rounded bg-white" id="product_list">
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <img class="card-img-top" src="<?php echo $product['product_imgurl']; ?>" alt="<?php echo $product['product_name']; ?>">
                        <div class="card-body">
                            <h4 class="card-title"><?php echo $product['product_name']; ?></h4>
                            <h5>RM <?php echo $product['product_price']; ?></h5>
                            <p class="card-text"><?php echo $product['product_description']; ?></p>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modifyOrderModal-<?php echo $product['product_id']; ?>">
                                Add to Cart
                            </button>
                        </div>

                        <!-- Modify Order Modal -->
                        <div class="modal fade" id="modifyOrderModal-<?php echo $product['product_id']; ?>" tabindex="-1" aria-labelledby="modifyOrderModalLabel-<?php echo $product['product_id']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modifyOrderModalLabel-<?php echo $product['product_id']; ?>">Modify Order: <?php echo $product['product_name']; ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form for modifying order -->
                                        <form id="addtocartForm" method="POST" action="function_addtocart.php">
                                            <div class="mb-3">
                                                <input type="hidden" name="uid" value="<?php echo $uid; ?>">
                                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                                <p>Price per Unit: RM <?php echo $product['product_price'];?></p>
                                                <label for="quantity-<?php echo $product['product_id']; ?>" class="form-label">Quantity</label>
                                                <input type="number" class="form-control" name="purchase_qty" id="quantity-<?php echo $product['product_id']; ?>" value="1" min="1">
                                            </div>
                                            <div class="mb-3">
                                                <label for="remark-<?php echo $product['product_id']; ?>" class="form-label">Order Remarks (Optional)</label>
                                                <input type="text" class="form-control" id="remark-<?php echo $product['product_id']; ?>" name="purchase_remark" value="<?php echo '' ?>">
                                            </div>
                                            <button type="submit" class="btn btn-outline-secondary" name="">Add to Cart</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>