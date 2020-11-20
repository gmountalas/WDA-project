<?php 

require __DIR__.'/../../boot/boot.php';

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

<header class="page-title">
    <h2>Search Results</h2>
</header>
<section>
    <?php 
        foreach ($allAvailableRooms as $availableRoom) {
    ?>
    <article class="hotel">
        <aside class="media">
            <img src="<?php echo ("./assets/images/rooms/" . $availableRoom['photo_url']); ?>" alt="Welcome to our Site" width="100%" height="auto">
        </aside>
        <main>
        <div class="info">
            <h1><?php echo $availableRoom['name']?></h1>
            <h2><?php echo $availableRoom['city'] . ' , ' . $availableRoom['area']; ?></h2>
            <p> <?php echo $availableRoom['description_short'] ?> </p>
        </div>
        <div class="text-right roomBtn">
            <button><a href="<?php echo ("room.php?room_id=" . $availableRoom['room_id'] . "&check_in_date=" . $checkInDate . "&check_out_date=" . $checkOutDate ); ?>">Go to Room Page </a></button>
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
                    } 
                ?>
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
</section>
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