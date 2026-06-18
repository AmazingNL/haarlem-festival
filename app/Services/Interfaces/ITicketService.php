<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

interface ITicketService
{
    /** @param string $token 64-char hex qr_token */
    public function findByToken(string $token): ?array;

    /**
     * Validate and mark a ticket as scanned.
     *
     * @param  string $token 64-char hex qr_token
     * @return array{result:string} Keys: 'ok'|'already_scanned'|'cancelled'|'not_found'
     */
    public function markScanned(string $token): array;
}
