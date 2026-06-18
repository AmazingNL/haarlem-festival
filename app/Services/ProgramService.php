<?php

declare(strict_types=1);

namespace App\Services;

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

    // Normalize one program item so event tickets and history bookings share one consistent structure.
    private function normalizeItem(array $item): array
    {
        $quantity = max(1, min(10, (int) ($item['quantity'] ?? 1)));
        $unitPrice = round((float) ($item['unit_price'] ?? 0), 2);
        $type = trim((string) ($item['type'] ?? 'history-book-tour'));
        if ($type === '') {
            $type = 'history-book-tour';
        }

        return [
            'id' => trim((string) ($item['id'] ?? '')) !== ''
                ? trim((string) $item['id'])
                : bin2hex(random_bytes(8)),
            'type' => $type,
            'event_id' => max(0, (int) ($item['event_id'] ?? 0)),
            'ticket_type_id' => max(0, (int) ($item['ticket_type_id'] ?? 0)),
            'title' => trim((string) ($item['title'] ?? 'Festival Booking')),
            'day' => trim((string) ($item['day'] ?? '')),
            'time' => trim((string) ($item['time'] ?? '')),
            'language' => trim((string) ($item['language'] ?? '')),
            'ticket_key' => trim((string) ($item['ticket_key'] ?? '')),
            'ticket_title' => trim((string) ($item['ticket_title'] ?? '')),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => round($unitPrice * $quantity, 2),
            'selection_text' => trim((string) ($item['selection_text'] ?? '')),
            'ticket_summary_text' => trim((string) ($item['ticket_summary_text'] ?? '')),
            'location_name' => trim((string) ($item['location_name'] ?? 'Bavo Church')),
            'category_label' => trim((string) ($item['category_label'] ?? 'Festival')),
            'special_requests' => trim((string) ($item['special_requests'] ?? '')),
            'customer_name' => trim((string) ($item['customer_name'] ?? '')),
            'customer_email' => trim((string) ($item['customer_email'] ?? '')),
            'customer_phone' => trim((string) ($item['customer_phone'] ?? '')),
            'page_slug' => trim((string) ($item['page_slug'] ?? '')),
            'adult_count' => max(0, (int) ($item['adult_count'] ?? 0)),
            'child_count' => max(0, (int) ($item['child_count'] ?? 0)),
            'adult_price' => round((float) ($item['adult_price'] ?? 0), 2),
            'child_price' => round((float) ($item['child_price'] ?? 0), 2),
            'starts_at' => trim((string) ($item['starts_at'] ?? '')),
            'ends_at' => trim((string) ($item['ends_at'] ?? '')),
        ];
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
