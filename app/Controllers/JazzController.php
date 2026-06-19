<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\Interfaces\ICmsService;
use App\Services\Interfaces\IPageSectionService;
use App\Services\Implementations\ProgramService;
use App\Services\Implementations\Booking\JazzBookingService;

final class JazzController extends BaseController
{
    private IPageSectionService $pageSectionService;
    private ICmsService $adminPageService;
    private ProgramService $programService;
    private JazzBookingService $jazzBookingService;

    public function __construct(
        IPageSectionService $pageSectionService,
        ICmsService $adminPageService,
        ProgramService $programService,
        JazzBookingService $jazzBookingService
    ) {
        $this->pageSectionService = $pageSectionService;
        $this->adminPageService = $adminPageService;
        $this->programService = $programService;
        $this->jazzBookingService = $jazzBookingService;
    }

    // Show the Jazz landing page, built from the CMS sections of the "jazz" page.
    public function loadLandingPage(): void
    {
        try {
            // Remember this page so "My Program" can send the visitor back here.
            $this->rememberProgramReturnUrl($this->currentUrl());

            $page = $this->adminPageService->getPageBySlug('jazz');
            $pageId = $page->page_id ?? null;

            if ($pageId === null) {
                $this->view('no_page/index', ['error' => 'Jazz page not available']);
                return;
            }

            $sections = $this->pageSectionService->getSectionsByPageId($pageId);
            if ($sections === []) {
                $this->view('no_page/index', ['error' => 'Jazz page not available']);
                return;
            }

            $this->view('jazz/index', [
                'section' => $sections,
                'page' => $page,
                'title' => 'Jazz',
            ]);
        } catch (\Throwable $e) {
            $this->view('no_page/index', ['error' => 'Jazz page not available']);
        }
    }

    // Show one artist detail page, built from the CMS sections of the page whose
    // slug matches the URL (e.g. /jazz/artists/gare-du-nord -> the "gare-du-nord"
    // page). Admins create these pages in the dashboard, no code change needed.
    public function artistDetail(string $slug): void
    {
        try {
            // Remember this page so "My Program" can send the visitor back here.
            $this->rememberProgramReturnUrl($this->currentUrl());

            $page = $this->adminPageService->getPageBySlug($slug);
            $pageId = $page->page_id ?? null;

            if ($pageId === null) {
                http_response_code(404);
                $this->view('no_page/index', ['error' => 'Artist not found', 'title' => 'Artist not found']);
                return;
            }

            $sections = $this->pageSectionService->getSectionsByPageId($pageId);
            if ($sections === []) {
                http_response_code(404);
                $this->view('no_page/index', ['error' => 'Artist not found', 'title' => 'Artist not found']);
                return;
            }

            $this->view('jazz/artist_detail', [
                'section' => $sections,
                'page' => $page,
                'title' => $page->title,
            ]);
        } catch (\Throwable $e) {
            http_response_code(404);
            $this->view('no_page/index', ['error' => 'Artist not found', 'title' => 'Artist not found']);
        }
    }

    // Add a jazz performance to My Program. The performance is pure CMS content:
    // we look it up by its section id and re-derive the price server-side.
    public function addToProgram(): void
    {
        $this->ensureSession();

        try {
            $this->verifyCsrf();

            $item = $this->jazzBookingService->buildProgramItem(
                max(0, $this->int('section_id')),
                max(1, $this->int('quantity', 1))
            );

            $this->programService->addItem($item);
            $this->setSuccessMessage('The jazz performance was added to My Program.');
            $this->redirect('/program');
        } catch (\InvalidArgumentException $e) {
            $this->setErrorMessage($e->getMessage());
            $this->redirect('/jazz');
        } catch (\Throwable $e) {
            $this->setErrorMessage('The jazz performance could not be added right now.');
            $this->redirect('/jazz');
        }
    }
}
