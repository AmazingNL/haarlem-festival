<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ITicketRepository;

final class TicketService implements ITicketService
{
    private ITicketRepository $ticketRepository;

    /** @param ITicketRepository $ticketRepository */
    public function __construct(ITicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * Find a ticket by its QR token.
     *
     * @param  string $token 64-char hex qr_token
     * @return array|null    Ticket row or null when not found.
     */
    public function findByToken(string $token): ?array
    {
        return $this->ticketRepository->findByToken($token);
    }
}
