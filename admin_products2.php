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
    <div class="title">
        <h2>Add Stock</h5>
    </div>
    <div class="title">
        <h2>Add New Product</h5>
    </div>
    
    <div class="container mt-5 px-4 py-4 border rounded bg-white" id="addproduct">
        
    </div>
</div>