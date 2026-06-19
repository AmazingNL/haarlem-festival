<?php

declare(strict_types=1);

namespace App\Services\Implementations\Booking;

use App\Models\Enum\SectionType;
use App\Models\ProgramItem;
use App\Services\Interfaces\IBookingStrategy;
use App\Services\Interfaces\IPageSectionService;

/**
 * Zero-database booking for a Jazz agenda performance.
 *
 * Each performance is a CMS "jazz_agenda_event" page section (added/removed by
 * admins in the dashboard) — these live on the Jazz landing page and on the
 * artist detail pages. This strategy re-reads that section by id — both when
 * adding to My Program and again at checkout — and derives the price and title
 * straight from the CMS, never trusting the cart. There is no event or
 * ticket_type row behind a performance.
 */
final class JazzBookingService implements IBookingStrategy
{
    private IPageSectionService $pageSectionService;

    public function __construct(IPageSectionService $pageSectionService)
    {
        $this->pageSectionService = $pageSectionService;
    }

    public function handledType(): string
    {
        return 'jazz-performance';
    }

    public function buildProgramItem(int $sectionId, int $quantity): array
    {
        $performance = $this->getPerformanceSection($sectionId);
        if ($performance === []) {
            throw new \InvalidArgumentException('That jazz performance is not available right now.');
        }

        $unitPrice = $this->parseMoney((string) ($performance['price'] ?? ''));
        if ($unitPrice <= 0) {
            throw new \InvalidArgumentException('This performance cannot be booked online.');
        }

        $quantity = max(1, min(10, $quantity));
        $title = trim((string) ($performance['title'] ?? 'Jazz Performance'));
        $venue = trim((string) ($performance['venue'] ?? ''));
        $day = trim((string) ($performance['day'] ?? ''));
        $time = trim((string) ($performance['time_text'] ?? ''));

        $selectionText = trim(implode(' | ', array_filter([$day, $time, $venue])));

        return ProgramItem::fromArray([
            'type' => 'jazz-performance',
            'section_id' => $sectionId,
            'title' => $title,
            'day' => $day,
            'time' => $time,
            'ticket_title' => 'Jazz Performance',
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => round($unitPrice * $quantity, 2),
            'selection_text' => $selectionText,
            'ticket_summary_text' => $quantity === 1 ? '1 ticket' : $quantity . ' tickets',
            'location_name' => $venue !== '' ? $venue : 'Patronaat',
            'category_label' => 'Jazz',
        ])->toArray();
    }

    public function validateProgramItem(array $item): array
    {
        $built = $this->buildProgramItem(
            max(0, (int) ($item['section_id'] ?? 0)),
            max(1, (int) ($item['quantity'] ?? 1))
        );

        $built['id'] = trim((string) ($item['id'] ?? ''));
        $built['special_requests'] = trim((string) ($item['special_requests'] ?? ''));

        return $built;
    }

    /**
     * Find one published jazz_agenda_event section by its id, on any page (the
     * Jazz landing page or an artist detail page). Returns the section's content
     * fields (price, title, venue, ...) as a flat array, or [] when not bookable.
     */
    private function getPerformanceSection(int $sectionId): array
    {
        if ($sectionId <= 0) {
            return [];
        }

        $section = $this->pageSectionService->getSectionById($sectionId);
        if (
            $section === null
            || $section->section_type !== SectionType::JAZZ_AGENDA_EVENT
            || !$section->is_published
        ) {
            return [];
        }

        $content = json_decode((string) $section->content, true);

        return is_array($content) ? $content : [];
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
