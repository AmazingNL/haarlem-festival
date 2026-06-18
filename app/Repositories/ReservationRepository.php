<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\BaseRepository;
use App\Models\Reservation;
use App\Models\Enum\ReservationStatus;
use PDO;

final class ReservationRepository extends BaseRepository implements IReservationRepository
{
    public function create(Reservation $reservation): int
    {
        $sql = 'INSERT INTO reservation
                (restaurant_id, user_id, order_id, reservation_date, session,
                 adult_count, child_count, special_requests, status)
                VALUES
                (:restaurant_id, :user_id, :order_id, :reservation_date, :session,
                 :adult_count, :child_count, :special_requests, :status)';
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':restaurant_id' => $reservation->restaurant_id,
            ':user_id' => $reservation->user_id,
            ':order_id' => $reservation->order_id,
            ':reservation_date' => $reservation->reservation_date,
            ':session' => $reservation->session,
            ':adult_count' => $reservation->adult_count,
            ':child_count' => $reservation->child_count,
            ':special_requests' => $reservation->special_requests,
            ':status' => $reservation->status->value,
        ]);

        return (int) $this->getConnection()->lastInsertId();
    }

    public function findById(int $id): ?Reservation
    {
        $stmt = $this->getConnection()->prepare('SELECT * FROM reservation WHERE reservation_id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->hydrate($row) : null;
    }

    /** @return Reservation[] */
    public function findByUser(int $userId): array
    {
        $stmt = $this->getConnection()->prepare('SELECT * FROM reservation WHERE user_id = :uid ORDER BY reservation_id DESC');
        $stmt->execute([':uid' => $userId]);

        return array_map(fn (array $row): Reservation => $this->hydrate($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function countGuestsForSlot(int $restaurantId, string $date, string $session): int
    {
        $sql = 'SELECT COALESCE(SUM(adult_count + child_count), 0)
                FROM reservation
                WHERE restaurant_id = :rid
                  AND reservation_date = :date
                  AND session = :session
                  AND status <> :cancelled';
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':rid' => $restaurantId,
            ':date' => $date,
            ':session' => $session,
            ':cancelled' => ReservationStatus::cancelled->value,
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function BookedGuestsForRestaurant(int $restaurantId): int
    {
        $sql = 'SELECT COALESCE(MAX(slot_guests), 0) FROM (
                    SELECT SUM(adult_count + child_count) AS slot_guests
                    FROM reservation
                    WHERE restaurant_id = :rid AND status <> :cancelled
                    GROUP BY reservation_date, session
                ) slots';
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':rid' => $restaurantId,
            ':cancelled' => ReservationStatus::cancelled->value,
        ]);

        return (int) $stmt->fetchColumn();
    }

    /** @param array<string, mixed> $row */
    private function hydrate(array $row): Reservation
    {
        return new Reservation(
            reservation_id: isset($row['reservation_id']) ? (int) $row['reservation_id'] : null,
            restaurant_id: (int) ($row['restaurant_id'] ?? 0),
            user_id: (int) ($row['user_id'] ?? 0),
            order_id: isset($row['order_id']) ? (int) $row['order_id'] : null,
            reservation_date: (string) ($row['reservation_date'] ?? ''),
            session: (string) ($row['session'] ?? ''),
            adult_count: (int) ($row['adult_count'] ?? 0),
            child_count: (int) ($row['child_count'] ?? 0),
            special_requests: $row['special_requests'] ?? null,
            status: ReservationStatus::tryFrom((string) ($row['status'] ?? 'pending')) ?? ReservationStatus::pending,
            created_at: $row['created_at'] ?? null,
            updated_at: $row['updated_at'] ?? null,
        );
    }
}
