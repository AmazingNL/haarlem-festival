<?php

declare(strict_types=1);

namespace App\Models;

/**
 * A single line in a visitor's "My Program" (the session cart).
 *
 * Program items are polymorphic: the {@see self::$type} discriminator
 * ("event-ticket", "yummy-reservation", "history-book-tour", "stories-show") decides which
 * fields are meaningful. This model is the single normalization authority —
 * {@see self::fromArray()} clamps and defaults raw input, and {@see self::toArray()}
 * produces the canonical array shape the session, views, and checkout consume.
 *
 * It is a transient cart entity, not a persisted database row.
 */
final class ProgramItem
{
    public function __construct(
        public string $id = '',
        public string $type = 'history-book-tour',
        public int $event_id = 0,
        public int $ticket_type_id = 0,
        public int $section_id = 0,
        public int $show_id = 0,
        public string $slug = '',
        public string $title = 'Festival Booking',
        public string $day = '',
        public string $time = '',
        public string $language = '',
        public string $ticket_key = '',
        public string $ticket_title = '',
        public int $quantity = 1,
        public float $unit_price = 0.0,
        public float $total_price = 0.0,
        public string $selection_text = '',
        public string $ticket_summary_text = '',
        public string $location_name = 'Bavo Church',
        public string $category_label = 'Festival',
        public string $special_requests = '',
        public string $customer_name = '',
        public string $customer_email = '',
        public string $customer_phone = '',
        public string $page_slug = '',
        public int $adult_count = 0,
        public int $child_count = 0,
        public float $adult_price = 0.0,
        public float $child_price = 0.0,
        public string $starts_at = '',
        public string $ends_at = '',
    ) {
    }

    /**
     * Build a normalized program item from raw (untrusted) input.
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $quantity = max(1, min(10, (int) ($data['quantity'] ?? 1)));
        $unitPrice = round((float) ($data['unit_price'] ?? 0), 2);

        $type = trim((string) ($data['type'] ?? 'history-book-tour'));
        if ($type === '') {
            $type = 'history-book-tour';
        }

        $id = trim((string) ($data['id'] ?? ''));
        if ($id === '') {
            $id = bin2hex(random_bytes(8));
        }

        return new self(
            id: $id,
            type: $type,
            event_id: max(0, (int) ($data['event_id'] ?? 0)),
            ticket_type_id: max(0, (int) ($data['ticket_type_id'] ?? 0)),
            section_id: max(0, (int) ($data['section_id'] ?? 0)),
            show_id: max(0, (int) ($data['show_id'] ?? 0)),
            slug: trim((string) ($data['slug'] ?? '')),
            title: trim((string) ($data['title'] ?? 'Festival Booking')),
            day: trim((string) ($data['day'] ?? '')),
            time: trim((string) ($data['time'] ?? '')),
            language: trim((string) ($data['language'] ?? '')),
            ticket_key: trim((string) ($data['ticket_key'] ?? '')),
            ticket_title: trim((string) ($data['ticket_title'] ?? '')),
            quantity: $quantity,
            unit_price: $unitPrice,
            total_price: round($unitPrice * $quantity, 2),
            selection_text: trim((string) ($data['selection_text'] ?? '')),
            ticket_summary_text: trim((string) ($data['ticket_summary_text'] ?? '')),
            location_name: trim((string) ($data['location_name'] ?? '')),
            category_label: trim((string) ($data['category_label'] ?? 'Festival')),
            special_requests: trim((string) ($data['special_requests'] ?? '')),
            customer_name: trim((string) ($data['customer_name'] ?? '')),
            customer_email: trim((string) ($data['customer_email'] ?? '')),
            customer_phone: trim((string) ($data['customer_phone'] ?? '')),
            page_slug: trim((string) ($data['page_slug'] ?? '')),
            adult_count: max(0, (int) ($data['adult_count'] ?? 0)),
            child_count: max(0, (int) ($data['child_count'] ?? 0)),
            adult_price: round((float) ($data['adult_price'] ?? 0), 2),
            child_price: round((float) ($data['child_price'] ?? 0), 2),
            starts_at: trim((string) ($data['starts_at'] ?? '')),
            ends_at: trim((string) ($data['ends_at'] ?? '')),
        );
    }

    /**
     * Canonical array shape stored in the session and consumed by views/checkout.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'event_id' => $this->event_id,
            'ticket_type_id' => $this->ticket_type_id,
            'section_id' => $this->section_id,
            'show_id' => $this->show_id,
            'slug' => $this->slug,
            'title' => $this->title,
            'day' => $this->day,
            'time' => $this->time,
            'language' => $this->language,
            'ticket_key' => $this->ticket_key,
            'ticket_title' => $this->ticket_title,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'total_price' => $this->total_price,
            'selection_text' => $this->selection_text,
            'ticket_summary_text' => $this->ticket_summary_text,
            'location_name' => $this->location_name,
            'category_label' => $this->category_label,
            'special_requests' => $this->special_requests,
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_phone' => $this->customer_phone,
            'page_slug' => $this->page_slug,
            'adult_count' => $this->adult_count,
            'child_count' => $this->child_count,
            'adult_price' => $this->adult_price,
            'child_price' => $this->child_price,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
        ];
    }
}
