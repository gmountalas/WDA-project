<?php

// Boot application
require_once __DIR__.'/../../boot/boot.php';

use Hotel\User;

// Homepage redirect if not post request
if (($_SERVER['REQUEST_METHOD']) != 'POST') {
    header('Location: /public');

    return;
}

// Create new user
$user = new User();
$user->insert($_REQUEST['name'], $_REQUEST['email'], $_REQUEST['password']);

// Retrieve user
$userInfo =$user->getByEmail($_REQUEST['email']);

// Generate token
$token = $user->generateToken($userInfo['user_id']);

// Set cookie
setcookie('user_token', $token, time() + (30 * 24 * 60 * 60), '/');

// Return to home page
header('Location: ../index.php');