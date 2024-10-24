<?php
session_start();
include 'dbcon.php';
include 'includes/header.php'; 
include 'includes/header2.php';
include 'includes/navbar.php'; 

if (!isset($_SESSION['verified_user_id'])) {
    echo "Please log in to submit a cartridge.";
    exit();
}

$uid = $_SESSION['verified_user_id'];

// Retrieve authenticated user data from Firebase
try {
    $user = $auth->getUser($uid);
} catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
    echo $e->getMessage();
    exit();
}



// Fetch models from the database based on selected brand if submitted
$selectedBrand = $_POST['brand'] ?? '';
$models = [];
$errorMessage = ''; // Initialize error message variable
if ($selectedBrand) {
    // Fetch models based on selected brand from Firebase
    $models = $database->getReference("printerlist/$selectedBrand")->getValue();
}

// Initialize the status variable
$status = ''; // Ensure $status is initialized to avoid undefined variable warning

// Check for status parameter
if (isset($_GET['status'])) {
    $status = $_GET['status'];
}



// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quantity = $_POST['quantity'] ?? 0;

    // Validate quantity
    if ($quantity < 0) {
        $errorMessage = "Quantity cannot be less than zero.";
    }
    // If quantity is valid, proceed with further processing...
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartridge Recycling Program</title>
    <link rel="stylesheet" href="cartridge.css">
</head>
<body>
    <div class="content">
        <div class="c-wrapper">
            <?php if ($status === 'success'): ?>
                <div class="success-message">
                    <p>Your cartridge submission was successful! Thank you for participating in our recycling program.</p>
                </div>
            <?php endif; ?>
            <h2 class="title">Cartridge Recycling Program</h2>
            <form action="cartridgescript.php" method="POST" enctype="multipart/form-data" class="cartridge-form">
                <input type="hidden" name="user_id" value="<?php echo $uid; ?>">

                <!-- Printer Brand Selection -->
                <div class="form-group">
                    <label for="brand">Please Select your Printer Brand</label>
                    <select id="brand" name="brand" class="input-field" required>
                        <option value="">--Select Brand--</option>
                        <option value="canon">Canon</option>
                        <option value="hp">HP</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="model">Model:</label>
                    <select id="model" name="model" class="input-field" required>
                        <option value="">--Select Model--</option>
                    </select>
                </div>

                <!-- Quantity Input -->
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" class="input-field" required min="0">
                </div>

                <!-- Payment Method Selection -->
                <div class="form-group">
                    <label for="paymentMethod">Preferred Payment Method:</label>
                    <select id="paymentMethod" name="payment_method" class="input-field" required>
                        <option value="">--Select Payment Method--</option>
                        <option value="bank_account">Bank Account</option>
                        <option value="ewallet">E-Wallet</option>
                    </select>
                </div>

                <!-- Bank Details Section (Initially Hidden) -->
                <div class="form-group" id="bankDetails" style="display: none;">
                    <label for="bankAccount">Bank Account Number:</label>
                    <input type="text" id="bankAccount" name="bank_account" class="input-field">
                </div>

                <!-- E-Wallet Details Section (Initially Hidden) -->
                <div class="form-group" id="ewalletDetails" style="display: none;">
                    <label for="phone">Phone Number for E-wallet:</label>
                    <input type="tel" id="phone" name="phone" class="input-field">
                </div>

                <!-- Cartridge Image Upload -->
                <div class="form-group">
                    <label for="cartridgeImage">Upload Cartridge Image:</label>
                    <input type="file" id="cartridgeImage" name="cartridge_image" class="input-field" accept="image/*" required>
                </div>

                <!-- Agree to Terms Checkbox -->
                <div class="form-group">
                    <input type="checkbox" id="termsCheckbox" required>
                    <label for="termsCheckbox">I agree to the <a href="#" id="termsLink">Terms and Conditions</a>.</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btnSave">Submit Cartridge</button>
            </form>
        </div>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Script for AJAX to fetch models dynamically -->
    <script>
        $(document).ready(function () {
            $('#brand').change(function () {
                var selectedBrand = $(this).val();
                
                if (selectedBrand) {
                    $.ajax({
                        type: "POST",
                        url: "fetchModels.php",
                        data: { brand: selectedBrand },
                        success: function (response) {
                            $('#model').empty().append('<option value="">--Select Model--</option>');
                            $.each(JSON.parse(response), function (index, model) {
                                $('#model').append('<option value="' + model + '">' + model + '</option>');
                            });
                        }
                    });
                } else {
                    $('#model').empty().append('<option value="">--Select Model--</option>');
                }
            });
        });
    </script>

    <!-- Script to dynamically toggle bank/ewallet input fields -->
    <script>
        $(document).ready(function () {
            $('#bankDetails').hide();
            $('#ewalletDetails').hide();

            $('#paymentMethod').change(function () {
                var selectedMethod = $(this).val();

                $('#bankDetails').hide();
                $('#ewalletDetails').hide();

                if (selectedMethod === 'bank_account') {
                    $('#bankDetails').show();
                } else if (selectedMethod === 'ewallet') {
                    $('#ewalletDetails').show();
                }
            });
        });
    </script>
    
    <!-- Modal for Terms and Conditions -->
<div id="termsModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Terms and Conditions</h2>
        <p>Accepted Models are entitled to claim RM5/unit (unless different amount stated) if criteria are met as below:</p>
        <p>1) Only Original cartridges are accepted, no refilled / counterfeit shall be accepted.</p>
        <p>2) Cartridge printhead and label stickers should be in good condition and label text should be readable.</p>
        <p>3) Please take a photo of the cartridges and submit it in the form.</p>
        <p>4) Kindly pack the cartridges in a plastic bag / if quantity is more than 5 units.</p>
        <p>Submitted cartridges will be inspected, and the amount will be credited accordingly to your preferred payment after processing.</p>
        <p>By submitting this form, you agree to the terms outlined above.</p>
    </div>
</div>

    <script>

          var modal = document.getElementById("termsModal");


          var termsLink = document.getElementById("termsLink");


          var span = document.getElementsByClassName("close")[0];


               termsLink.onclick = function() {
                  modal.style.display = "block";
              }


s             pan.onclick = function() {
                  modal.style.display = "none";
              }


              window.onclick = function(event) {
              if (event.target == modal) {
                modal.style.display = "none";
                  }
              }
     </script>

</body>
</html>
