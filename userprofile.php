<?php
session_start();
include 'dbcon.php';
include 'includes\header.php';
echo ' | User Profile';
include 'includes\header2.php';
include 'includes\navbar.php';

$userurlprefix = "https://firebasestorage.googleapis.com/v0/b/mysticstarenterprise.appspot.com/o/profile%2F";
$userurlsuffix = "?alt=media";
$userurl = $userurlprefix.$uid.".png".$userurlsuffix;

if(isset($_SESSION['verified_user_id'])){
    $uid = $_SESSION['verified_user_id'];
    try {
        $user = $auth->getUser($uid);
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
        echo $e->getMessage();
    }
}

if (isset($_FILES['myfile']['name']) && $_FILES['myfile']['name'] !== ""){
    $defaultBucket->upload(
        file_get_contents($_FILES['myfile']['tmp_name']),
        [
        'name' =>"profile/".$uid.".png"
        ]
    );
}

if (isset($_POST['btnSave'])){
    $user_name = $_POST['user_name'];
    $user_contact = $_POST['user_contact'];
    
    // If no new photo was uploaded, use the existing photo URL
    if (!isset($_FILES['myfile']['name']) || $_FILES['myfile']['name'] === "") {
        $userProperties = [
            'displayName' => $user_name,
            'photoUrl' => $userurl, // Keep the old photo URL if no new photo is uploaded
            'phoneNumber' => $user_contact
        ];
    }
    if(URLcheck($userurl)){
        $userProperties = [
            'displayName' => $user_name,
            'photoUrl'=> $userurl,
            'phoneNumber' => $user_contact
        ];
    }
    else {
        $userProperties = [
            'displayName' => $user_name,
            'photoUrl'=> '',
            'phoneNumber' => $user_contact
        ];
    }
    
    $updatedUser = $auth -> updateUser($uid, $userProperties);
    
    if($updatedUser){
        $_SESSION['status'] = "Profile Updated Successfully.";
        header("Location: userprofile.php");
        die();
    }
}

// Remove Profile Photo
if (isset($_POST['btnRemovePhoto'])) {
    // Delete the photo from Firebase Storage
    $imagePath = "profile/" . $uid . ".png"; // Path to the user's profile photo
    $deleteObject = $defaultBucket->object($imagePath);
    $deleteObject->delete(); // Remove photo from Firebase Storage

    // Update the user's photoUrl to null in Firebase Authentication
    $userProperties = [
        'photoUrl' => ''
    ];
    $updatedUser = $auth->updateUser($uid, $userProperties);

    // Provide feedback
    $_SESSION['status'] = "Profile photo removed successfully.";
    header("Location: profile.php"); // Redirect to the profile page
    die();
}
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
        <h2>Profile Information</h2>
    </div>
    
    <div class="profile-container">
        <form id="profileForm" class="was-validated" method="POST" enctype="multipart/form-data">
            <div class="profile-header">
                <div class="profile-picture" onclick="document.getElementById('file-input').click();">
                    <img src="<?php echo $userurl?>" alt="Profile Picture" id="profile-img">
                    <div class="edit-photo">Edit Photo</div>
                </div>
                <!-- Hidden file input to upload image -->
                <input type="file" id="file-input" accept="image/png" name="myfile" onchange="previewImage(event)" hidden="true">
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
                <button id="btnSave" class="btnSave" name="btnSave" type="submit">Save Changes</button>
            </div>
        </form>
        
        <!-- Remove Profile Photo Button -->
        <button class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#removePhotoModal">Remove Profile Photo</button>
    </div>
</div>

<!-- Modal for Confirming Photo Deletion -->
<div class="modal fade" id="removePhotoModal" tabindex="-1" aria-labelledby="removePhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removePhotoModalLabel">Confirm Photo Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove your profile photo? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST">
                    <button type="submit" name="btnRemovePhoto" class="btn btn-danger">Remove Photo</button>
                </form>
            </div>
        </div>
    </div>
</div>
<br>
    
<?php
include 'includes\footer.php';
?>
