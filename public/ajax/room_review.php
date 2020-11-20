<?php

// Boot application
require_once __DIR__.'/../../boot/boot.php';

use Hotel\User;
use Hotel\Review;

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
    echo "This is an invalid request";
    return;
}

// Add review
$review = new Review();
// if (!empty($_REQUEST['rate'])) {
    $review->insert($roomId, User::getCurrentUserId(), $_REQUEST['rate'], $_REQUEST['comment']);
// }

// Get all reviews
$roomReviews = $review->getReviewsByRoom($roomId);
$counter = count($roomReviews);

// Load user
$user = new User();
$userInfo = $user->getByUserID(User::getCurrentUserId());

?>

<div class="box">
    <div class="rating-container">
        <p> <?php echo sprintf('%d. %s',$counter, $userInfo['name']); ?></p> 
        <!-- <p> <?php //echo sprintf('%s', $userInfo['name']); ?></p>  -->
        <div class="review-rating">
        <?php 
            for ($i = 1; $i<=5; $i++) {
            if ($_REQUEST['rate'] >= $i) {
                ?>
                <span class="fa fa-star checked"></span>
                <?php
            } else {
                ?>
                <span class="fa fa-star "></span>
                <?php
            }
            }
        ?>
        </div>
    </div>
    <span class="review-date">Created at: <?php echo date('Y-m-d H:i:s'); ?></span>
    <span class="comment"><?php echo $_REQUEST['comment']; ?></span>
</div>


