<?php

declare(strict_types=1);

namespace App\Services\Implementations\Booking;

use App\Services\Implementations\Booking\EventBookingService;
use App\Services\Implementations\Booking\HistoryBookingService;
use App\Services\Implementations\Booking\RestaurantBookingService;

final class CheckoutValidationService
{
    private EventBookingService $eventBookingService;
    private HistoryBookingService $historyBookingService;
    private RestaurantBookingService $restaurantBookingService;
    private ReservationService $reservationService;

    public function __construct(
        EventBookingService $eventBookingService,
        HistoryBookingService $historyBookingService,
        RestaurantBookingService $restaurantBookingService,
        ReservationService $reservationService
    )
    {
        $this->eventBookingService = $eventBookingService;
        $this->historyBookingService = $historyBookingService;
        $this->restaurantBookingService = $restaurantBookingService;
        $this->reservationService = $reservationService;
    }

    /** @param list<array<string, mixed>> $items */
    public function validateAndNormalize(array $items): array
    {
        if ($items === []) {
            throw new \InvalidArgumentException('My Program is empty.');
        }

        $normalized = [];
        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }

            $type = trim((string) ($item['type'] ?? 'history-book-tour'));
            if ($type === '') {
                $type = 'history-book-tour';
            }

            $validated = match ($type) {
                'event-ticket' => $this->eventBookingService->validateProgramItem($item),
                'yummy-reservation' => $this->restaurantBookingService->validateProgramItem($item),
                'history-book-tour' => $this->historyBookingService->validateProgramItem($item),
                default => throw new \InvalidArgumentException('An item in My Program is not supported for checkout.'),
            };

            // Enforce restaurant capacity for reservations before payment is taken.
            if ($type === 'yummy-reservation') {
                $this->reservationService->assertCapacityAvailable(
                    (string) ($validated['page_slug'] ?? ''),
                    (string) ($validated['day'] ?? ''),
                    (string) ($validated['time'] ?? ''),
                    (int) ($validated['adult_count'] ?? 0) + (int) ($validated['child_count'] ?? 0)
                );
            }

            $normalized[] = $this->ensureItemId($validated);
        }

        if ($normalized === []) {
            throw new \InvalidArgumentException('No valid items to pay for.');
        }

        return $normalized;
    }

    public function calculateTotalCents(array $items): int
    {
        $total = 0.0;
        foreach ($items as $item) {
            $total += round((float) ($item['total_price'] ?? 0), 2);
        }

        return (int) round($total * 100);
    }

    private function ensureItemId(array $item): array
    {
        if (trim((string) ($item['id'] ?? '')) === '') {
            $item['id'] = bin2hex(random_bytes(8));
        }

        return $item;
    }
}
