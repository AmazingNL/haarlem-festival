<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\EventCatalogService;
use App\Services\ICmsService;
use App\Services\IPageSectionService;

final class DanceController extends BaseController
{
    private ICmsService $adminPageService;
    private IPageSectionService $pageSectionService;
    private EventCatalogService $eventCatalogService;

    public function __construct(
        ICmsService $adminPageService,
        IPageSectionService $pageSectionService,
        EventCatalogService $eventCatalogService
    ) {
        $this->adminPageService = $adminPageService;
        $this->pageSectionService = $pageSectionService;
        $this->eventCatalogService = $eventCatalogService;
    }

    public function index(): void
    {
        $this->rememberProgramReturnUrl($this->currentUrl());

        $sections = $this->loadDanceSections();

        $this->view('dance/index', [
            'title' => 'Dance',
            'sections' => $sections,
            'hasCmsContent' => $sections !== [],
            'events' => $this->eventCatalogService->getPublishedEvents('dance'),
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
