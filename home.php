<?php 
session_start();
include 'dbcon.php';
include 'includes\header.php'; 
echo ' | Home';
include 'includes\header2.php';
include 'includes\navbar.php';
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
        <h5>Our Products</h5>
    </div>

</div>

<?php
include 'includes\footer.php';
?>