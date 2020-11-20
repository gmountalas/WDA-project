<?php

// Boot application
require_once __DIR__.'/../../boot/boot.php';

use Hotel\User;
use Hotel\Booking;

// Homepage redirect if not post request
if (strtolower($_SERVER['REQUEST_METHOD']) != 'post') {
    echo "This is a post script";
    die;
}

// If no use is logged in return to homepage
if (empty(User::getCurrentUserId())) {
    echo "No current user for this operation";
    die;
}

// Check for room id
$roomId = $_REQUEST['room_id'];
if(empty($roomId)) {
    echo "No room is given for this operation";
    die;
}

// Verify csrf
$csrf = $_REQUEST['csrf'];
if(empty($csrf) || !User::verifyCsrf($csrf)) {
    echo 'This is an invalid request';
    return;
}

// Get page parameters
$checkInDate = $_REQUEST['check_in_date'];
$checkOutDate = $_REQUEST['check_out_date'];

$alreadyBooked = empty($checkInDate) || empty($checkOutDate);
if (!$alreadyBooked) {
  $booking = new Booking();
  $alreadyBooked = $booking->isBooked($roomId, $checkInDate, $checkOutDate);

}

?>

<div>
    <?php 
        if ($alreadyBooked) {
    ?>
        <span class="unavailable">Already Booked</span>
    <?php 
        } else {
    ?>
        <form method="post" action="actions/book.php" name="bookingForm" class="bookingForm">
            <input type="hidden" name="room_id" value="<?php echo $roomId ?>" >
            <input type="hidden" name="csrf" value="<?php echo User::getCsrf(); ?>">
            <input type="hidden" name="check_in_date" value="<?php echo $checkInDate ?>" >
            <input type="hidden" name="check_out_date" value="<?php echo $checkOutDate ?>" >
            <button class="available" type="submit">Book Now</button>
        </form>
    <?php 
        }
    ?>
</div>

