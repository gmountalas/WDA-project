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

// Redirect to same page with updated Check-in and Check-out Dates
$checkInDate = $_REQUEST['check_in_date'];
$checkOutDate = $_REQUEST['check_out_date'];

// Return to room page
header(sprintf('Location: ../room.php?room_id=%s&check_in_date=%s&check_out_date=%s', $roomId, $checkInDate, $checkOutDate));