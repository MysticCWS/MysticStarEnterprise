<?php 
include 'dbcon.php';
include 'includes\header.php'; 
echo ' | Sign Up';
include 'includes\header2.php';


//if($_SERVER['REQUEST_METHOD'] == "POST"){
//    //Something was posted
//    $user_email = $_POST['user_email'];
//    $user_password = $_POST['user_password'];
//    $user_name = $_POST['user_name'];
//    $user_contact = $_POST['user_contact'];
//
//    if(!empty($user_email) && !empty($user_password) && !empty($user_name) && !empty($user_contact)){
//        //Save into database
//        $userProperties = [
//            'user_email' => $user_email,
//            'user_password' => $user_password,
//            'user_name' => $user_name,
//            'user_contact' => $user_contact
//        ];
//
//        $createdUser = $auth -> createUser($userProperties);
//
//        header("Location: login.php");
//        die;
//    }else{
//        echo "Please enter all information.";
//    }
//}
?>

<div class="col-md-4 mx-auto px-4 py-5 border rounded bg-white">
    <form action="function_signup.php" id="signupForm" class="was-validated" method="POST">
        <div class="row g-2 mb-3 mx-2">
            <div class="col-md">
                <h3>Sign Up</h3>
            </div>
        </div>
        <div class="row g-2 my-3 mx-2">
            <div class="col-md">
                <div class="form-floating">
                    <input id="loginEmail" class="form-control" type="text" name="user_email" placeholder="Email" value="" required>
                    <label for="name">Email</label>
                </div>
            </div>
        </div>
        <div class="row g-2 my-3 mx-2">
            <div class="col-md">
                <div class="form-floating">
                    <input id="loginPassword" class="form-control" type="password" name="user_password" placeholder="Password" value="" required>
                    <label for="name">Password</label>
                </div>
            </div>
        </div>
        <div class="row g-2 my-3 mx-2">
            <div class="col-md">
                <div class="form-floating">
                    <input id="loginConfirmPassword" class="form-control" type="password" name="user_password" placeholder="Password" value="" required>
                    <label for="name">Confirm Password</label>
                </div>
            </div>
        </div>
        <div class="row g-2 my-3 mx-2">
            <div class="col-md">
                <div class="form-floating">
                    <input id="displayName" class="form-control" type="text" name="user_name" placeholder="Display Name" value="" required>
                    <label for="name">Display Name</label>
                </div>
            </div>
        </div>
        <div class="row g-2 my-3 mx-2">
            <div class="col-md">
                <div class="form-floating">
                    <input id="contact" class="form-control" type="text" name="user_contact" placeholder="Contact" value="" required>
                    <label for="name">Contact</label>
                </div>
            </div>
        </div>
        <div class="row g-2 my-3 mx-2">
            <div class="col-md">
                <div class="forgot-password mt-10">
                    <p>Already have an account? <a href="login.php">Login</a></p>
                </div>
            </div>
        </div>
        <div class="submit-login">
            <button id="btnCreate" class="btn btn-outline-success my-2 my-sm-0" type="submit">Create</button>
        </div>
    </form>
</div>

<?php
include 'includes\footer.php';
?>