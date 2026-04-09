<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\IAdminPageService;
use App\Services\IPageSectionService;

final class HistoryController extends BaseController
{
    private IPageSectionService $pageSectionService;
    private IAdminPageService $adminPageService;

    public function __construct(IPageSectionService $pageSectionService, IAdminPageService $adminPageService)
    {
        $this->pageSectionService = $pageSectionService;
        $this->adminPageService = $adminPageService;
    }

    public function index(): void
    {
        $this->renderHistoryPage(
            'history',
            'history/index',
            'History',
            ['historyPage' => 'overview']
        );
    }

    public function bookTour(): void
    {
        $this->ensureSession();
        $this->renderHistoryPage(
            'history-book-tour',
            'history/book_tour',
            'Book Tour',
            ['historyPage' => 'book-tour']
        );
    }

    public function routeMap(): void
    {
        $this->renderHistoryPage(
            'history-route-map',
            'history/route_map',
            'Route Map',
            ['historyPage' => 'route-map']
        );
    }

    public function stBavosChurch(): void
    {
        $this->renderHistoryPage(
            'history-st-bavos-church',
            'history/st_bavos_church',
            "St. Bavo's Church",
            ['historyPage' => 'st-bavo']
        );
    }

    public function molenDeAdriaan(): void
    {
        $this->renderHistoryPage(
            'history-molen-de-adriaan',
            'history/molen_de_adriaan',
            'Molen de Adriaan',
            ['historyPage' => 'molen']
        );
    }

    public function addBookTourToProgram(): void
    {
        $this->ensureSession();
        $this->verifyCsrf();

        $bookingSection = $this->getSectionByTypeFromSlug('history-book-tour', 'history_book_tour_booking');
        if ($bookingSection === []) {
            $this->setFlash('error', 'The Book Tour section is not available right now.');
            $this->redirect('/history/book-tour');
            return;
        }

        $day = trim($this->str('selected_day'));
        $time = trim($this->str('selected_time'));
        $language = trim($this->str('selected_language'));
        $ticketKey = trim($this->str('ticket_key')) === 'family' ? 'family' : 'individual';

        $dayOptions = $this->filledOptions($bookingSection, ['day_one', 'day_two', 'day_three', 'day_four']);
        $timeOptions = $this->filledOptions($bookingSection, ['time_one', 'time_two', 'time_three']);
        $languageOptions = $this->filledOptions($bookingSection, ['language_one', 'language_two', 'language_three']);

        if (
            $day === ''
            || $time === ''
            || $language === ''
            || !in_array($day, $dayOptions, true)
            || !in_array($time, $timeOptions, true)
            || !in_array($language, $languageOptions, true)
        ) {
            $this->setFlash('error', 'Please choose a day, time, language, and ticket before adding the tour.');
            $this->redirect('/history/book-tour');
            return;
        }

        $individualTitle = trim((string) ($bookingSection['individual_title'] ?? 'Individual'));
        $familyTitle = trim((string) ($bookingSection['family_title'] ?? 'Family'));
        $familySize = $this->parseCount((string) ($bookingSection['family_price'] ?? ''), 4);
        $quantity = max(1, min(10, $this->int('quantity', 1)));

        $unitPrice = $ticketKey === 'family'
            ? $this->parseMoney((string) ($bookingSection['family_price'] ?? '0'))
            : $this->parseMoney((string) ($bookingSection['individual_price'] ?? '0'));

        $ticketTitle = $ticketKey === 'family' ? $familyTitle : $individualTitle;
        $bookingTitle = trim((string) ($bookingSection['heading'] ?? 'History Book Tour'));
        $selectionText = sprintf('%s, %s | %s', $day, $time, $language);
        $ticketSummaryText = $ticketKey === 'family'
            ? ($quantity === 1
                ? sprintf('%s (up to %d)', $familyTitle, $familySize)
                : sprintf('%d %s tickets (up to %d people)', $quantity, strtolower($familyTitle), $quantity * $familySize))
            : ($quantity === 1 ? '1 person' : sprintf('%d people', $quantity));

        $totalPrice = round($unitPrice * $quantity, 2);

        $_SESSION['program_items'] ??= [];
        $_SESSION['program_items'][] = [
            'id' => bin2hex(random_bytes(8)),
            'type' => 'history-book-tour',
            'title' => $bookingTitle,
            'day' => $day,
            'time' => $time,
            'language' => $language,
            'ticket_key' => $ticketKey,
            'ticket_title' => $ticketTitle,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
            'selection_text' => $selectionText,
            'ticket_summary_text' => $ticketSummaryText,
        ];

        $this->setFlash('success', 'The history tour was added to My Program.');
        $this->redirect('/program');
    }

    public function program(): void
    {
        $this->ensureSession();

        $items = $_SESSION['program_items'] ?? [];
        $programItems = is_array($items) ? array_values($items) : [];
        $programTotal = array_reduce(
            $programItems,
            static fn(float $total, array $item): float => $total + (float) ($item['total_price'] ?? 0),
            0.0
        );

        $this->view('history/program', [
            'title' => 'My Program',
            'programItems' => $programItems,
            'programTotal' => $programTotal,
        ]);
    }

    public function removeProgramItem(): void
    {
        $this->ensureSession();
        $this->verifyCsrf();

        $itemId = trim($this->str('item_id'));
        $items = $_SESSION['program_items'] ?? [];

        if ($itemId !== '' && is_array($items)) {
            $_SESSION['program_items'] = array_values(array_filter(
                $items,
                static fn(array $item): bool => (string) ($item['id'] ?? '') !== $itemId
            ));
        }

        $this->setFlash('success', 'The item was removed from My Program.');
        $this->redirect('/program');
    }

    private function renderHistoryPage(string $slug, string $view, string $title, array $extraData = []): void
    {
        try {
            [$page, $pageSections] = $this->loadHistoryPageData($slug);
            if ($page === null || empty($pageSections)) {
                $this->view(
                    'no_page/index',
                    ['error' => 'History page not available']
                );
                return;
            }

            $section = $this->mergeSectionContent($pageSections);

            $this->view(
                $view,
                array_merge(
                    [
                        'section' => $section,
                        'page' => $page,
                        'title' => $title,
                    ],
                    $extraData
                )
            );
        } catch (\Throwable $e) {
            $this->view(
                'no_page/index',
                ['error' => 'History page not available']
            );
        }
    }

    private function loadHistoryPageData(string $slug): array
    {
        $page = $this->adminPageService->getPageBySlug($slug);
        $pageId = $page->page_id ?? null;
        if ($pageId === null) {
            return [null, []];
        }

        $pageSections = $this->pageSectionService->getSectionsByPageId($pageId);
        if (empty($pageSections)) {
            return [$page, []];
        }

        return [$page, $pageSections];
    }

    private function getSectionByTypeFromSlug(string $slug, string $sectionType): array
    {
        try {
            [, $pageSections] = $this->loadHistoryPageData($slug);
            foreach ($this->mergeSectionContent($pageSections) as $section) {
                if (($section['section_type'] ?? '') === $sectionType && !empty($section['is_published'])) {
                    return $section;
                }
            }
        } catch (\Throwable $e) {
        }

        return [];
    }

    private function filledOptions(array $section, array $keys): array
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

    private function mergeSectionContent(array $sections): array
    {
        return array_map(
            function (array $section): array {
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
