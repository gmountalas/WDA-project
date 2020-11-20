<?php

// Boot application
require_once __DIR__.'/../../boot/boot.php';

use Hotel\User;
use Hotel\Booking;

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

// Create Booking
$booking = new Booking();
$checkInDate = $_REQUEST['check_in_date'];
$checkOutDate = $_REQUEST['check_out_date'];

if(!empty($checkInDate) && !empty($checkOutDate) && !($booking->isBooked($roomId, $checkInDate, $checkOutDate))) {
    $booking->insert($roomId, User::getCurrentUserId(), $checkInDate, $checkOutDate);
}

// Return to room page
header(sprintf('Location: ../room.php?room_id=%s&check_in_date=%s&check_out_date=%s', $roomId, $checkInDate, $checkOutDate));