<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\DanceArtistService;
use App\Services\EventCatalogService;
use App\Services\ICmsService;
use App\Services\IPageSectionService;

final class DanceController extends BaseController
{
    private ICmsService $adminPageService;
    private IPageSectionService $pageSectionService;
    private EventCatalogService $eventCatalogService;
    private DanceArtistService $danceArtistService;

    public function __construct(
        ICmsService $adminPageService,
        IPageSectionService $pageSectionService,
        EventCatalogService $eventCatalogService,
        DanceArtistService $danceArtistService
    ) {
        $this->adminPageService = $adminPageService;
        $this->pageSectionService = $pageSectionService;
        $this->eventCatalogService = $eventCatalogService;
        $this->danceArtistService = $danceArtistService;
    }

    public function index(): void
    {
        $this->rememberProgramReturnUrl($this->currentUrl());

        $sections = $this->loadDanceSections();

        $this->view('dance/index', [
            'title' => 'Dance',
            'sections' => $sections,
            'hasCmsContent' => $sections !== [],
            'artists' => $this->loadDanceArtists(),
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

    private function loadDanceArtists(): array
    {
        try {
            return $this->danceArtistService->getPublishedArtists();
        } catch (\Throwable $e) {
            return [];
        }
    }
}
