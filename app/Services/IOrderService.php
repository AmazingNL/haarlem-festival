<?php
namespace App\Services;

use App\Models\Order;
use App\Schemas\yummy\BookReservation;

interface IOrderService {

    public function getAllOrders() : array; 
    public function getOrderById(int $id) : Order;
    public function createOrder(BookReservation $bookReservation) : void;   
    
}