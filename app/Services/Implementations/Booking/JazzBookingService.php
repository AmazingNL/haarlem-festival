<?php

declare(strict_types=1);

namespace App\Services\Implementations\Booking;

use App\Models\ProgramItem;
use App\Services\Interfaces\IBookingStrategy;
use App\Services\Interfaces\ICmsService;
use App\Services\Interfaces\IPageSectionService;

/**
 * Zero-database booking for a Jazz agenda performance.
 *
 * Each performance is a CMS "jazz_agenda_event" page section (added/removed by
 * admins in the dashboard). This strategy re-reads that section by id — both
 * when adding to My Program and again at checkout — and derives the price and
 * title straight from the CMS, never trusting the cart. There is no event or
 * ticket_type row behind a performance.
 */
final class JazzBookingService implements IBookingStrategy
{
    private ICmsService $adminPageService;
    private IPageSectionService $pageSectionService;

    public function __construct(ICmsService $adminPageService, IPageSectionService $pageSectionService)
    {
        $this->adminPageService = $adminPageService;
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

    /** Find one published jazz_agenda_event section by its id (JSON already flattened). */
    private function getPerformanceSection(int $sectionId): array
    {
        if ($sectionId <= 0) {
            return [];
        }

        $page = $this->adminPageService->getPageBySlug('jazz');
        $pageId = $page->page_id ?? null;
        if ($pageId === null) {
            return [];
        }

        foreach ($this->pageSectionService->getSectionsByPageId((int) $pageId) as $section) {
            if (
                (int) ($section['section_id'] ?? 0) === $sectionId
                && ($section['section_type'] ?? '') === 'jazz_agenda_event'
                && !empty($section['is_published'])
            ) {
                return $section;
            }
        }

        return [];
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
