<?php
declare(strict_types=1);
namespace App\Repositories;

use App\Core\BaseRepository;
use App\Models\Restaurant;
use Exception;
use PDO;
use RuntimeException;

class RestaurantRepository extends BaseRepository implements IRestaurantRepository
{
    private const TABLE = 'restaurant';
    private const PK = 'restaurant_id';

    public function __construct()
    {
        parent::__construct();
    }

    public function getRestaurantById(int $id): ?Restaurant
    {
        try {
            $sql = "SELECT *
                FROM " . self::TABLE . "
                WHERE " . self::PK . " = :id
                LIMIT 1";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([':id' => $id]);
            $stmt->setFetchMode(PDO::FETCH_CLASS, Restaurant::class);
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new RuntimeException("Failed to fetch restaurant from the DB");
        }
    }

    public function getAllRestaurants(): array
    {
        try {
            $sql = "SELECT *
            FROM " . self::TABLE . "ORDER BY event_id DESC";

            $stmt = $this->getConnection()->prepare($sql);
            $array = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $array;
        } catch (Exception $e) {
            throw new RuntimeException("Failed to fetch restaurant from the DB");
        }
    }

    public function bookReservation(Restaurant $restaurant): void
    {
        try{
            $sql = "INSERT INTO ". self::TABLE .
            "(restaurant_id, order_id, event_id, adult_count, child_count, adult_price, child_price, total_price)
            VALUES (:restaurant_id, :order_id, :event_id, :adult_count, :child_count, :adult_price, :child_price, :total_price)";

            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($this->toDbArray($restaurant));        
        }
        catch(Exception $e){
            throw new RuntimeException("Failed to create reservation" . $e);
        }
    }

    private function toDbArray(Restaurant $restaurant): array
    {
        return[
            ':restaurant_id' =>$restaurant->id,
            ':order_id' =>$restaurant->orderId,
            ':event_id' =>$restaurant->eventId,
            ':adult_count' =>$restaurant->adultCount,
            ':child_count' =>$restaurant->childCount,
            ':adult_price' =>$restaurant->adultPrice,
            ':child_price' =>$restaurant->childPrice,
            ':total_price' =>$restaurant->totalPrice
        ];
    }
}