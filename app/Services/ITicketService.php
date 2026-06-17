<?php

declare(strict_types=1);

namespace App\Services;

interface ITicketService
{
    /** @param string $token 64-char hex qr_token */
    public function findByToken(string $token): ?array;
}
