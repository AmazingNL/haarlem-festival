<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\Implementations\DanceArtistService;
use App\Services\Implementations\DanceScheduleService;
use App\Services\Interfaces\ICmsService;
use App\Services\Interfaces\IPageSectionService;

final class DanceController extends BaseController
{
    private ICmsService $adminPageService;
    private IPageSectionService $pageSectionService;
    private DanceArtistService $danceArtistService;
    private DanceScheduleService $danceScheduleService;

    public function __construct(
        ICmsService $adminPageService,
        IPageSectionService $pageSectionService,
        DanceArtistService $danceArtistService,
        DanceScheduleService $danceScheduleService
    ) {
        $this->adminPageService = $adminPageService;
        $this->pageSectionService = $pageSectionService;
        $this->danceArtistService = $danceArtistService;
        $this->danceScheduleService = $danceScheduleService;
    }

    public function index(): void
    {
        $this->rememberProgramReturnUrl($this->currentUrl());

        $sections = $this->loadDanceSections();
        $filterOptions = $this->danceScheduleService->getFilterOptions();
        $filters = $this->danceScheduleService->getFiltersFromQuery($_GET, $filterOptions);

        $this->view('dance/index', [
            'title' => 'Dance',
            'sections' => $sections,
            'hasCmsContent' => $sections !== [],
            'artists' => $this->loadDanceArtists(),
            'events' => $this->danceScheduleService->getPublishedDanceSessions($filters),
            'danceFilters' => $filters,
            'danceFilterOptions' => $filterOptions,
            'hasActiveDanceFilters' => $this->danceScheduleService->hasActiveFilters($filters),
        ]);
    }

    public function artistDetail(string $slug): void
    {
        $this->rememberProgramReturnUrl($this->currentUrl());

        $artist = $this->danceArtistService->getArtistDetailBySlug($slug);
        if ($artist === null) {
            http_response_code(404);
            $this->view('no_page/index', [
                'error' => 'Dance artist not found.',
                'title' => 'Artist not found',
            ]);
            return;
        }

        $this->view('dance/artist_detail', [
            'artist' => $artist,
            'title' => $artist['name'] . ' | Dance',
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
