<?php

// Boot application
require_once __DIR__.'/../../boot/boot.php';

use Hotel\User;

// Homepage redirect if not post request
if (strtolower($_SERVER['REQUEST_METHOD']) != 'post') {
    header('Location: /public');
    
    return;
}

// If no user is logged in return to homepage
if (empty(User::getCurrentUserId())) {
    header('Location: /public');
   
    return;
}

// Verify csrf
$csrf = $_REQUEST['csrf'];
if(empty($csrf) || !User::verifyCsrf($csrf)) {
    header('Location: /public');

    return;
}

// Retrieve token
$token = $_COOKIE['user_token'];

// Delete cookie
setcookie('user_token', "", time() - 3600, '/');

// Return to login page
header('Location: ../login.php');