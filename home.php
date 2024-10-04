<?php 
include 'dbcon.php';
include 'includes\header.php'; 
echo ' | Home';
include 'includes\header2.php';
?>

//Show Status
    <?php
        if(isset($_SESSION['status'])){
            echo "<h5 class='alert alert-success>".$_SESSION['status']."</h5>";
            unset($_SESSION['status']);
        }
    ?>

<?php
include 'includes\footer.php';
?>