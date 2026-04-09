<?php
// src/Domain/Ticket.php

declare(strict_types=1);
namespace App\Models;
use App\Core\BaseEntity;
use App\Models\Enum\TicketStatus;

final class Ticket extends TicketStatus
{

    public ?int $id = null;
    public int $ticketTypeId = 0;
    public int $orderItemId = 0;
    public string $qrCode = '';
    public string $scannedAt = date("Y-m-d H:i:s");

    public function __construct(
        int $id, int $ticketTypeId, int $orderItemId, string $qrCode, string $scannedAt
    ){
        $this->id = $id;
        $this->ticketTypeId = $ticketTypeId;
        $this->orderItemId = $orderItemId;
        $this->qrCode = $qrCode;
        $this->scanAt = $scannedAt;
    }

}
