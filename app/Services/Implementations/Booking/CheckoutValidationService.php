<?php

declare(strict_types=1);

namespace App\Services\Implementations\Booking;

use App\Services\Interfaces\IBookingStrategy;

final class CheckoutValidationService
{
    private const DEFAULT_TYPE = 'history-book-tour';

    /** @var array<string, IBookingStrategy> indexed by handledType() */
    private array $strategies = [];

    /**
     * @param iterable<IBookingStrategy> $strategies one per My-Program item type
     */
    public function __construct(iterable $strategies)
    {
        foreach ($strategies as $strategy) {
            $this->strategies[$strategy->handledType()] = $strategy;
        }
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

            $type = trim((string) ($item['type'] ?? self::DEFAULT_TYPE));
            if ($type === '') {
                $type = self::DEFAULT_TYPE;
            }

            $strategy = $this->strategies[$type] ?? null;
            if ($strategy === null) {
                throw new \InvalidArgumentException('An item in My Program is not supported for checkout.');
            }

            // Each strategy re-validates its own item type (and enforces its own
            // rules, e.g. restaurant capacity) — the loop stays type-agnostic.
            $normalized[] = $this->ensureItemId($strategy->validateProgramItem($item));
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
