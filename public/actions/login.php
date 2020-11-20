<?php

// Boot application
require_once __DIR__.'/../../boot/boot.php';

use Hotel\User;

// Homepage redirect if not post request
if (($_SERVER['REQUEST_METHOD']) != 'POST') {
    header('Location: /public');

    return;
}

// If there is an already logged in user return to homepage
if (!empty(User::getCurrentUserId())) {
    header('Location: /public');

    return;
}

// Verify user
$user = new User();
try {
    if (!$user->verify($_REQUEST['email'], $_REQUEST['password'])) {
        header('Location: ../login.php?error=Could not verify user');

        return;
    }
} catch (InvalidArgumentException $ex) {
    header('Location: ../login.php?error=No user exists with that email');

    return;
}

// Retrieve user
$userInfo =$user->getByEmail($_REQUEST['email']);

// Generate token
$token = $user->generateToken($userInfo['user_id']);

// Set cookie
setcookie('user_token', $token, time() + (30 * 24 * 60 * 60), '/');

// Return to home page
header('Location: ../index.php');



