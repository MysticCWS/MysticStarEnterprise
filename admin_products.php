<?php 
session_start();
include 'dbcon.php';
include 'includes\header.php'; 
echo ' | Manage Products';
include 'includes\header2.php';
include 'includes\navbar_admin.php';

//Get UID
if(isset($_SESSION['verified_user_id'])){
    $uid = $_SESSION['verified_user_id'];
    try {
        $user = $auth->getUser($uid);
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
        echo $e->getMessage();
    }
}

if (isset($_FILES['myfile']['name'])){
    $defaultBucket->upload(
        file_get_contents($_FILES['myfile']['tmp_name']),
        [
        'name' =>"products/".$sku.".png"
        ]
    );
}

if (isset($_POST['btnSaveChanges'])){
    $sku = $_POST['sku'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_description = $_POST['product_description'];
    $stockbalance = $_POST['stockbalance'];
    
    if (isset($_FILES['myfile']['name'])){
        $product_imgurlprefix = "https://firebasestorage.googleapis.com/v0/b/mysticstarenterprise.appspot.com/o/products%2F";
        $product_imrurlsuffix = "?alt=media";
        $product_imgurl = $product_imgurlprefix.$sku.".png".$product_imrurlsuffix;
        
        $defaultBucket->upload(
            file_get_contents($_FILES['myfile']['tmp_name']),
            [
            'name' =>"products/".$sku.".png"
            ]
        );
    } else {
        $product_imgurl = $_POST['product_imgurl'];
    }
    
    $productProperties = [
        'product_name' => $product_name,
        'product_price' => $product_price,
        'product_description' => $product_description,
        'stockbalance' => $stockbalance
    ];
    
    $updateProduct_table = 'products/'.$sku;
    $updateProductRef = $database->getReference($updateProduct_table)->update($productProperties);
    
    if($updateProductRef){
                $_SESSION['status'] = "Saved Changes Successfully.";
                header("Location: admin_products.php#product_list");
                die();
            }
}

//Fetch product from database
$ref_table = 'product';
$products = $database->getReference($ref_table)->getValue();

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
        <h2>Manage Products</h5>
    </div>
    
    <div class="container mt-5 px-4 py-4 border rounded bg-white" id="product_list">
        <div class="row">
            <div class="c-wrapper">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <br>&nbsp;&nbsp;Add Product&nbsp;&nbsp;<br><br>
                </button>
                <br><br>
            </div>
        </div>
        <div class="row">
            <?php foreach ($products as $product): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <img class="card-img-top" src="<?php echo $product['product_imgurl']; ?>" alt="<?php echo $product['product_name']; ?>">
                        <div class="card-body">
                            <h4 class="card-title"><?php echo $product['product_name']; ?></h4>
                            <h5>RM <?php echo number_format($product['product_price'], 2); ?></h5>
                            <p class="card-text">SKU: <?php echo $product['sku']; ?></p>
                            <p class="card-text"><?php echo $product['product_description']; ?></p>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal-<?php echo $product['sku']; ?>">
                                Edit Product
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#deleteProductModal-<?php echo $product['sku']; ?>">
                                Delete Product
                            </button>
                        </div>
                        
                        <!-- Add Product Modal -->
                        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form for product sku -->
                                        <form id="addProductForm" method="GET" action="admin_products2.php">
                                            <div class="mb-3">
                                                <label for="name">SKU</label>
                                                <input type="text" class="form-control" id="sku" name="sku" value="">
                                            </div>
                                                <button type="submit" class="btn btn-outline-secondary" name="btnAddProductNext">Next</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Product Modal -->
                        <div class="modal fade" id="editProductModal-<?php echo $product['sku']; ?>" tabindex="-1" aria-labelledby="editProductModalLabel-<?php echo $product['sku']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editProductModalLabel-<?php echo $product['sku']; ?>">Edit Product Details for SKU: <?php echo $product['sku']; ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form for editing product -->
                                        <form id="editProductForm" method="POST" >
                                            <div class="mb-3">
                                                <input type="hidden" name="sku" value="<?php echo $product['sku']; ?>">
                                                <div class="" onclick="document.getElementById('file-input').click();">
                                                    <img src="<?php echo $product['product_imgurl'];?>" alt="Product Picture of <?php echo $product['sku']; ?>" id="product-img">
                                                    <div class="edit-photo">Edit Photo</div>
                                                </div>
                                                <!-- Hidden file input to upload image -->
                                                <input type="file" class="form-control" id="file-input" accept="image/png" name="myfile" onchange="previewImage(event)">
                                                <br>
                                                
                                                <label for="product_name">Product Name: </label>
                                                <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo $product['product_name']; ?>" required=""><br>
                                                
                                                <label for="product_price">Price per Unit: </label>
                                                <input type="text" class="form-control" id="product_price" name="product_price" value="<?php echo $product['product_price']; ?>" required=""><br>
                                                
                                                <label for="stockbalance">Stock Balance: </label>
                                                <input type="number" class="form-control" name="stockbalance" id="stockbalance" value="<?php echo $product['stockbalance']; ?>" min="0" max="999" required=""><br>
                                                
                                                <label for="product_description">Product Description: </label>
                                                <input type="text" class="form-control" id="product_description" name="product_description" value="<?php echo $product['product_description']; ?>" required=""><br>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-outline-secondary" name="btnSaveChanges">Save Changes</button>
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
    <br>
</div>
<?php
include 'includes\footer.php';
?>
