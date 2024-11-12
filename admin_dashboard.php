<?php
session_start();
include 'dbcon.php'; // Ensure you have your database connection
include 'includes\header.php'; 
echo ' | Admin Dashboard';
include 'includes\header2.php';
include 'includes\navbar_admin.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['verified_user_id']) || !isset($_SESSION['idTokenString'])) {
    $_SESSION['status'] = "Not Allowed.";
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Get the user ID from the session
$uid = $_SESSION['verified_user_id'];

// At this point, the user is logged in and is an admin
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
        <h2>Admin Dashboard</h2>
    </div>

    <div class="container mt-5 px-4 py-4 border rounded bg-white c-wrapper" id="admin_dashboard">
        <h5>Welcome to the admin page. You may navigate around to manage products, manage orders, and manage cartridge submissions here.</h5><br>
        <a href="admin_products.php" class="btn btn-outline-secondary btn-sm"><h3>Manage Products</h3></a><br><br>
        <a href="admin_orders.php" class="btn btn-outline-secondary btn-sm"><h3>Manage Orders</h3></a><br><br>
        <a href="admin_cartridges.php" class="btn btn-outline-secondary btn-sm"><h3>Manage Cartridge Submissions</h3></a><br>
    </div>
</div>
<br>
<?php
include 'includes\footer.php';
?>
