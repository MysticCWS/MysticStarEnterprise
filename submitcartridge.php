<?php
session_start();
include 'dbcon.php'; 


include 'firebase_init.php';  // Ensure your Firebase PHP SDK is set up correctly

// Check if the user is authenticated
if (isset($_SESSION['verified_user_id'])) {
    $uid = $_SESSION['verified_user_id'];

    try {
        // Retrieve authenticated user data from Firebase
        $user = $auth->getUser($uid);  // Firebase's Auth class
    } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
        echo $e->getMessage();
        exit();
    }
} else {
    echo "Please log in to submit a cartridge.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartridge Submission</title>
</head>
<body>
    <h2>Submit a Cartridge</h2>
    <form action="submit_cartridge.php" method="POST">
        <input type="hidden" name="user_id" value="<?php echo $uid; ?>">

        <label for="name">Cartridge Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="type">Cartridge Type:</label>
        <input type="text" id="type" name="type" required><br><br>

        <label for="model">Model:</label>
        <input type="text" id="model" name="model" required><br><br>

        <label for="manufacturer">Manufacturer:</label>
        <input type="text" id="manufacturer" name="manufacturer" required><br><br>

        <label for="notes">Additional Notes:</label><br>
        <textarea id="notes" name="notes"></textarea><br><br>

        <input type="submit" value="Submit Cartridge">
    </form>
</body>
</html>
