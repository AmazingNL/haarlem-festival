<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\Interfaces\ICmsService;
use App\Services\Interfaces\IDanceArtistService;
use App\Services\Interfaces\IDanceScheduleService;
use App\Services\Interfaces\IPageSectionService;
use App\ViewModels\dance\DancePageViewModel;

final class DanceController extends BaseController
{
    private ICmsService $adminPageService;
    private IPageSectionService $pageSectionService;
    private IDanceArtistService $danceArtistService;
    private IDanceScheduleService $danceScheduleService;

    public function __construct(
        ICmsService $adminPageService,
        IPageSectionService $pageSectionService,
        IDanceArtistService $danceArtistService,
        IDanceScheduleService $danceScheduleService
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
        ['filterOptions' => $filterOptions, 'filters' => $filters, 'events' => $events] = $this->loadScheduleData();

        $viewData = [
            'title' => 'Dance',
            'sections' => $sections,
            'hasCmsContent' => $sections !== [],
            'artists' => $this->loadDanceArtists(),
            'events' => $events,
            'danceFilters' => $filters,
            'danceFilterOptions' => $filterOptions,
            'hasActiveDanceFilters' => $this->danceScheduleService->hasActiveFilters($filters),
        ];

        $this->view('dance/index', (new DancePageViewModel($viewData))->toArray());
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
        } catch (\Throwable) {
            return [];
        }
    }

    private function loadScheduleData(): array
    {
        $emptyFilters = [
            'date' => '',
            'venue' => '',
            'artist' => '',
            'session' => '',
            'location_id' => 0,
            'ticket_type_name' => '',
            'invalid' => false,
        ];
        $emptyOptions = ['dates' => [], 'venues' => [], 'artists' => [], 'sessions' => []];

        try {
            $filterOptions = $this->danceScheduleService->getFilterOptions();
            $filters = $this->danceScheduleService->getFiltersFromQuery($_GET, $filterOptions);

            return [
                'filterOptions' => $filterOptions,
                'filters' => $filters,
                'events' => $this->danceScheduleService->getPublishedDanceSessions($filters),
            ];
        } catch (\Throwable $e) {
            error_log('Dance schedule load failed: ' . $e->getMessage());

            return [
                'filterOptions' => $emptyOptions,
                'filters' => $emptyFilters,
                'events' => [],
            ];
        }
    }
}
