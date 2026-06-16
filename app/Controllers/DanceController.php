<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\IAdminPageService;
use App\Services\IPageSectionService;

final class DanceController extends BaseController
{
    private IAdminPageService $adminPageService;
    private IPageSectionService $pageSectionService;

    public function __construct(IAdminPageService $adminPageService, IPageSectionService $pageSectionService)
    {
        $this->adminPageService = $adminPageService;
        $this->pageSectionService = $pageSectionService;
    }

    public function index(): void
    {
        $this->rememberProgramReturnUrl($this->currentUrl());

        $sections = $this->loadDanceSections();

        $this->view('dance/index', [
            'title' => 'Dance',
            'sections' => $sections,
            'hasCmsContent' => $sections !== [],
        ]);
    }

    private function loadDanceSections(): array
    {
        try {
            $page = $this->adminPageService->getPageBySlug('dance');
            $pageId = $page->page_id ?? null;
            if ($pageId === null) {
                return [];
            }

            return array_values(array_filter(
                $this->pageSectionService->getSectionsByPageId((int) $pageId),
                static fn(array $section): bool => !empty($section['is_published'])
            ));
        } catch (\Throwable $e) {
            return [];
        }
    }
}
