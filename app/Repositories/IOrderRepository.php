<?php

declare(strict_types=1);
namespace App\Repositories;

use App\Models\Order;

interface IOrderRepository{

    public function getOrderById(int $id) : Order;
    public function getAllOrders() : array;
    public function createOrder(Order $order) : void;
}