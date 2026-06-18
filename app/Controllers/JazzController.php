<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\Interfaces\ICmsService;
use App\Services\Interfaces\IPageSectionService;

final class JazzController extends BaseController
{
    private IPageSectionService $pageSectionService;
    private ICmsService $adminPageService;

    public function __construct(IPageSectionService $pageSectionService, ICmsService $adminPageService)
    {
        $this->pageSectionService = $pageSectionService;
        $this->adminPageService = $adminPageService;
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
}
