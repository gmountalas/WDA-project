<?php

namespace Hotel;

use PDO;
use DateTime;
use Hotel\BaseService;

class Room extends BaseService
{
    public function getCities()
    {
        // Get all cities
        $cities = [];
        try {
            $rows = $this->fetchAll('SELECT DISTINCT city FROM room');
            foreach ($rows as $row) {
                $cities[] = $row['city'];
            }
        } catch (Exception $ex) {
            //
        }
        
        return $cities;
    }

    public function getGuestCount()
    {
        // Get all cities
        $guestCount = [];
        $rows = $this->fetchAll('SELECT DISTINCT count_of_guests FROM room');
        foreach ($rows as $row) {
            $guestCount[] = $row['count_of_guests'];
        }
        return $guestCount;
    }

    public function search($checkInDate, $checkOutDate, $city = '', $typeId = '', $countOfGuests = '', $minPrice = '', $maxPrice = '')
    {
        // Get all available rooms
        $parameters = [
            ':check_in_date' => $checkInDate->format(DateTime::ATOM),
            ':check_out_date' => $checkOutDate->format(DateTime::ATOM), 
        ];
        if (!empty($city)) {
            $parameters[':city'] = $city;
        }
        if (!empty($typeId)) {
            $parameters[':type_id'] = $typeId;
        }
        if (!empty($countOfGuests)) {
            $parameters[':count_of_guests'] = $countOfGuests;
        }
        if (!empty($minPrice)) {
            $parameters[':min_price'] = $minPrice;
        }
        if (!empty($maxPrice)) {
            $parameters[':max_price'] = $maxPrice;
        }

        //Build query
        $sql = 'SELECT * FROM room WHERE ';
        if (!empty($city)) {
            $sql .= 'city = :city AND ';
        }
        if (!empty($typeId)) {
            $sql .= 'type_id = :type_id AND ';
        }
        if (!empty($countOfGuests)) {
            $sql .= 'count_of_guests = :count_of_guests AND ';
        }
        if (!empty($minPrice)) {
            $sql .= 'price >= :min_price AND ';
        }
        if (!empty($maxPrice)) {
            $sql .= 'price <= :max_price AND ';
        }
        $sql .= 'room_id NOT IN (
                SELECT room_id 
                FROM booking 
                WHERE check_in_date <= :check_out_date AND check_out_date >= :check_in_date
        )';
        return $this->fetchAll($sql, $parameters);  
    }

    public function getRoomInfo($roomId)
    {  
        $parameters = [
            ':room_id' => $roomId,
        ];
        return $this->fetch('SELECT * FROM room WHERE room_id = :room_id' , $parameters);
    }
}