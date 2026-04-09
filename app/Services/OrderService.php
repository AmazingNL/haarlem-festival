<?php

namespace App\Services;
use App\Models\Order;
use App\Repositories\IOrderRepository;
use App\ViewModels\yummy\BookReservation;

class OrderService implements IOrderService
{
    private IOrderRepository $orderRepo;

    public function OrderService (IOrderRepository $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function getAllOrders(): array
    {
        return $this->orderRepo->getAllOrders();
    }
    public function getOrderById(int $id) : Order
    {
        return $this->orderRepo->getOrderById($id);
    }
    public function createOrder(BookReservation $bookReservation): void
    {
        $bookReservation->userId = $_SESSION['user_id'] ?? null;

        $amount = 
        $this->orderRepo->createOrder($order);
    }
}