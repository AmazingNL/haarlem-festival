<?php

declare(strict_types=1);

namespace App\Repositories;

interface ITicketRepository
{
    /** @param string $token 64-char hex qr_token */
    public function findByToken(string $token): ?array;
}
