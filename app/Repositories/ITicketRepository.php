<?php

declare(strict_types=1);

namespace App\Repositories;

interface ITicketRepository
{
    /** @param string $token 64-char hex qr_token */
    public function findByToken(string $token): ?array;

    /**
     * Mark ticket as scanned only if it is currently 'valid'.
     *
     * @param  string $token 64-char hex qr_token
     * @return bool True when exactly one row was updated.
     */
    public function markScanned(string $token): bool;
}
