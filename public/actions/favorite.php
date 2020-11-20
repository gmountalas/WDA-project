<?php

// Boot application
require_once __DIR__.'/../../boot/boot.php';

use Hotel\User;
use Hotel\Favorite;

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

// Check for room id
$roomId = $_REQUEST['room_id'];
if(empty($roomId)) {
    header('Location: /public');

    return;
}

// Verify csrf
$csrf = $_REQUEST['csrf'];
if(empty($csrf) || !User::verifyCsrf($csrf)) {
    header('Location: /public');

    return;
}

// Set room to favorites
$favorite = new Favorite();

// Add or remove room from favorites
$isFavorite = $_REQUEST['is_favorite'];
// var_dump($isFavorite);die;
if(!$isFavorite) {
    // var_dump('is favourite');die;
    $favorite->addFavorite($roomId, User::getCurrentUserId());
} else {
    $favorite->removeFavorite($roomId, User::getCurrentUserId());
}

// Return to room page
header(sprintf('Location: ../room.php?room_id=%s', $roomId));


