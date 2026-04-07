<?php

declare(strict_types=1);

namespace App\Repositories;
use App\Core\BaseRepository;
use App\Models\Order;
use RuntimeException;
use Exception;
use PDO;
class OrderRepository extends BaseRepository implements IOrderRepository
{

    private const TABLE = "order";
    private const PK = "order_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function createOrder(Order $order): void
    {
        try {
            $sql = "INSERT INTO" . self::TABLE . "
            (user_id, amount, status, created_at, vat)
            VALUES
            (:user_id, :amount, :status, :created_at, :vat)";

            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($this->toDbArray($order));
        } catch (Exception $e) {
            throw new RuntimeException("Failed to create Order" . $e);
        }
    }

    private function toDbArray(Order $order): array
    {
        return [
            ':order_id' => $order->orderId,
            ':user_id' => $order->userId,
            ':amount' => $order->amount,
            ':vat' => $order->vat,
            ':status' => $order->status,
            ':created_at' => $order->createdAt
        ];
    }

    public function getOrderById(int $id): Order
    {
        try {
            $sql = "SELECT * FROM" . self::TABLE . "
            WHERE" . self::PK .
                " = :id LIMIT 1";

            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([':id' => $id]);
            $stmt->setFetchMode(PDO::FETCH_CLASS, Order::class);
            $order = $stmt->fetch();
            return $order;
        }
        catch (Exception $e){
            throw new RuntimeException("could not fetch order by id" .$e);
        }
    }

    public function getAllOrders(): array
    {
        try{
            $sql = "SELECT * FROM" . self::TABLE .
            "WHERE created_at >= NOW() - INTERVAL 24 HOUR"; 

            $stmt = $this->getConnection()->prepare($sql);
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $orders;
        }
        catch(Exception $e){
            throw new RuntimeException("Failed to fetch all Orders" .$e);
        }
    }
}