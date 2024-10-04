<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

session_start();
include 'dbcon.php';

if (isset($_POST['btnLogin'])){
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
    
    try {
        $user = $auth->getUserByEmail("$user_email");
        
        try {
            $signInResult = $auth->signInWithEmailAndPassword($user_email, $user_password);
            $idTokenString = $signInResult->idToken();
            
            try {
                $verifiedIdToken = $auth->verifyIdToken($idTokenString);
                $uid = $verifiedIdToken->claims()->get('sub');
                
                $_SESSION['verified_user_id'] = $uid;
                $_SESSION['idTokenString'] = $idTokenString;
                
                $_SESSION['status'] = "Logged in successfully.";
                header("Location: home.php");
                die();
                
            } catch (InvalidToken $e) {
                echo 'The token is invalid: '.$e->getMessage();
                
            } catch (\InvalidArgumentException $e) {
                echo 'The token could not be parsed: '.$e->getMessage();
                        
            }
        } catch (Exception $e) {
            $_SESSION['status'] = "Wrong Password";
            header("Location: login.php");
            die();
            
        }
    } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
        $_SESSION['status'] = "Invalid Email";
        header("Location: login.php");
        die();
        
    }
} else {
    $_SESSION['status'] = "Not Allowed";
    header("Location: login.php");
    die();
}