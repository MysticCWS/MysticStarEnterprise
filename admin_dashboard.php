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
        <h2>Admin Dashboard</h5>
    </div>

    <div class="container">
        <h1>Welcome to the Admin Dashboard</h1>
        <p>You are logged in as an admin.</p>

        <h2>User Management</h2>
        <!-- Add your user management features here -->

        <h2>Other Admin Features</h2>
        <!-- Add other features specific to admin here -->

        <div class="status-message">
            <?php
            // Display any status messages
            if (isset($_SESSION['status'])) {
                echo "<p class='alert alert-info'>" . $_SESSION['status'] . "</p>";
                unset($_SESSION['status']); // Clear status message
            }
            ?>
        </div>

        <a href="function_logout.php">Logout</a> <!-- Link to logout -->
    </div>
</div>

<?php
include 'includes\footer.php';
?>
