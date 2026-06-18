<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\Interfaces\ICmsService;
use App\Services\Interfaces\IPageSectionService;
use App\Services\Implementations\ProgramService;
use App\Services\Implementations\Booking\HistoryBookingService;

final class HistoryController extends BaseController
{
    private IPageSectionService $pageSectionService;
    // Service for loading page records like "history" or "history-book-tour".
    private ICmsService $adminPageService;
    // Service for saving bookings into My Program.
    private ProgramService $programService;
    private HistoryBookingService $historyBookingService;

    public function __construct(
        IPageSectionService $pageSectionService,
        ICmsService $adminPageService,
        ProgramService $programService,
        HistoryBookingService $historyBookingService
    )
    {
        $this->pageSectionService = $pageSectionService;
        $this->adminPageService = $adminPageService;
        $this->programService = $programService;
        $this->historyBookingService = $historyBookingService;
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

            $item = $this->historyBookingService->buildProgramItem(
                $this->str('selected_day'),
                $this->str('selected_time'),
                $this->str('selected_language'),
                $this->str('ticket_key'),
                $this->int('quantity', 1)
            );

            $this->programService->addItem($item);
            $this->setSuccessMessage('The history tour was added to My Program.');
            $this->redirect('/program');
        } catch (\InvalidArgumentException $e) {
            $this->setErrorMessage($e->getMessage());
            $this->redirect('/history/book-tour');
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
