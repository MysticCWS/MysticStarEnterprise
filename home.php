<?php 
session_start();
include 'dbcon.php';
include 'includes\header.php'; 
echo ' | Home';
include 'includes\header2.php';
include 'includes\navbar.php';

//Fetch carousel from database
$ref_table = 'carousel';
$carousel_images = $database->getReference($ref_table)->getValue();

//$carouselurlprefix = "https://firebasestorage.googleapis.com/v0/b/mysticstarenterprise.appspot.com/o/carousel%2F";
//$carouselurlsuffix = "?alt=media";
//$carouselurl = $carouselurlprefix.$carouselnum.$carouselurlsuffix;
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
