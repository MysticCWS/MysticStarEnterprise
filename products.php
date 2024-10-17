<?php 
session_start();
include 'dbcon.php';
include 'includes\header.php'; 
echo ' | Products';
include 'includes\header2.php';
include 'includes\navbar.php';

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
                                        <form id="orderForm" method="POST" action="function_order.php">
                                            <div class="mb-3">
                                                <input type="hidden" name="user_id" value="<?php echo $user_data['user_id']; ?>">
                                                <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                                                <label for="quantity-<?php echo $item['item_id']; ?>" class="form-label">Quantity</label>
                                                <input type="number" class="form-control" name="purchase_qty" id="quantity-<?php echo $item['item_id']; ?>" value="1" min="1">
                                            </div>
                                            <div class="mb-3">
                                                <label for="location-<?php echo $item['item_id']; ?>" class="form-label">Order Location</label>
                                                <select class="form-select" name="purchase_location" id="location-<?php echo $item['item_id']; ?>" required>
                                                    <option value="">Select Location</option>
                                                    <option value="Level 2 Foyer">Level 2 Foyer</option>
                                                    
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="time-<?php echo $item['item_id']; ?>" class="form-label">Order Time</label>
                                                <input type="time" class="form-control" id="time-<?php echo $item['item_id']; ?>" name="purchase_time" value="<?php echo date('H:i') ?>" required>
                                            </div>
                                            <button type="submit" class="btn btn-outline-secondary">Add to Cart</button>
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