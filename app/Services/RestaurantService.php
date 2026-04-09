<?php
namespace App\Services;

use App\Models\Restaurant;
use App\Repositories\RestaurantRepository;
use ErrorException;

class RestaurantService implements IRestaurantService
{

    private RestaurantRepository $restaurant;

    public function __construct(RestaurantRepository $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    public function getAllRestaurants(): array
    {
        return $this->restaurant->getAllRestaurants();
    }

    public function getRestaurantById(int $id): Restaurant
    {
        return $this->restaurant->getRestaurantById($id);
    }

    public function bookReservation(Restaurant $restaurant): void
    {
        if ($restaurant === null) {
            throw new ErrorException("Error: Restaurant object cannot be null");
        }
        $userId = 
        $this->restaurant->bookReservation($restaurant);
    }
}