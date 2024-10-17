<?php
session_start();
include 'dbcon.php';
include 'includes\header.php'; 
include 'includes\header2.php';
include 'includes\navbar.php'; 

// Check if the user is authenticated
if (isset($_SESSION['verified_user_id'])) {
    $uid = $_SESSION['verified_user_id'];

    try {
        // Retrieve authenticated user data from Firebase
        $user = $auth->getUser($uid);  // Firebase's Auth class
    } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
        echo $e->getMessage();
        exit();
    }
} else {
    echo "Please log in to submit a cartridge.";
    exit();
}
?>

<div class="content">
    <h2 class="title">Cartridge Recycling Program</h2>
    <form action="submit_cartridge.php" method="POST" class="cartridge-form" id="cartridgeForm">
        <input type="hidden" name="user_id" value="<?php echo $uid; ?>">

        <div class="form-group">
            <label for="name">Cartridge Name:</label>
            <input type="text" id="name" name="name" class="input-field" required>
        </div>

        <div class="form-group">
            <label for="type">Cartridge Type:</label>
            <input type="text" id="type" name="type" class="input-field" required>
        </div>

        <div class="form-group">
            <label for="model">Model:</label>
            <input type="text" id="model" name="model" class="input-field" required>
        </div>

        <div class="form-group">
            <label for="manufacturer">Manufacturer:</label>
            <input type="text" id="manufacturer" name="manufacturer" class="input-field" required>
        </div>

        <div class="form-group">
            <label for="notes">Additional Notes:</label>
            <textarea id="notes" name="notes" class="input-field textarea-field"></textarea>
        </div>

        <div class="form-group">
            <input type="checkbox" id="termsCheckbox" required>
            <label for="termsCheckbox">I agree to the <a href="#" id="termsLink">Terms and Conditions</a>.</label>
        </div>

        <button type="submit" class="btnSave" id="submitBtn" disabled>Submit Cartridge</button>
    </form>
</div>


<div id="termsModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h2>Terms and Conditions</h2>
        <p>Accepted Models are entitled to claim RM5/unit (unless different amount stated) if criteria are met as below:
        <p>1) Only Original cartridges are accepted , no refilled / counterfeit shall be accepted.
        <p>2) Cartridge printhead and label stickers should be in good condition and label text should be readable.</p>
        <p>3) Please take a photo of the cartridges and submit it in the form.</p>
        <p>4) Kindly pack the cartridges in a plastic bag / if quantity is more than 5 units.</p>
        
        <p> Submitted cartridges will be inspected and the amount will be credited accordingly to your preferred payment after processing.</p>
        
        <p>By submitting this form, you agree to the terms outlined above.</p>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const termsLink = document.getElementById('termsLink');
        const termsModal = document.getElementById('termsModal');
        const closeButton = document.querySelector('.close-button');
        const submitBtn = document.getElementById('submitBtn');
        const termsCheckbox = document.getElementById('termsCheckbox');

     
        termsLink.addEventListener('click', function (e) {
            e.preventDefault();
            termsModal.style.display = 'block';
        });


        closeButton.addEventListener('click', function () {
            termsModal.style.display = 'none';
        });

     
        window.addEventListener('click', function (event) {
            if (event.target == termsModal) {
                termsModal.style.display = 'none';
            }
        });

        
        termsCheckbox.addEventListener('change', function () {
            submitBtn.disabled = !this.checked;
        });
    });
</script>
