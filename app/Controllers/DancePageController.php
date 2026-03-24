<?php

declare(strict_types=1);

namespace App\Controllers;
use App\Core\BaseController;
use App\Services\IPageSectionService;
use App\Services\IAdminPageService;
final class DancePageController extends BaseController
{

    private IPageSectionService $pageSectionService;
    private IAdminPageService $adminPageService;

    public function __construct(IPageSectionService $pageSectionService, IAdminPageService $adminPageService)
    {
        $this->pageSectionService = $pageSectionService;
        $this->adminPageService = $adminPageService;
    }

    public function dance(): void
    {
        try {
            $this->ensureSession();

            $page = $this->adminPageService->getPageBySlug('dance');
            if ($page === null) {
                $this->view('no_page/index', ['error' => 'Dance page not found']);
                return;
            }

            $sections = $this->pageSectionService->getSectionsByPageId((int)$page->page_id);


            $this->view('/dance/index', [
                'page' => $page,
                'sections' => $sections,
                'title' => $page->title ?? 'Dance',
            ]);
        } catch (\Throwable $e) {
            $this->view('no_page/index', ['error' => 'Failed to load Dance page']);
        }
    }
}

