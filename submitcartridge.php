<?php
session_start();
include 'dbcon.php';
include 'includes/header.php'; 
echo ' | Cartridge Recycling Program';
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

// Fetch models based on selected brand if submitted
$selectedBrand = $_POST['brand'] ?? '';
$models = [];
$errorMessage = ''; // Initialize error message variable
if ($selectedBrand) {
    $models = $database->getReference("printerlist/$selectedBrand")->getValue();
}

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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

                <!-- Model Selection -->
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
                    <input type="text" id="bankAccount" name="bank_account" class="input-field" maxlength="17" 
                           pattern="^[0-9]{1,17}$" title="Bank account number can only contain up to 17 digits" 
                           placeholder="e.g., 12345678901234567">

                    <!-- Bank Selection Dropdown -->
                    <label for="bankSelect">Select Bank:</label>
                    <select id="bankSelect" name="bank" class="input-field">
                        <option value="">--Select Bank--</option>
                        <option value="public_bank">Public Bank</option>
                        <option value="maybank">Maybank</option>
                        <option value="cimb_bank">CIMB Bank</option>
                        <option value="uob_bank">UOB Bank</option>
                    </select>
                </div>

                <!-- E-Wallet Details Section (Initially Hidden) -->
                <div class="form-group" id="ewalletDetails" style="display: none;">
                    <label for="phone">Phone Number for E-wallet:</label>
                    <input type="tel" id="phone" name="phone" class="input-field" maxlength="10" 
                           pattern="^0[0-9]{9}$" title="Phone number must start with 0 and have 10 digits" 
                           placeholder="e.g., 0123456789">
                </div>

                <!-- Cartridge Image Upload -->
                <div class="form-group">
                    <label for="cartridgeImage">Upload Cartridge Image:</label>
                    <input type="file" id="cartridgeImage" name="cartridge_image" class="input-field" accept="image/*" required>
                </div>

                <!-- Agree to Terms Checkbox -->
                <div class="form-group">
                    <input type="checkbox" id="termsCheckbox" required>
                    <label for="termsCheckbox">I agree to the <a href="#termsLink" id="termsLink" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>.</label>
                </div>
                
                <!-- Terms Modal -->
                <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="termsModalLabel">Terms and Condition</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
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

    <!-- JavaScript for Additional Validation -->
    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            const phone = document.getElementById('phone');
            if (phone && phone.value && !/^0[0-9]{9}$/.test(phone.value)) {
                alert('Phone number must start with 0 and contain exactly 10 digits.');
                event.preventDefault();
                return;
            }

            const bankAccount = document.getElementById('bankAccount');
            if (bankAccount && bankAccount.value && !/^[0-9]{1,17}$/.test(bankAccount.value)) {
                alert('Bank account number must only contain digits and be up to 17 characters.');
                event.preventDefault();
                return;
            }
        });
    </script>
    <br>
</body>
</html>
<?php
include 'includes\footer.php';
?>
