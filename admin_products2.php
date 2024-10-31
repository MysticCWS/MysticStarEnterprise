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

if (isset($_POST['btnUpdateStock'])){
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
        'stockbalance' => $stockbalance,
        'product_imgurl' => $product_imgurl
    ];
    
    $updateProduct_table = 'products/'.$sku;
    $updateProductRef = $database->getReference($updateProduct_table)->update($productProperties);
    
    if($updateProductRef){
        $_SESSION['status'] = "Saved Changes Successfully.";
        header("Location: admin_products.php#product_list");
        die();
    }
}

//Add new product

//Fetch product from database
$ref_table = 'product';
$products = $database->getReference($ref_table)->getValue();

?>

<div class="content">
    <?php foreach ($products as $product): ?>
    <?php 
    $productSKU = $product['sku'];
    $sku = $_GET['sku'];
    if ($productSKU == $sku): ?>
        <div class="title">
            <h2>Update Stock</h5>
        </div>
    
        <div class="container mt-5 px-4 py-4 border rounded bg-white" id="addproduct">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Update Stock Details</h5>
            </div>
            <div class="modal-body">
                <!-- Form for product sku -->
                <form id="updateStockForm" method="POST" >
                    <div class="mb-3">
                        <br>
                        <label for="name">SKU</label>
                        <input type="text" class="form-control" id="sku" name="sku" value="<?php echo $sku; ?>" readonly=""><br>
                        
                        <div class="" onclick="document.getElementById('file-input').click();">
                            <img src="<?php echo $product['product_imgurl']; ?>" alt="Upload Product Picture of <?php echo $sku; ?>" id="product-img">
                            <div class="edit-photo">Edit Photo</div>
                        </div>
                        <!-- Hidden file input to upload image --> 
                        <input type="file" class="form-control" id="file-input" accept="image/png" name="myfile" onchange="previewImage(event)">
                        <br>

                        <label for="product_name">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo $product['product_name']; ?>" required=""><br>

                        <label for="product_price">Price per Unit</label>
                        <input type="text" class="form-control" id="product_price" name="product_price" value="<?php echo $product['product_price']; ?>" required=""><br>

                        <label for="stockbalance">Stock Balance</label>
                        <input type="number" class="form-control" name="stockbalance" id="stockbalance" value="<?php echo $product['stockbalance']; ?>" min="0" max="999" required=""><br>

                        <label for="product_description">Product Description</label>
                        <input type="text" class="form-control" id="product_description" name="product_description" value="<?php echo $product['product_description']; ?>" required=""><br>
                    </div>

                    <button type="submit" class="btn btn-outline-secondary" name="btnUpdateStock">Update</button>
                </form>
            </div>
        </div>
    
    <?php else: ?>
        <div class="title">
            <h2>Add Product</h5>
        </div>
    
        <div class="container mt-5 px-4 py-4 border rounded bg-white" id="addproduct">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
            </div>
            <div class="modal-body">
                <!-- Form for product sku -->
                <form id="addProductForm" method="POST" >
                    <div class="mb-3">
                        <br>
                        <label for="name">SKU</label>
                        <input type="text" class="form-control" id="sku" name="sku" value="<?php echo $sku; ?>" readonly=""><br>
                        
                        <div class="" onclick="document.getElementById('file-input').click();">
                            <img src="" alt="Upload Product Picture of <?php echo $sku; ?>" id="product-img">
                            <div class="edit-photo">Edit Photo</div>
                        </div>
                        <!-- Hidden file input to upload image --> 
                        <input type="file" class="form-control" id="file-input" accept="image/png" name="myfile" onchange="previewImage(event)">
                        <br>

                        <label for="product_name">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" value="" required=""><br>

                        <label for="product_price">Price per Unit</label>
                        <input type="text" class="form-control" id="product_price" name="product_price" value="" required=""><br>

                        <label for="stockbalance">Stock Balance</label>
                        <input type="number" class="form-control" name="stockbalance" id="stockbalance" value="" min="0" max="999" required=""><br>

                        <label for="product_description">Product Description</label>
                        <input type="text" class="form-control" id="product_description" name="product_description" value="" required=""><br>
                    </div>

                    <button type="submit" class="btn btn-outline-secondary" name="btnAddProduct">Add</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
    <?php endforeach; ?>
    <br>
</div>
<?php
include 'includes\footer.php';
?>