<?php 

require __DIR__.'/../boot/boot.php';

use Hotel\Booking ;
use Hotel\Favorite;
use Hotel\User;
use Hotel\Review;


// Check for logged in user
$userId = User::getCurrentUserId();
if(empty($userId)) {
  header('Locaton: index.php');
}

// Get all favorites
$favorite = new Favorite();
$userFavorites = $favorite->getListByUser($userId);

// Get all reviews
$review = new Review();
$userReviews = $review->getListByUser($userId);

// Get user bookings
$booking = new Booking();
$userBookings = $booking->getListByUser($userId);


?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
    <link href="./assets/css/jquery-ui.css" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" href="./assets/images/favicon/favicon.ico" />
    <script src="./assets/js/jquery-3.5.1.min.js"></script>
    <script src="./assets/js/jquery-3.5.1.js"></script>
    <script src="./assets/js/jquery-ui.js"></script>
    <script src="./assets/js/jquery.ui.touch-punch.min.js"></script>
    <title>Profile</title>
  </head>
  <body data-pagetype="profile-page">
    <?php include '_header.php'; ?>
    <div class="container list-container">
      <aside class= "profile-sidebar">
        <ul class="profile-menu" >
          <li>
              <h2>Favorites</h2>
              <?php 
                if (count($userFavorites) > 0) {
              ?>
                <ol class="favourites-list">
                  <?php 
                    foreach ($userFavorites as $favorite) {
                  ?>
                    <li>
                      <a class="fav-link" href="room.php?room_id=<?php echo $favorite['room_id']; ?>"><span class="name-fav"><?php echo $favorite['name']; ?></span></a>
                    </li>
                  <?php 
                    }
                  ?>
                </ol>
              <?php 
                } else {
              ?>
                <div id="no-fav">
                  <span class="text-center">You don't have any favorite Hotel yet!</span>
                </div>
              <?php 
                }
              ?>
          </li>
          <li>
            <h2>Reviews</h2>
            <?php 
              if (count($userReviews) > 0) {
            ?>
              <ol class="review-list">
                <?php 
                  foreach ($userReviews as $review) {
                ?>
                  <li>
                  <a class="fav-link" href="room.php?room_id=<?php echo $review['room_id']; ?>"><span class="name-rev"><?php echo $review['name']; ?></span></a>
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
                    <div class="clear"></div>
                  </li>
                  <?php 
                    }
                  ?>
              </ol>
            <?php 
                } else {
              ?>
              <div id="no-rev">
                <span class="text-center">You don't have any reviews yet!</span>
              </div>
            <?php 
              }
            ?>
          </li>
        </ul>
      </aside> 
      <section class="hotel-list box">
        <header class="page-title">
            <h2>My bookings</h2>
        </header>
        <?php 
          if (count($userBookings) > 0) {
        ?>
        <section>
          <?php
            foreach ($userBookings as $booking) {
          ?>
            <article class="hotel">
              <aside class="media">
                  <img src="assets/images/rooms/<?php echo $booking['photo_url'] ?>" alt="<?php echo $booking['name']; ?>" width="100%" height="auto">
              </aside>
              <main >
                <div class="info">
                  <h1><?php echo $booking['name']; ?></h1>
                  <h2><?php echo sprintf('%s , %s', $booking['city'], $booking['area']); ?></h2>
                  <p> <?php echo $booking['description_short'] ?></p>
                </div>  
                <div class="text-right roomBtn">
                    <button title="Go to Room Page"><a href="room.php?room_id=<?php echo $booking['room_id']; ?>">Go to Room Page </a></button>
                </div>
              </main>
              <div class="clear"></div>
              <footer>
                <div class="list-footer-container">
                  <ul>
                    <li class="total-cost">
                      <p>Total Cost: <?php echo $booking['total_price']; ?> € </p>
                    </li>
                    <li class="checkin-date">
                      <p>Check-in Date: <?php echo $booking['check_in_date']; ?></p>
                    </li>
                    <li class="checkout-date">
                      <p>Check-out Date: <?php echo $booking['check_out_date']; ?></p>
                    </li>
                    <li class="footer-room-type">
                      <p>Type of Room: <?php echo $booking['room_type']; ?></p>
                    </li>
                  </ul>
                </div>
                <div class="clear"></div>
              </footer>
              <hr>
            </article>
            <?php 
              } 
            ?>
          </section>
        <?php 
          } else {
        ?>
          <div>
            <h3 class="text-center">You don't have any bookings</h3>
          </div>
        <?php 
          }
        ?>
      </section>
    </div>

    <?php include '_footer.php'; ?>

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

    <script>
      // Inject the logout button functionality in the profile button on profile page
      let formOpen = '<form style="position: relative;" name="logoutForm" method="POST" action="actions/logout.php" id="logout">';
      let inputCsrf = '<input type="hidden" name="csrf" value="<?php echo User::getCsrf(); ?>">';
      let logoutBtn = '<button id="logoutBtn"style="align-self: flex-start; position: absolute; top: 5px; left: 0px;" type="submit"><i class="fas fa-sign-out-alt">Logout</i></button>';
      let formClose = '</form>';
      let test = formOpen + inputCsrf + logoutBtn + formClose;

      $('#profile-button').after(test);

      // Add css position: relative to page header
      $('body > header ul').addClass('dropdown');
      
      // Hide by default the logout form/button
      $('form[name="logoutForm"]').hide();

      // Track where the user clicks on the page
      $(document).on("click", function(e) {
        let target = $( e.target );

        // If the user clicks on the profile button or any of its associated elements then stop the 
        // <a> link from firing and show the logout button
        if ( target.is("a#profile-button") || target.is( "a#profile-button > span" ) || target.is("a#profile-button > .fa-user") ) {
          
          e.preventDefault();
          
          $('form[name="logoutForm"]').show();

        // If the user clicks anywhere else on the document simply hide the logout button
        } else {
          $('form[name="logoutForm"]').hide();
        }
      });

      // Media querry for screens with width < 376px 
      // Remove the "Logout" word from logout button and leave only icon
      $(function(){
        function logoutIconOnly(x) {
          if (x.matches) { // If media query matches
            
            $('#logoutBtn > i').text("");
            $('#logoutBtn').css({"border-radius": "50%", "left": "0px"});
          } else {
            // Reinsert the "Logout" word for screens with width > 376px
            $('#logoutBtn > i').text("Logout");
            $('#logoutBtn').css("border-radius", "4px");
          }
        }

        let windowWidth = window.matchMedia("(max-width: 376px)");
        logoutIconOnly(windowWidth); // Call listener function at run time
        windowWidth.addListener(logoutIconOnly); // Attach listener function on state changes
      }); 
    </script>

    <link href="./assets/css/styles.css" type="text/css" rel="stylesheet" />
    <link rel= "stylesheet" type="text/css" href="./assets/css/fontawesome.min.css" />
    <script src="./assets/js/script.js"></script>
  </body>
</html>
