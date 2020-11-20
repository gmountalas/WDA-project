<?php 

require __DIR__.'/../boot/boot.php';

use Hotel\Room;
use Hotel\RoomType;

// Initialize Room Service
$room = new Room();

// Get page parameters
$selectedCity = $_REQUEST['city'];
$selectedTypeId = $_REQUEST['room_type'];
$checkInDate = $_REQUEST['check_in_date'];
$checkOutDate = $_REQUEST['check_out_date'];
$selectedGuestCount = $_REQUEST['count_of_guests'];
$selectedMinPrice = $_REQUEST['min_price'];
$selectedMaxPrice = $_REQUEST['max_price'];
// print_r($_REQUEST);die;

// Search all available rooms
$allAvailableRooms = $room->search(new DateTime($checkInDate), new DateTime($checkOutDate), $selectedCity, $selectedTypeId, $selectedGuestCount, $selectedMinPrice, $selectedMaxPrice);
// print_r($allAvailableRooms);die;

// Get cities
$cities = $room->getCities();

// Get Count of Guests
$guestcount = $room->getGuestCount();

//Get room types
$type = new RoomType();
$allTypes = $type->getAllTypes();

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
    <script src="./assets/js/jquery.ui.touch-punch.min.js"></script>
    <script src="./assets/pages/search.js"></script>
    <link rel="shortcut icon" href="./assets/images/favicon/favicon.ico" />
    <title>List Page</title>
    <style>
      .ui-state-active .ui-icon,
      .ui-button:active .ui-icon {
        background-image: url("images/ui-icons_555555_256x240.png");
      }
    </style>    
  </head>
  <body data-pagetype="list-page">
    <?php include '_header.php'; ?>

    <div class="container list-container">
      <aside class= "hotel-search box">
        <h2> Find the perfect hotel </h2>
        <div class="accordion-wrapper">
          <form method="get" action="list.php" name="searchForm" class="searchForm" id="searchForm">
            <div class="form-group guest-number">
              <label for="formGuest-number"></label>
              <select id="formGuest-number" name="count_of_guests" title="Choose the number of guest">
                <option value="" selected >Count of Guests</option>
                <?php 
                  foreach ($guestcount as $count) {
                ?>
                  <option <?php echo $selectedGuestCount == $count ? 'selected="selected"': ''; ?>value="<?php echo $count ?>"><?php echo sprintf($count . ($count == 1 ? ' Guest' : ' Guests')); ?></option>
                <?php 
                  }
                ?>
              </select>
            </div>
            <div class="form-group room-type">
              <label for="formRoom-Type"></label>
              <select id="formRoom-Type" name="room_type" title="Choose a Room type">
                <option value="" selected  >Room Type</option>
                <?php 
                  foreach ($allTypes as $roomType) {
                ?>
                  <option <?php echo $selectedTypeId == $roomType['type_id'] ? 'selected="selected"': ''; ?> value="<?php echo $roomType['type_id'] ?>"><?php echo $roomType['title'] ?></option>
                <?php 
                  }
                ?>
              </select>
            </div>
            <div class="form-group city-select">
              <label for="formCity"></label>
              <select class="text-center" id="formCity" name="city"class="city" title="Choose a City">
                <option value="" selected >City</option>
                <?php 
                  foreach ($cities as $city) {
                ?>
                  <option <?php echo $selectedCity == $city ? 'selected="selected"': ''; ?> value="<?php echo $city ?>"><?php echo $city ?></option>
                <?php 
                  }
                ?>
              </select>
            </div>
            <div class="middle" >
              <div class="price-display-menu">
                <span class="min-price">0 €</span>
                <span class="max-price">5000 €</span>
                <input type="hidden" name="min_price" id="min_price">
                <input type="hidden" name="max_price" id="max_price">
              </div>
              <div id = "slider"></div>
              <div class="price-labels">
                <span><p>PRICE MIN.</p></span>
                <span><p>PRICE MAX.</p></span>
              </div>
            </div>
            
            <div class="form-group check-in-date">
              <input type="text" name="check_in_date" id="checkInDate" placeholder=" Check-in Date" title="Choose a Check-in Date" class="text-center" value="<?php echo $checkInDate ?>" />
              <div class= "c-validation check_in_date_error"></div>
            </div>
            <div class="form-group check-out-date">
              <input type="text" name="check_out_date" id="checkOutDate" placeholder=" Check-out Date" title="Choose a Check-out Date" class="text-center" value="<?php echo $checkOutDate ?>" />
              <div class= "c-validation check_out_date_error"></div>
            </div>
            <div class="action">
              <button id="submit" type="submit" title="Find Rooms">Find Hotel</button>
            </div>
          </form>
        </div>
      </aside>
      <section class="hotel-list box" id="search-results-container">
        <header class="page-title">
            <h2>Search Results</h2>
        </header>
        <?php 
          foreach ($allAvailableRooms as $availableRoom) {
        ?>
          <article class="hotel">
              <aside class="media">
                  <img src="<?php echo ("./assets/images/rooms/" . $availableRoom['photo_url']); ?>" alt="<?php echo $availableRoom['name']?>" width="100%" height="auto">
              </aside>
              <main>
                <div class="info">
                  <h1><?php echo $availableRoom['name']?></h1>
                  <h2><?php echo $availableRoom['city'] . ' , ' . $availableRoom['area']; ?></h2>
                  <p> <?php echo $availableRoom['description_short'] ?> </p>
                </div>
                <div class="text-right roomBtn">
                    <button title="Go to Room Page"><a href="<?php echo ("room.php?room_id=" . $availableRoom['room_id'] . "&check_in_date=" . $checkInDate . "&check_out_date=" . $checkOutDate ); ?>">Go to Room Page </a></button>
                </div>
              </main>
            <div class="clear"></div>
            <footer>
              <div class="list-footer-container">
                <ul>
                  <li class="room-price">
                    <p> Per Night: <?php echo $availableRoom['price'] . ' €' ?>
                    </p>
                  </li>
                  <li class="guest-count">
                    <p>Count of Guests: <?php echo $availableRoom['count_of_guests'] ?>
                    </p>
                  </li>
                  <li class="room-type">
                    <p>Type of Room: 
                    <?php foreach ($allTypes as $roomType) {
                        echo ($roomType['type_id'] == $availableRoom['count_of_guests']) ? $roomType['title'] : '';
                      } ?>
                    </p>
                  </li>
                </ul>
              </div>
              <div class="clear"></div>
            </footer>
          </article>
        <?php 
          }
        ?>
        <?php 
          if (count($allAvailableRooms) == 0) {
        ?>
          <div>
            <h2 class="text-center">There are no rooms available</h2>
          </div>
        <?php
          }
        ?>
        <hr>
      </section>
    </div>

    <?php include '_footer.php'; ?>

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
                  let w = $(window).width();
                  // Check window width and place calendar accordingly
                  w < 480 ? offset.left = offset.left -40 : '';
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
                  w < 480 ? offset.left = offset.left -40 : '';
                  $(inst.dpDiv).css({ top: (offset.top + height) + 'px', left:offset.left + 'px'})
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

    <script>
      // Media query for screens with width < 750px
      // Turn the search menu into a jQuery Ui accordion when width < 750px 
      $(function(){
        function insertAccordion(x) {
          if (x.matches) { // If media query matches
            $('aside.hotel-search').addClass("accordion");
            $(function() {
              var icons = {
                "header": "ui-icon-circle-arrow-e",
                "activeHeader": "ui-icon-circle-arrow-s"
              };
              $( ".accordion" ).accordion({
                collapsible: true,
                active: false,
                autoHeight: false,
                icons: icons
              }); 
            });
          } else {
            
            $('.accordion').accordion('destroy');
            $('aside.hotel-search').removeClass("accordion");
            $('.accordion-wrapper').css("overflow", "");
            
          }
        }

        let windowWidth = window.matchMedia("(max-width: 750px)");
        insertAccordion(windowWidth); // Call listener function at run time
        windowWidth.addListener(insertAccordion); // Attach listener function on state changes
      }); 
    </script>

    <link href="./assets/css/styles.css" type="text/css" rel="stylesheet" />
    <link rel= "stylesheet" type="text/css" href="./assets/css/fontawesome.min.css" />
    <script src="./assets/js/script.js"></script>
    <script src="./assets/js/slider.js"></script>
  </body>
</html>
