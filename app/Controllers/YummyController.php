<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\IRestaurantService;
use App\Models\Restaurant;
use App\ViewModels\yummy\BookReservation;

final class YummyController extends BaseController
{

    private IRestaurantService $restaurantService;

    public function __construct(IRestaurantService $restaurantService)
    {

        $this->restaurantService = $restaurantService;
    }

    public function bookReservation()
    {
        try {
            $this->requireFields(['childCount', 'adultCount', 'adultPrice', 'childPrice', 'totalPrice']);

            $childCount = (int)$this->str('childCount');
            $adultCount = (int)$this->str('adultCount');
            $adultPrice = (float)$this->str('adultPrice');
            $childPrice = (float)$this->str('childPrice');
            $totalPrice = (float)$this->str('totalPrice');

            $restaurant = new Restaurant (
                0,           // id (auto-generated)
                0,           // eventId
                0,           // orderId
                $childCount,
                $adultCount,
                $adultPrice,
                $childPrice,
                $totalPrice
            );
            $this->restaurantService->bookReservation($restaurant);
            $this->json(['success' => true, 'message' => 'Reservation booked successfully']);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

}