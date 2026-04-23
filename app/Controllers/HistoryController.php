<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\IAdminPageService;
use App\Services\IPageSectionService;
use App\Services\ProgramService;

final class HistoryController extends BaseController
{
    // Service for loading section records from the CMS.
    private IPageSectionService $pageSectionService;
    // Service for loading page records like "history" or "history-book-tour".
    private IAdminPageService $adminPageService;
    // Service for saving bookings into My Program.
    private ProgramService $programService;

    // Store the services that this controller needs.
    public function __construct(
        IPageSectionService $pageSectionService,
        IAdminPageService $adminPageService,
        ProgramService $programService
    )
    {
        $this->pageSectionService = $pageSectionService;
        $this->adminPageService = $adminPageService;
        $this->programService = $programService;
    }

    // Show the main History overview page.
    public function index(): void
    {
        $this->showHistoryPage('history', 'history/index', 'History', [
            'historyPage' => 'overview',
        ]);
    }

    // Show the Book Tour page.
    public function bookTour(): void
    {
        $this->ensureSession();
        $this->showHistoryPage('history-book-tour', 'history/book_tour', 'Book Tour', [
            'historyPage' => 'book-tour',
        ]);
    }

    // Show the History route map page.
    public function routeMap(): void
    {
        $this->showHistoryPage('history-route-map', 'history/route_map', 'Route Map', [
            'historyPage' => 'route-map',
        ]);
    }

    // Show the St. Bavo page.
    public function stBavosChurch(): void
    {
        $this->showHistoryPage('history-st-bavos-church', 'history/st_bavos_church', "St. Bavo's Church", [
            'historyPage' => 'st-bavo',
        ]);
    }

    // Show the Molen de Adriaan page.
    public function molenDeAdriaan(): void
    {
        $this->showHistoryPage('history-molen-de-adriaan', 'history/molen_de_adriaan', 'Molen de Adriaan', [
            'historyPage' => 'molen',
        ]);
    }

    // Read the Book Tour form and save the selected booking in My Program.
    public function addTourToProgram(): void
    {
        $this->ensureSession();

        try {
            $this->verifyCsrf();

            // Load the booking data that was entered in the CMS.
            $bookingData = $this->getSectionByType('history-book-tour', 'history_book_tour_booking');
            if ($bookingData === []) {
                $this->setErrorMessage('The Book Tour section is not available right now.');
                $this->redirect('/history/book-tour');
                return;
            }

            // Read the selected values from the form.
            $day = trim($this->str('selected_day'));
            $time = trim($this->str('selected_time'));
            $language = trim($this->str('selected_language'));
            $ticketKey = trim($this->str('ticket_key'));

            // Build clean option lists from the CMS data.
            $dayOptions = $this->getFilledOptions($bookingData, ['day_one', 'day_two', 'day_three', 'day_four']);
            $timeOptions = $this->getFilledOptions($bookingData, ['time_one', 'time_two', 'time_three']);
            $languageOptions = $this->getFilledOptions($bookingData, ['language_one', 'language_two', 'language_three']);

            // Reject the booking if the user sends values that are missing or not allowed.
            if (
                $day === ''
                || $time === ''
                || $language === ''
                || !in_array($ticketKey, ['individual', 'family'], true)
                || !in_array($day, $dayOptions, true)
                || !in_array($time, $timeOptions, true)
                || !in_array($language, $languageOptions, true)
            ) {
                $this->setErrorMessage('Please choose a day, time, language, and ticket before adding the tour.');
                $this->redirect('/history/book-tour');
                return;
            }

            // Read labels and prices from the booking section so the CMS controls the content.
            $individualTitle = trim((string) ($bookingData['individual_title'] ?? 'Individual'));
            $familyTitle = trim((string) ($bookingData['family_title'] ?? 'Family'));
            $familySize = $this->parseCount((string) ($bookingData['family_price'] ?? ''), 4);
            $quantity = max(1, min(10, $this->int('quantity', 1)));

            // Pick the correct price depending on the selected ticket type.
            $unitPrice = $this->parseMoney((string) ($bookingData['individual_price'] ?? '0'));
            if ($ticketKey === 'family') {
                $unitPrice = $this->parseMoney((string) ($bookingData['family_price'] ?? '0'));
            }

            // Pick the label that matches the selected ticket type.
            $ticketTitle = $individualTitle;
            if ($ticketKey === 'family') {
                $ticketTitle = $familyTitle;
            }

            // Build the text that will be shown later in My Program and checkout.
            $bookingTitle = trim((string) ($bookingData['heading'] ?? 'History Book Tour'));
            $selectionText = sprintf('%s, %s | %s', $day, $time, $language);
            $ticketSummaryText = '';

            if ($ticketKey === 'family') {
                if ($quantity === 1) {
                    $ticketSummaryText = sprintf('%s (up to %d)', $familyTitle, $familySize);
                } else {
                    $ticketSummaryText = sprintf(
                        '%d %s tickets (up to %d people)',
                        $quantity,
                        strtolower($familyTitle),
                        $quantity * $familySize
                    );
                }
            } else {
                if ($quantity === 1) {
                    $ticketSummaryText = '1 person';
                } else {
                    $ticketSummaryText = sprintf('%d people', $quantity);
                }
            }

            // Save the chosen booking in the session cart.
            $this->programService->addItem([
                'title' => $bookingTitle,
                'day' => $day,
                'time' => $time,
                'language' => $language,
                'ticket_key' => $ticketKey,
                'ticket_title' => $ticketTitle,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'selection_text' => $selectionText,
                'ticket_summary_text' => $ticketSummaryText,
                'location_name' => 'Bavo Church',
            ]);

            $this->setSuccessMessage('The history tour was added to My Program.');
            $this->redirect('/program');
        } catch (\Throwable $e) {
            $this->setErrorMessage('The history tour could not be added right now.');
            $this->redirect('/history/book-tour');
        }
    }

