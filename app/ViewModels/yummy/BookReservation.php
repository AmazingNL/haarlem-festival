<?php

namespace App\ViewModels\yummy;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Event;

class BookReservation {

    public int $eventId = 0;
    public Order $order = new Order();
    public Restaurant $restaurant = new Restaurant();
}