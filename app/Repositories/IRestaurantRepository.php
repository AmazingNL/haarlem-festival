<?php

namespace App\Repositories;

use App\Models\Restaurant;

interface IRestaurantRepository{

    public function getRestaurantById(int $id) : ?Restaurant;
    public function getAllRestaurants() : array;
    public function bookReservation(Restaurant $restaurant);
    

}