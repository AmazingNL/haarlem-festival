<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Models\ProgramItem;

final class ProgramService
{
    private const ITEMS_KEY = 'program_items';

    // Return the current My Program items from the session.
    public function getItems(): array
    {
        $this->ensureSession();

        $items = $_SESSION[self::ITEMS_KEY] ?? [];
        if (!is_array($items)) {
            return [];
        }

        $items = $this->deduplicateItems(array_values($items));
        $_SESSION[self::ITEMS_KEY] = $items;

        return $items;
    }

    // Add a program item or replace the matching one with the newest selection.
    public function addItem(array $item): void
    {
        $this->ensureSession();

        $items = $this->getItems();
        $normalizedItem = $this->normalizeItem($item);
        $matchingIndex = $this->findMatchingProgramItemIndex($items, $normalizedItem);

        if ($matchingIndex !== null) {
            $normalizedItem['id'] = (string) ($items[$matchingIndex]['id'] ?? $normalizedItem['id']);
            $items[$matchingIndex] = $normalizedItem;
        } else {
            $items[] = $normalizedItem;
        }

        $_SESSION[self::ITEMS_KEY] = $items;
    }

    // Remove one program item by its generated id.
    public function removeItem(string $itemId): void
    {
        $this->ensureSession();

        $_SESSION[self::ITEMS_KEY] = array_values(array_filter(
            $this->getItems(),
            static fn(array $item): bool => (string) ($item['id'] ?? '') !== $itemId
        ));
    }

    // Remove multiple items after they have been turned into a paid order.
    public function removeItemsByIds(array $itemIds): void
    {
        $this->ensureSession();

        $itemIds = array_values(array_filter(array_map(
            static fn(mixed $itemId): string => trim((string) $itemId),
            $itemIds
        )));

        if ($itemIds === []) {
            return;
        }

        $_SESSION[self::ITEMS_KEY] = array_values(array_filter(
            $this->getItems(),
            static fn(array $item): bool => !in_array((string) ($item['id'] ?? ''), $itemIds, true)
        ));
    }

    // Empty the current My Program cart.
    public function clearItems(): void
    {
        $this->ensureSession();
        unset($_SESSION[self::ITEMS_KEY]);
    }

    // Check whether My Program currently has any saved items.
    public function hasItems(): bool
    {
        return $this->getItems() !== [];
    }

    // Count the total quantity across all saved items.
    public function getItemCount(): int
    {
        $count = 0;
        foreach ($this->getItems() as $item) {
            $count += max(1, (int) ($item['quantity'] ?? 1));
        }

        return $count;
    }

    // Calculate the full total for the current My Program cart.
    public function getTotal(): float
    {
        return $this->calculateTotal($this->getItems());
    }

    // Sum all normalized item totals into one final amount.
    private function calculateTotal(array $items): float
    {
        $total = 0.0;
        foreach ($items as $item) {
            $normalizedItem = $this->normalizeItem($item);
            $total += (float) ($normalizedItem['total_price'] ?? 0);
        }

        return round($total, 2);
    }

    // Normalize one program item via the ProgramItem model so every item shares one consistent structure.
    private function normalizeItem(array $item): array
    {
        return ProgramItem::fromArray($item)->toArray();
    }

    // Remove duplicates so the same selection is stored only once.
    private function deduplicateItems(array $items): array
    {
        $uniqueItems = [];

        foreach ($items as $item) {
            $normalizedItem = $this->normalizeItem($item);
            $matchingIndex = $this->findMatchingProgramItemIndex($uniqueItems, $normalizedItem);

            if ($matchingIndex !== null) {
                $normalizedItem['id'] = (string) ($uniqueItems[$matchingIndex]['id'] ?? $normalizedItem['id']);
                $uniqueItems[$matchingIndex] = $normalizedItem;
                continue;
            }

            $uniqueItems[] = $normalizedItem;
        }

        return $uniqueItems;
    }

    // Find the matching line in My Program for a newly added item.
    private function findMatchingProgramItemIndex(array $items, array $candidate): ?int
    {
        foreach ($items as $index => $item) {
            if ($this->isSameProgramItem($item, $candidate)) {
                return $index;
            }
        }

        return null;
    }

    // Decide whether two program items represent the same booking or ticket choice.
    private function isSameProgramItem(array $left, array $right): bool
    {
        $leftEventId = (int) ($left['event_id'] ?? 0);
        $rightEventId = (int) ($right['event_id'] ?? 0);
        $leftTicketTypeId = (int) ($left['ticket_type_id'] ?? 0);
        $rightTicketTypeId = (int) ($right['ticket_type_id'] ?? 0);

        if ($leftEventId > 0 || $rightEventId > 0 || $leftTicketTypeId > 0 || $rightTicketTypeId > 0) {
            return trim((string) ($left['type'] ?? '')) === trim((string) ($right['type'] ?? ''))
                && $leftEventId === $rightEventId
                && $leftTicketTypeId === $rightTicketTypeId;
        }

        $leftType = trim((string) ($left['type'] ?? ''));
        $rightType = trim((string) ($right['type'] ?? ''));
        if ($leftType === 'yummy-reservation' || $rightType === 'yummy-reservation') {
            $keys = ['type', 'page_slug', 'title', 'day', 'time', 'location_name'];
        } else {
            $keys = ['type', 'title', 'day', 'time', 'language', 'ticket_key', 'location_name'];
        }

        foreach ($keys as $key) {
            if (trim((string) ($left[$key] ?? '')) !== trim((string) ($right[$key] ?? ''))) {
                return false;
            }
        }

        return true;
    }

    // Start the PHP session if it has not been started yet.
    private function ensureSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}
