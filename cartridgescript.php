<?php
session_start();
include 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uid = $_POST['user_id'];
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $quantity = $_POST['quantity'];
    $paymentMethod = $_POST['payment_method'];
    $bankType = $_POST['bank'] ?? ''; // Capture selected bank when payment method is 'bank_account'
    $status = 'pending'; // Set initial status to "pending"

    // Initialize variables
    $imageURL = '';
    $payment_details = '';

    // Validate file upload
    if (isset($_FILES['cartridge_image']) && $_FILES['cartridge_image']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['cartridge_image']['tmp_name'];
        $fileName = $_FILES['cartridge_image']['name'];
        $fileSize = $_FILES['cartridge_image']['size'];
        $fileType = mime_content_type($fileTmpPath);

        // File size limit (5MB in this case)
        $maxFileSize = 5 * 1024 * 1024;
        $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if ($fileSize > $maxFileSize) {
            echo "Error: File size exceeds the 5MB limit.";
            exit();
        }

        if (!in_array($fileType, $allowedFileTypes)) {
            echo "Error: Invalid file type. Only JPG, PNG, and GIF are allowed.";
            exit();
        }

        // Generate unique file name to prevent overwriting
        $uniqueFileName = uniqid() . '-' . $fileName;
        $filePath = 'uploads/' . $uniqueFileName;

        // Upload the file to Firebase Storage
        $bucket = $storage->getBucket();
        $fileUpload = fopen($fileTmpPath, 'r');
        $object = $bucket->upload($fileUpload, ['name' => $filePath]);

        // Check if the file was uploaded successfully
        if ($object) {
            // Get the URL of the uploaded file (signed for 1 year)
            $storageRef = $bucket->object($filePath);
            $imageURL = $storageRef->signedUrl(new \DateTime('+1 year'));
        } else {
            echo "Error: Failed to upload the image.";
            exit();
        }
    }

    // Prepare payment details
    if ($paymentMethod === 'bank_account') {
        $payment_details = $_POST['bank_account'] ?? '';
        // Ensure bankType is filled if bank account is selected
        if (empty($bankType)) {
            echo "Error: Please select a bank.";
            exit();
        }
    } elseif ($paymentMethod === 'ewallet') {
        $payment_details = $_POST['phone'] ?? '';
    }

    // Validate required fields
    if (empty($uid) || empty($brand) || empty($model) || $quantity <= 0 || empty($paymentMethod) || empty($imageURL)) {
        echo "Error: Please fill in all required fields.";
        exit();
    }

    // Store submission data in Firebase Realtime Database
    $data = [
        'user_id' => $uid,
        'name' => $name,
        'brand' => $brand,
        'model' => $model,
        'quantity' => $quantity,
        'payment_method' => $paymentMethod,
        'payment_details' => $payment_details,
        'bank_type' => $bankType, // Include bank type in data
        'image_url' => $imageURL,
        'status' => $status, // Store initial status as "pending"
    ];

    $database->getReference('cartridge_submissions/' . $uid)->push($data);

    // Redirect back to the form or display a success message
    header('Location: submitcartridge.php?status=success');
    exit();
}
?>
