<?php
// src/Domain/Ticket.php

declare(strict_types=1);
namespace App\Models;
use App\Core\BaseEntity;
use App\Models\Enum\TicketStatus;

final class Ticket extends 
{
    public ?int $ticket_id = null;
    public int $order_ticket_id = 0;
    public string $qr_token = '';

    public TicketStatus $status = TicketStatus::valid;
    public ?string $scanned_at = null;
    public ?int $scanned_by_user_id = null;

 
}
