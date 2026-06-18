<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Services\Interfaces\ITicketService;

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

    /**
     * Validate and mark a ticket scanned.
     *
     * @param  string $token 64-char hex qr_token
     * @return array{result:string}
     */
    public function markScanned(string $token): array
    {
        $ticket = $this->ticketRepository->findByToken($token);
        if ($ticket === null) {
            return ['result' => 'not_found'];
        }
        if ($ticket['status'] === 'scanned') {
            return ['result' => 'already_scanned'];
        }
        if ($ticket['status'] === 'cancelled') {
            return ['result' => 'cancelled'];
        }
        $updated = $this->ticketRepository->markScanned($token);
        return ['result' => $updated ? 'ok' : 'already_scanned'];
    }
}
