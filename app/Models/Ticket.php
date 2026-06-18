<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Enum\TicketStatus;

/**
 * An issued ticket with a secure QR token, scannable at the entrance.
 */
final class Ticket
{
    public function __construct(
        public ?int $ticket_id = null,
        public ?int $order_line_id = null,
        public string $qr_token = '',
        public TicketStatus $status = TicketStatus::valid,
        public ?string $scanned_at = null,
    ) {
    }
}
