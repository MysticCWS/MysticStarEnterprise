<?php
include 'dbcon.php';

$userID = $_GET['userID'];
$submissionID = $_GET['submissionID'];

if ($userID && $submissionID) {
    $submissionRef = $database->getReference("cartridge_submissions/$userID/$submissionID");

    $submissionRef->update(['status' => 'Payment Simulated']);

    echo "Payment simulated successfully.";
} else {
    echo "Error: Invalid user ID or submission ID.";
}
?>
