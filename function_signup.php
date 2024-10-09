<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

session_start();
include 'dbcon.php';

if (isset($_POST['btnCreate'])){
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
    $user_name = $_POST['user_name'];
    $user_contact = $_POST['user_contact'];
    
    $userProperties = [
        'email' => $user_email,
        'emailVerified' => false,
        'password' => $user_password,
        'displayName' => $user_name,
        'photoUrl'=> null,
        'phoneNumber' => '+6'.$user_contact
    ];
    
    $createdUser = $auth -> createUser($userProperties);
    
    if($createdUser){
        $_SESSION['status'] = "Sign Up Successful.";
        header("Location: login.php");
        die();
    } else {
        $_SESSION['status'] = "Error Signing Up. Please try again.";
        header("Location: signup.php");
        die();
    }
}