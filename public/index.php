<?php 

require __DIR__.'/../boot/boot.php';

use Hotel\Room;
use Hotel\RoomType;

// Get cities
$room = new Room();
$cities = $room->getCities();

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
      <title>Search Room</title>
      <link href="./assets/css/jquery-ui.css" rel="stylesheet" type="text/css">
      <link rel="shortcut icon" href="./assets/images/favicon/favicon.ico" />
      <script src="./assets/js/jquery-3.5.1.min.js"></script>
      <script src="./assets/js/jquery-3.5.1.js"></script>
      <script src="./assets/js/jquery-ui.js"></script>
      <script src="./assets/pages/search.js"></script>
  </head>
  <body data-pagetype="index">
      <?php include '_header.php'; ?>

      <main class="main-content">
        <section class="hero text-center">
          <form name="searchForm" method="get" action="list.php" id="indexForm" class="indexForm">
            <div class="form-group city-select ">
              <!-- <label for="formCity"></label> -->
              <select name="city" class="text-center" id="formCity" title="Choose a City">
                <option value="" selected hidden >City</option>
                <?php 
                  foreach ($cities as $city) {
                ?>
                  <option value="<?php echo $city ?>"><?php echo $city ?></option>
                <?php 
                  }
                ?>
              </select>
            </div>
            <div class="form-group room-type">
              <label for="formRoom-Type"></label>
              <select name="room_type" id="formRoom-Type" title="Choose a Room type">
                <option value="" selected hidden >Room Type</option>
                <?php 
                  foreach ($allTypes as $roomType) {
                ?>
                  <option value="<?php echo $roomType['type_id'] ?>"><?php echo $roomType['title'] ?></option>
                <?php 
                  }
                ?>
              </select>
            </div>
            <div class="form-group check-in-date">
              <input type="text" name="check_in_date" id="checkInDate" placeholder=" Check-in Date" class="text-center" title="Choose a Check-in Date"/>
              <div class= "c-validation check_in_date_error"></div>
            </div>
            <div class="form-group check-out-date">
              <input type="text" name="check_out_date" id="checkOutDate" placeholder=" Check-out Date" class="text-center" title="Choose a Check-out Date"/>
              <div class= "c-validation check_out_date_error"></div>
            </div>
            <div class="action text-center">
              <button id="submitBtn" type="submit" title="Search for rooms">Search</button>
            </div>
          </form>
        </section>
      </main>
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
                    let h = $(window).height();
                    // Check window width and place calendar accordingly
                    w < 480 ? offset.left = offset.left -40 : '';
                    h < 430 ? height = -70 : '';
                    $(inst.dpDiv).css({ top: (offset.top + height) + 'px', left:offset.left + 'px' })
                }, 1);
            }
          });
          $("#checkInDate").datepicker("setDate", "0");
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
                    let h = $(window).height();
                    // Check window width and place calendar accordingly
                    w < 480 ? offset.left = offset.left -40 : '';
                    h < 430 ? height = -70 : '';
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
          
          $("#checkOutDate").datepicker("setDate", "2");
        });
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
      <link href="./assets/css/styles.css" type="text/css" rel="stylesheet" />
      <link rel= "stylesheet" type="text/css" href="./assets/css/fontawesome.min.css" />
      <script src="./assets/js/script.js"></script>
      
  </body>
</html>
