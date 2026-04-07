<?php
namespace App\Services;

use App\Models\Restaurant;


interface IRestaurantService {

    public function getRestaurantById(int $id) : ?Restaurant;
    public function getAllRestaurants() : array;
    public function bookReservation(Restaurant $restaurant) : void;
}