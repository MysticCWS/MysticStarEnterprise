<?php
include 'dbcon.php'; // Include Firebase database connection

if (isset($_POST['brand'])) {
    $selectedBrand = $_POST['brand'];
    $models = $database->getReference("printerlist/$selectedBrand")->getValue();
    
    if ($models) {
        echo json_encode(array_values($models)); // Return models in JSON format
    } else {
        echo json_encode([]); // Return an empty array if no models found
    }
}
?>
