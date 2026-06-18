<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Repositories\AdminDanceAvailabilityRepository;
use InvalidArgumentException;

final class AdminDanceAvailabilityService
{
    private AdminDanceAvailabilityRepository $adminDanceAvailabilityRepository;

    public function __construct(AdminDanceAvailabilityRepository $adminDanceAvailabilityRepository)
    {
        $this->adminDanceAvailabilityRepository = $adminDanceAvailabilityRepository;
    }

    public function getDanceEventsWithTicketTypes(): array
    {
        return $this->adminDanceAvailabilityRepository->findDanceEventsWithTicketTypes();
    }

    public function getDanceEventWithTicketTypes(int $eventId): ?array
    {
        if ($eventId <= 0) {
            return null;
        }

        return $this->adminDanceAvailabilityRepository->findDanceEventWithTicketTypes($eventId);
    }

    public function updateTicketQuantities(int $eventId, array $postData): void
    {
        $event = $this->getDanceEventWithTicketTypes($eventId);
        if ($event === null) {
            throw new InvalidArgumentException('Dance event not found.');
        }

        $postedQuantities = $postData['max_quantity'] ?? [];
        if (!is_array($postedQuantities)) {
            throw new InvalidArgumentException('Seat values are missing.');
        }

        foreach (($event['ticket_types'] ?? []) as $ticketType) {
            $ticketTypeId = (int) ($ticketType['ticket_type_id'] ?? 0);
            if ($ticketTypeId <= 0 || !array_key_exists((string) $ticketTypeId, $postedQuantities)) {
                continue;
            }

            $rawQuantity = trim((string) $postedQuantities[(string) $ticketTypeId]);
            if ($rawQuantity === '' || !ctype_digit($rawQuantity)) {
                throw new InvalidArgumentException('Seat values must be whole numbers of 0 or higher.');
            }

            $this->adminDanceAvailabilityRepository->updateTicketTypeMaxQuantity(
                $ticketTypeId,
                $eventId,
                (int) $rawQuantity
            );
        }
    }
}
