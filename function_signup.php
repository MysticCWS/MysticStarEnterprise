<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

session_start();
include 'dbcon.php';

if (isset($_POST['btnCreate'])){
    $user_email = S_POST['user_email'];
    $user_password = S_POST['user_password'];
    $user_name = S_POST['user_name'];
    $user_contact = S_POST['user_contact'];
    
    $userProperties = [
        'user_email' => $user_email,
        'user_password' => $user_password,
        'user_name' => $user_name,
        'user_contact' => $user_contact
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