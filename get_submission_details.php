<?php
session_start();
include 'dbcon.php';

$userID = $_GET['userID'];
$submissionID = $_GET['submissionID'];


$submissionRef = $database->getReference("cartridge_submissions/$userID/$submissionID");
$submission = $submissionRef->getValue();

echo json_encode($submission);
?>
