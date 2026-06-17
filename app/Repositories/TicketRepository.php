<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\BaseRepository;
use PDO;

final class TicketRepository extends BaseRepository implements ITicketRepository
{
    /**
     * Find a ticket row by its QR token.
     *
     * @param  string $token 64-char hex qr_token
     * @return array{ticket_id:int,qr_token:string,status:string}|null
     */
    public function findByToken(string $token): ?array
    {
        $stmt = $this->getConnection()->prepare(
            'SELECT ticket_id, qr_token, status FROM ticket WHERE qr_token = ? LIMIT 1'
        );
        $stmt->execute([$token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return is_array($row) ? $row : null;
    }
}
