<?php

declare(strict_types=1);

namespace App\Services\Implementations\Booking;

use App\Repositories\IStoriesRepository;
use App\Services\Interfaces\IBookingStrategy;

final class StoriesBookingService implements IBookingStrategy
{
    public function __construct(private IStoriesRepository $storiesRepository)
    {
    }

    public function handledType(): string
    {
        return 'stories-show';
    }

    public function buildProgramItem(int $showId, int $quantity): array
    {
        $show = $this->loadShow($showId);
        $quantity = $this->clampQuantity($show, $quantity);

        $title = trim((string) ($show['show_title'] ?? 'Stories Show'));
        $date = trim((string) ($show['date'] ?? ''));
        $time = trim((string) ($show['time'] ?? ''));
        $location = trim((string) ($show['location'] ?? ''));
        $unitPrice = $this->parseMoney((string) ($show['price_raw'] ?? '0'));

        return [
            'type' => 'stories-show',
            'show_id' => $showId,
            'slug' => trim((string) ($show['slug'] ?? '')),
            'title' => $title,
            'ticket_title' => $title,
            'category_label' => 'Stories',
            'date' => $date,
            'day' => $date,
            'time' => $time,
            'location' => $location,
            'location_name' => $location,
            'unit_price' => $unitPrice,
            'quantity' => $quantity,
            'total_price' => round($unitPrice * $quantity, 2),
            'selection_text' => trim(implode(' | ', array_filter([$date, $time], static fn(string $v): bool => $v !== ''))),
            'ticket_summary_text' => $quantity === 1 ? '1 ticket' : sprintf('%d tickets', $quantity),
        ];
    }

    public function validateProgramItem(array $item): array
    {
        $showId = max(0, (int) ($item['show_id'] ?? 0));
        if ($showId <= 0) {
            $slug = trim((string) ($item['slug'] ?? ''));
            if ($slug !== '') {
                $show = $this->storiesRepository->getShowBySlug($slug);
                $showId = $show !== null ? (int) ($show['section_id'] ?? 0) : 0;
            }
        }

        if ($showId <= 0) {
            throw new \InvalidArgumentException(
                'This story session is missing booking details. Remove it from My Program and add it again from the Stories page.'
            );
        }

        $built = $this->buildProgramItem($showId, max(1, (int) ($item['quantity'] ?? 1)));
        $built['id'] = trim((string) ($item['id'] ?? ''));
        $built['special_requests'] = trim((string) ($item['special_requests'] ?? ''));

        return $built;
    }

    private function loadShow(int $showId): array
    {
        if ($showId <= 0) {
            throw new \InvalidArgumentException('Choose a valid story session before checkout.');
        }

        $show = $this->storiesRepository->getShowById($showId);
        if ($show === null) {
            throw new \InvalidArgumentException('This story session is no longer available.');
        }

        return $show;
    }

    private function clampQuantity(array $show, int $quantity): int
    {
        $available = trim((string) ($show['spots_available'] ?? ''));
        if ($available !== '' && (int) $available < 1) {
            throw new \InvalidArgumentException('This story session is sold out.');
        }

        $maxQuantity = $available !== '' ? max(1, (int) $available) : 20;

        return min(max(1, $quantity), min(20, $maxQuantity));
    }

    private function parseMoney(string $value): float
    {
        $raw = str_replace(',', '.', $value);
        if (!preg_match('/\d+(?:\.\d{1,2})?/', $raw, $match)) {
            return 0.0;
        }

        return round((float) $match[0], 2);
    }
}
