<?php 
session_start();
include 'dbcon.php';
include 'includes\header.php'; 
echo ' | Login';
include 'includes\header2.php';
?>
<div class="col-md-4 mx-auto px-4 py-5 border rounded bg-white">
<!--Show Status-->
    <?php
        if(isset($_SESSION['status'])){
            echo "<h5 class='alert alert-success>".$_SESSION['status']."</h5>";
            unset($_SESSION['status']);
        }
    ?>
    
    <form action="function_login.php" id="loginForm" class="was-validated" method="POST">
        <div class="row g-2 my-3 mx-2">
            <div class="col-md">
                <h3>Login</h3>
            </div>
        </div>
        <div class="row g-2 my-3 mx-2">
            <div class="col-md">
                <div class="form-floating">
                    <input id="loginEmail" class="form-control" type="text" name="user_email" placeholder="Email" value="" required="">
                    <label for="name">Email</label>
                </div>
            </div>
        </div>
        <div class="row g-2 my-3 mx-2">
            <div class="col-md">
                <div class="form-floating">
                    <input id="loginPassword" class="form-control" type="password" name="user_password" placeholder="Password" value="" required="">
                    <label for="name">Password</label>
                </div>
            </div>
        </div>
        <div class="row g-2 my-3 mx-2">
            <div class="col-md">
                <div class="forgot-password mt-10">
                    <p>Don't have an account yet? <a href="signup.php">Sign Up</a></p>
                </div>
            </div>
        </div>
        <div class="submit-login">
            <button id="btnLogin" class="btn btn-outline-success my-2 my-sm-0" name="btnLogin" type="submit">Login</button>
        </div>
    </form>
</div>
<?php
include 'includes\footer.php';
?>