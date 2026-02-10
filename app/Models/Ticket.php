<?php
// src/Domain/Ticket.php

declare(strict_types=1);
namespace App\Models;
use App\Core\BaseEntity;
use App\Models\Enum\TicketStatus;

final class Ticket extends BaseEntity
{
    public ?int $ticket_id = null;
    public int $order_ticket_id = 0;
    public string $qr_token = '';

    public TicketStatus $status = TicketStatus::valid;
    public ?string $scanned_at = null;
    public ?int $scanned_by_user_id = null;

    /** @param array<string,mixed> $row */
    public static function fromArray(array $row): static
    {
        $rawRole = $row['status'] ?? null;
        unset($row['status']); // prevent parent from assigning a raw string to the enum-typed property

        $t = parent::fromArray($row);

        if ($rawRole !== null) {
            // Prefer tryFrom to avoid ValueError on invalid values (PHP 8.1+)
            $role = TicketStatus::tryFrom((string)$rawRole);
            if ($role !== null) $t->status = $role;
        }

        return $t;
    }
}
