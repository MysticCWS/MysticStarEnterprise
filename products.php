<?php 
session_start();
include 'dbcon.php';
include 'includes\header.php'; 
echo ' | Products';
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
    <div class="title">
        <h2>New Promotions</h5>
    </div>