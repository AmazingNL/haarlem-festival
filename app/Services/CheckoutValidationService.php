<?php

declare(strict_types=1);

namespace App\Services;

final class CheckoutValidationService
{
    private EventCatalogService $eventCatalogService;
    private HistoryBookingCatalogService $historyBookingCatalogService;
    private YummyReservationCatalogService $yummyReservationCatalogService;

    public function __construct(
        EventCatalogService $eventCatalogService,
        HistoryBookingCatalogService $historyBookingCatalogService,
        YummyReservationCatalogService $yummyReservationCatalogService
    )
    {
        $this->eventCatalogService = $eventCatalogService;
        $this->historyBookingCatalogService = $historyBookingCatalogService;
        $this->yummyReservationCatalogService = $yummyReservationCatalogService;
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
                'event-ticket' => $this->eventCatalogService->validateProgramItem($item),
                'yummy-reservation' => $this->yummyReservationCatalogService->validateProgramItem($item),
                'history-book-tour' => $this->historyBookingCatalogService->validateProgramItem($item),
                default => throw new \InvalidArgumentException('An item in My Program is not supported for checkout.'),
            };

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
