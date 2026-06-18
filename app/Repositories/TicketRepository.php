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

    /**
     * Set status='scanned' and scanned_at=NOW() only when status='valid'.
     *
     * @param  string $token 64-char hex qr_token
     * @return bool True when exactly one row was updated.
     */
    public function markScanned(string $token): bool
    {
        $stmt = $this->getConnection()->prepare(
            "UPDATE ticket SET status = 'scanned', scanned_at = NOW() WHERE qr_token = ? AND status = 'valid'"
        );
        $stmt->execute([$token]);
        return $stmt->rowCount() === 1;
    }
}