    // Reusable page loader for all History pages.
    private function showHistoryPage(string $slug, string $view, string $title, array $extraData = []): void
    {
        try {
            // Remember this page so the Back/Continue link can return here later.
            $this->rememberProgramReturnUrl($this->currentUrl());

            // Load one page and all of its sections from the CMS.
            $pageData = $this->getHistoryPageData($slug);
            $page = $pageData['page'];
            $pageSections = $pageData['sections'];

            // Show a fallback page if the page or its sections do not exist.
            if ($page === null || empty($pageSections)) {
                $this->view('no_page/index', ['error' => 'History page not available']);
                return;
            }

            // Decode the section JSON and pass the final data to the view.
            $this->view($view, array_merge([
                'section' => $this->mergeSectionData($pageSections),
                'page' => $page,
                'title' => $title,
            ], $extraData));
        } catch (\Throwable $e) {
            $this->view('no_page/index', ['error' => 'History page not available']);
        }
    }

    // Load one page by slug, then load all sections that belong to that page.
    private function getHistoryPageData(string $slug): array
    {
        $page = $this->adminPageService->getPageBySlug($slug);
        $pageId = $page->page_id ?? null;
        if ($pageId === null) {
            return [
                'page' => null,
                'sections' => [],
            ];
        }

        $pageSections = $this->pageSectionService->getSectionsByPageId($pageId);
        if (empty($pageSections)) {
            return [
                'page' => $page,
                'sections' => [],
            ];
        }

        return [
            'page' => $page,
            'sections' => $pageSections,
        ];
    }

    // Find one specific section type on a page, for example the booking section.
    private function getSectionByType(string $slug, string $sectionType): array
    {
        try {
            $pageData = $this->getHistoryPageData($slug);
            $pageSections = $pageData['sections'];

            // Decode section content first, then return the first published match.
            foreach ($this->mergeSectionData($pageSections) as $section) {
                if (($section['section_type'] ?? '') === $sectionType && !empty($section['is_published'])) {
                    return $section;
                }
            }
        } catch (\Throwable $e) {
            return [];
        }

        return [];
    }

    // Return only the non-empty CMS options from a list of field names.
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

    // Turn a price string like "€17.50/person" into a float we can calculate with.
    private function parseMoney(string $value): float
    {
        $raw = str_replace(',', '.', $value);
        if (!preg_match('/\d+(?:\.\d{1,2})?/', $raw, $match)) {
            return 0.0;
        }

        return (float) $match[0];
    }

    // Extract a number like 4 from text such as "for up to 4".
    private function parseCount(string $value, int $default = 4): int
    {
        if (!preg_match_all('/\d+/', $value, $matches) || empty($matches[0])) {
            return $default;
        }

        return (int) end($matches[0]);
    }

    // Decode the JSON content field so the views can use normal array keys.
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
