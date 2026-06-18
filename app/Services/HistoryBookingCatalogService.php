<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\ICmsService;
use App\Services\IPageSectionService;

final class HistoryBookingCatalogService
{
    private ICmsService $adminPageService;
    private IPageSectionService $pageSectionService;

    public function __construct(ICmsService $adminPageService, IPageSectionService $pageSectionService)
    {
        $this->adminPageService = $adminPageService;
        $this->pageSectionService = $pageSectionService;
    }

    public function buildProgramItem(
        string $day,
        string $time,
        string $language,
        string $ticketKey,
        int $quantity
    ): array
    {
        $bookingData = $this->getBookingSection();
        if ($bookingData === []) {
            throw new \InvalidArgumentException('The Book Tour section is not available right now.');
        }

        $day = trim($day);
        $time = trim($time);
        $language = trim($language);
        $ticketKey = trim($ticketKey);
        $quantity = max(1, min(10, $quantity));

        $dayOptions = $this->getFilledOptions($bookingData, ['day_one', 'day_two', 'day_three', 'day_four']);
        $timeOptions = $this->getFilledOptions($bookingData, ['time_one', 'time_two', 'time_three']);
        $languageOptions = $this->getFilledOptions($bookingData, ['language_one', 'language_two', 'language_three']);

        if (
            $day === ''
            || $time === ''
            || $language === ''
            || !in_array($ticketKey, ['individual', 'family'], true)
            || !in_array($day, $dayOptions, true)
            || !in_array($time, $timeOptions, true)
            || !in_array($language, $languageOptions, true)
        ) {
            throw new \InvalidArgumentException('Please choose a day, time, language, and ticket before adding the tour.');
        }

        $individualTitle = trim((string) ($bookingData['individual_title'] ?? 'Individual'));
        $familyTitle = trim((string) ($bookingData['family_title'] ?? 'Family'));
        $familySize = $this->parseCount((string) ($bookingData['family_price'] ?? ''), 4);

        $unitPrice = $this->parseMoney((string) ($bookingData['individual_price'] ?? '0'));
        $ticketTitle = $individualTitle;
        if ($ticketKey === 'family') {
            $unitPrice = $this->parseMoney((string) ($bookingData['family_price'] ?? '0'));
            $ticketTitle = $familyTitle;
        }

        $bookingTitle = trim((string) ($bookingData['heading'] ?? 'History Book Tour'));
        $selectionText = sprintf('%s, %s | %s', $day, $time, $language);
        $ticketSummaryText = $this->buildTicketSummaryText($ticketKey, $quantity, $familyTitle, $familySize);
        $unitPrice = round($unitPrice, 2);

        return [
            'type' => 'history-book-tour',
            'title' => $bookingTitle,
            'day' => $day,
            'time' => $time,
            'language' => $language,
            'ticket_key' => $ticketKey,
            'ticket_title' => $ticketTitle,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => round($unitPrice * $quantity, 2),
            'selection_text' => $selectionText,
            'ticket_summary_text' => $ticketSummaryText,
            'location_name' => 'Bavo Church',
            'category_label' => 'History',
        ];
    }

    public function validateProgramItem(array $item): array
    {
        $built = $this->buildProgramItem(
            trim((string) ($item['day'] ?? '')),
            trim((string) ($item['time'] ?? '')),
            trim((string) ($item['language'] ?? '')),
            trim((string) ($item['ticket_key'] ?? '')),
            max(1, (int) ($item['quantity'] ?? 1))
        );

        $built['id'] = trim((string) ($item['id'] ?? ''));
        $built['location_name'] = trim((string) ($item['location_name'] ?? $built['location_name']));
        $built['special_requests'] = trim((string) ($item['special_requests'] ?? ''));

        return $built;
    }

    private function getBookingSection(): array
    {
        $page = $this->adminPageService->getPageBySlug('history-book-tour');
        $pageId = $page->page_id ?? null;
        if ($pageId === null) {
            return [];
        }

        foreach ($this->mergeSectionData($this->pageSectionService->getSectionsByPageId((int) $pageId)) as $section) {
            if (($section['section_type'] ?? '') === 'history_book_tour_booking' && !empty($section['is_published'])) {
                return $section;
            }
        }

        return [];
    }

    private function buildTicketSummaryText(string $ticketKey, int $quantity, string $familyTitle, int $familySize): string
    {
        if ($ticketKey === 'family') {
            if ($quantity === 1) {
                return sprintf('%s (up to %d)', $familyTitle, $familySize);
            }

            return sprintf(
                '%d %s tickets (up to %d people)',
                $quantity,
                strtolower($familyTitle),
                $quantity * $familySize
            );
        }

        return $quantity === 1 ? '1 person' : sprintf('%d people', $quantity);
    }

    /** @param list<string> $keys */
    private function getFilledOptions(array $section, array $keys): array
    {
        $options = [];
        foreach ($keys as $key) {
            $value = trim((string) ($section[$key] ?? ''));
            if ($value !== '') {
                $options[] = $value;
            }
        }

        return $options;
    }

    private function parseMoney(string $value): float
    {
        $raw = str_replace(',', '.', $value);
        if (!preg_match('/\d+(?:\.\d{1,2})?/', $raw, $match)) {
            return 0.0;
        }

        return (float) $match[0];
    }

    private function parseCount(string $value, int $default = 4): int
    {
        if (!preg_match_all('/\d+/', $value, $matches) || empty($matches[0])) {
            return $default;
        }

        return (int) end($matches[0]);
    }

    private function mergeSectionData(array $sections): array
    {
        return array_map(
            static function (array $section): array {
                $content = json_decode((string) ($section['content'] ?? ''), true);
                if (is_array($content)) {
                    $section = array_merge($section, $content);
                }

                return $section;
            },
            $sections
        );
    }
}
