<?php 

require __DIR__.'/../boot/boot.php';

use Hotel\Room;
use Hotel\Favorite;
use Hotel\User;
use Hotel\Review;
use Hotel\Booking;

// Initialize Room Service
$room = new Room();
$favorite = new Favorite();

// Check for room id
$roomId = $_REQUEST['room_id'];
if (empty($roomId)) {
  header('Location: index.php');
  die;
}

// Load room information
$roomInfo = $room->getRoomInfo($roomId);
if (empty($roomInfo)) {
  header('Location: index.php');
  die;
}
// print_r($roomInfo);die;
// Get current User Id
$userId = User::getCurrentUserId();

// Check if room is favourite for current user
$isFavorite = $favorite->isFavorite($roomId, $userId);

// Load all room reviews
$review = new Review();
$allReviews = $review->getReviewsByRoom($roomId);

// Get page parameters
$checkInDate = $_REQUEST['check_in_date'];
$checkOutDate = $_REQUEST['check_out_date'];
$alreadyBooked = empty($checkInDate) || empty($checkOutDate);
if (!$alreadyBooked) {
  $booking = new Booking();
  $alreadyBooked = $booking->isBooked($roomId, $checkInDate, $checkOutDate);

}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
    <meta name="robots" content="noindex,nofollow">
    <link href="./assets/css/jquery-ui.css" rel="stylesheet" type="text/css">
    <script src="./assets/js/jquery-3.5.1.min.js"></script>
    <script src="./assets/js/jquery-3.5.1.js"></script>
    <script src="./assets/js/jquery-ui.js"></script>
    <script src="./assets/pages/room.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY&callback=initMap"></script>
    <title>Room</title>
    <link rel="shortcut icon" href="./assets/images/favicon/favicon.ico" />
  </head>
  <body data-pagetype="room-page">
    <?php include '_header.php'; ?>

    <div class="container room-list-container">
      <section class="room-list box">
        <article class="room-page">
          <header class="room-info">
            <div class="room-name-location">
              <p><?php echo sprintf('%s - %s , %s',$roomInfo['name'], $roomInfo['city'], $roomInfo['area']); ?></p>
            </div>
            <div class="review-score">
              <p>Reviews:</p>
              <div class="rating-display">
                <?php 
                  $roomAvgReview = $roomInfo['avg_reviews'];
                  for ($i = 1; $i<=5; $i++) {
                    if ($roomAvgReview >= $i) {
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
            <div class="favorite-button" id="favorite">
              <form name="favoriteForm" method="post" action="actions/favorite.php" id="favoriteForm" class="favoriteForm">
                <input type="hidden" name="room_id" value="<?php echo $roomId; ?>">
                <input type="hidden" name="csrf" value="<?php echo User::getCsrf(); ?>">
                <input type="hidden" name="is_favorite" value="<?php echo $isFavorite ? '1' : '0'; ?>">
                <div class="heart">
                  <p>Add to favorites: </p>
                  <span id="fav-heart" class="fas fa-heart fheart <?php echo $isFavorite ? 'selected' : ''; ?>" ></span>
                </div>
              </form>
            </div>
            <div class="room-price">
              <p>Per Night: <?php echo $roomInfo['price'] . ' €' ?> </p>
            </div>
          </header>
          <!-- <aside class="media"> -->
          <div class="image">
            <img
              src="<?php echo ("./assets/images/rooms/" . $roomInfo['photo_url']); ?>"
              alt="<?php echo $roomInfo['name']?>"
            />
          </div>
          <footer class="room-footer">
            <div class=" room-footer-container">
              <ul>
                <li class="guest-count">
                  <div>
                    <i class="fas fa-user"></i>
                    <span><?php echo $roomInfo['count_of_guests'] ?></span>
                    <p>Count of Guests</p>
                  </div>
                </li>
                <li class="room-type">
                  <div>
                    <i class='fas fa-bed'></i>
                    <span><?php echo $roomInfo['type_id'] ?></span>
                    <p>Type of Room</p>
                  </div>
                </li>
                <li class="parking">
                  <div>
                    <i class='fas fa-parking'></i>
                    <span><?php echo $roomInfo['parking'] ?></span>
                    <p>Parking</p>
                  </div>  
                </li>
                <li class="wifi">
                  <div>
                    <i class="fas fa-wifi"></i>
                    <span><?php echo $roomInfo['wifi'] == 1 ? 'Yes' : 'No' ?></span>
                    <p>Wifi</p>
                  </div>
                </li>
                <li class="pet-friendly">
                  <div>
                    <i class='fas fa-dog'></i>
                    <span><?php echo $roomInfo['pet_friendly'] == 1 ? 'Yes' : 'No' ?></span>
                    <p>Pet friendly</p>
                  </div>
                </li>
              </ul>
            </div>
            <div class="clear"></div>
          </footer>
        </article>
      </section>
      <section class="room-description box">
        <h2>Room Description</h2>
        <p><?php echo $roomInfo['description_long']?></p>
      </section>
      <div class="booking padding-right" id="booking">
        <div id="booking-result">
          <div>
            <?php 
              if ($alreadyBooked) {
            ?>
              <span class="unavailable">Already Booked</span>
            <?php 
              } else {
            ?>
            <form method="post" action="actions/book.php" name="bookingForm" class="bookingForm" id="bookingForm">
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
        </div>
        <div class="check-available">
          <form method="post" action="actions/check.php" name="checkForm" class="checkForm" id="checkForm">
            <input type="hidden" name="room_id" value="<?php echo $roomId; ?>">
            <input type="hidden" name="csrf" value="<?php echo User::getCsrf(); ?>">
            <input type="text" name="check_in_date" id="checkInDate" placeholder=" Check-in Date" title="Choose a Check-in Date" class="text-center" value="<?php echo $checkInDate ?>"/>
            <input type="text" name="check_out_date" id="checkOutDate" placeholder=" Check-out Date" title="Choose a Check-out Date" class="text-center" value="<?php echo $checkOutDate ?>"/>
            <button id="checkBtn" type="submit" title="Check availability">Check availability</button>
          </form>
        </div>
      </div>
      <div id="map"></div>
      <hr>
      <section class="review-display box">
        <h2>Reviews</h2>
        <div id="room-reviews-container">
          <?php 
            foreach ($allReviews as $counter => $review) {
          ?>
            <div class="box">
              <div class="rating-container">
                <p> <?php echo sprintf('%d. %s',$counter+1, $review['user_name']); ?></p> 
                <!-- <p> <?php //echo sprintf(' %s', $review['user_name']); ?></p>  -->
                <div class="review-rating">
                  <?php 
                    for ($i = 1; $i<=5; $i++) {
                      if ($review['rate'] >= $i) {
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
              <span class="review-date">Created at: <?php echo $review['created_time']; ?></span>
              <span class="comment"><?php echo htmlentities($review['comment']); ?></span>
            </div>
          <?php 
            }
          ?>
        </dic>
      </section>
      <section class="review-add box">
        <h2>Add review</h2>
        <form name="reviewForm" id="reviewForm" class="reviewForm" method="POST" action="actions/review.php">
          <input type="hidden" name="room_id" value="<?php echo $roomId ?>" >
          <input type="hidden" name="csrf" value="<?php echo User::getCsrf(); ?>">
          <input type="hidden" name="check_in_date" value="<?php echo $checkInDate ?>" >
          <input type="hidden" name="check_out_date" value="<?php echo $checkOutDate ?>" >
          <div class="ratingReview">
            <div class="rate-area">
              <input type="radio" id="5-star" name="rate" value="5"/><label for="5-star" title="Give 5 stars">★</label>
              <input type="radio" id="4-star" name="rate" value="4"/><label for="4-star" title="Give 4 stars">★</label>
              <input type="radio" id="3-star" name="rate" value="3"/><label for="3-star" title="Give 3 stars">★</label>
              <input type="radio" id="2-star" name="rate" value="2"/><label for="2-star" title="Give 2 stars">★</label>
              <input type="radio" id="1-star" name="rate" value="1"/><label for="1-star" title="Give 1 star">★</label>
            </div>
            <div class="clear"></div>
            <div class= "c-validation rating_error"></div>
          </div>
          <img id="loadingImg" src="./assets/images/loading.gif" width="100px" height="auto" />
          <div class="form-group review message">
            <!-- <label for="formComments"><span style="color: red;">*</span>Review</label> -->
            <textarea id="formReview" name="comment" placeholder="Review" rows="3" cols="100" title="Write a review"></textarea>
            <div class="action center">
              <input name="submitBtn" id="submitReview" value="Submit" type="submit" title="Submit your review"/>
            </div>
          </div>
          
        </form>
      </section>
    </div>

    <?php include '_footer.php'; ?>

    <link href="./assets/css/styles.css" type="text/css" rel="stylesheet" />
    <link rel= "stylesheet" type="text/css" href="./assets/css/fontawesome.min.css" />
    <script src="./assets/js/script.js"></script>

    <script type="text/javascript">
      $("body").on('click', '.fheart', function () {
        $(this).toggleClass('selected');
        $(this).closest('form').trigger('submit');
      });
    </script>

    <script>
      // Google maps
      function initMap() {
      const centerLatLng = {
        lat: <?php echo $roomInfo['location_lat']?>,
        lng: <?php echo $roomInfo['location_long']?>
      };
      const mapProp= {
        center:new google.maps.LatLng(centerLatLng),
        zoom:15,
      };
      const map = new google.maps.Map(document.getElementById("map"),mapProp);

      const marker = new google.maps.Marker({
        position: centerLatLng,
        map: map,
        title: '<?php echo $roomInfo['name'] ?>'
      });
      }
    </script>
    
    <script>
      // Checkin Date jQuery Ui Calendar
      $(function() {
        $("#checkInDate").datepicker({
          changeMonth: true,
          changeYear: true,
          minDate: 0,
          dateFormat: "yy-mm-dd",
          // Force calendar to open below date input
          beforeShow : function(input,inst){
              var offset = $(input).offset();
              var height = $(input).height();
              window.setTimeout(function () {
                  // Check window width and place calendar accordingly
                  let w = $(window).width();
                  w < 480 ? offset.left = offset.left -75 : '';
                  $(inst.dpDiv).css({ top: (offset.top + height) + 'px', left:offset.left + 'px' })
              }, 1);
          }
        });
      });
      // Checkout Date jQuery Ui Calendar
      $(function() {
        $("#checkOutDate").datepicker({
          changeMonth: true,
          changeYear: true,
          minDate: 1,
          dateFormat: "yy-mm-dd",
          // Force calendar to open below date input
          beforeShow : function(input,inst){
              var offset = $(input).offset();
              var height = $(input).height();
              window.setTimeout(function () {
                  let w = $(window).width();
                  // Check window width and place calendar accordingly
                  w < 480 ? offset.left = offset.left -75 : '';
                  $(inst.dpDiv).css({ top: (offset.top + height) + 'px', left:offset.left + 'px' })
              }, 1);
          }
        });

        // Whenever the checkin date changes force the minimum checkout date to be that +1
        $("#checkInDate").change(function(){

          let minimumDate = new Date($("#checkInDate").val());

          minimumDate.setDate(minimumDate.getDate() + 1);

          $("#checkOutDate").datepicker("option", 'minDate', minimumDate );
        });
      });
    </script>

    <script>
      // Use the tooltip plugin from jQuery Ui
      $(function() {
        var tooltips = $( "[title]" ).tooltip({
          position: {
          my: "center bottom",
          at: "center top-10",
          collision: "none"
          }
        });
      });
    </script>
  </body>
</html>
