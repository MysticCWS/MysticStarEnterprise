<?php
session_start();
include 'dbcon.php';
include 'includes\header.php';
echo ' | User Profile';
include 'includes\header2.php';
include 'includes\navbar.php';

if(isset($_SESSION['verified_user_id'])){
    $uid = $_SESSION['verified_user_id'];
    try {
        $user = $auth->getUser($uid);
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
        echo $e->getMessage();
    }
}
?>

<div class="content">
    <div class="title">
        <h2>Profile Information</h2>
    </div>
    
    <div class="profile-container">
        <form id="profileForm" class="was-validated" method="POST">
            <div class="profile-header">
                <div class="profile-picture" onclick="document.getElementById('file-input').click();">
                    <img src="default-profile.jpg" alt="Profile Picture" id="profile-img">
                    <div class="edit-photo">Edit Photo</div>
                </div>
                <!-- Hidden file input to upload image -->
                <input type="file" id="file-input" style="display: none;" accept="image/*" onchange="previewImage(event)">
            </div>
            
            <div class="row g-2 my-3 mx-2">
                <div class="col-md">
                    <div class="form-floating">
                        <input id="loginEmail" class="form-control" type="text" name="user_email" placeholder="Email" value="<?php echo $user->email; ?>" required disabled="">
                        <label for="name">Email</label>
                    </div>
                </div>
            </div>
            <div class="row g-2 my-3 mx-2">
                <div class="col-md">
                    <div class="form-floating">
                        <input id="displayName" class="form-control" type="text" name="user_name" placeholder="Display Name" value="<?php echo $user->displayName; ?>" required>
                        <label for="name">Display Name</label>
                    </div>
                </div>
            </div>
            <div class="row g-2 my-3 mx-2">
                <div class="col-md">
                    <div class="form-floating">
                        <input id="contact" class="form-control" type="text" name="user_contact" placeholder="Contact" value="<?php echo $user->phoneNumber; ?>" required>
                        <label for="name">Contact</label>
                    </div>
                </div>
            </div>
            
            <div class="submit-login">
                <button id="btnSave" class="btnSave" name="btnSubmit" type="submit">Save Changes</button>
            </div>
        </form>
    </div>
</div>
    
<?php
include 'includes\footer.php';
?>
