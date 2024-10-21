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

if (isset($_FILES['myfile']['name'])){
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
}
?>

<div class="content">
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
    </div>
</div>
    
<?php
include 'includes\footer.php';
?>
