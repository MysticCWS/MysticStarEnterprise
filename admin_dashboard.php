<?php
session_start();
include 'dbcon.php'; // Ensure you have your database connection

// Check if the user is logged in and is an admin
if (!isset($_SESSION['verified_user_id']) || !isset($_SESSION['idTokenString'])) {
    $_SESSION['status'] = "You must log in first.";
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Get the user ID from the session
$uid = $_SESSION['verified_user_id'];

// At this point, the user is logged in and is an admin
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="path/to/your/styles.css"> <!-- Include your CSS -->
</head>
<body>
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

        <a href="logout.php">Logout</a> <!-- Link to logout -->
    </div>
</body>
</
