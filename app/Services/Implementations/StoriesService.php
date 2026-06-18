<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Services\Interfaces\IPageSectionService;
use App\Services\Interfaces\IStoriesService;
use App\Services\Interfaces\ICmsService;

use App\Repositories\IStoriesRepository;

/**
 * Business logic for the stories feature.
 *
 * Coordinates page-section retrieval (via ICmsService + IPageSectionService)
 * and show lookups (via IStoriesRepository).
 */
final class StoriesService implements IStoriesService
{
    private ICmsService $adminPageService;
    private IPageSectionService $pageSectionService;
    private IStoriesRepository $storiesRepository;

    /**
     * @param ICmsService         $adminPageService   Resolves page by slug.
     * @param IPageSectionService $pageSectionService Fetches sections by page ID.
     * @param IStoriesRepository  $storiesRepository  Fetches individual show rows.
     */
    public function __construct(
        ICmsService $adminPageService,
        IPageSectionService $pageSectionService,
        IStoriesRepository $storiesRepository
    ) {
        $this->adminPageService   = $adminPageService;
        $this->pageSectionService = $pageSectionService;
        $this->storiesRepository  = $storiesRepository;
    }

    /**
     * Return all sections for the page at $slug with JSON content merged.
     *
     * @param  string $slug Page slug (e.g. 'stories').
     * @return array        Ordered array of merged section arrays.
     * @throws \RuntimeException If the page is not found.
     */
    public function getPageSections(string $slug): array
    {
        $page = $this->adminPageService->getPageBySlug($slug);
        if ($page === null || $page->page_id === null) {
            throw new \RuntimeException("Page not found: {$slug}");
        }
        $raw = $this->pageSectionService->getSectionsByPageId($page->page_id);
        return array_map([$this, 'mergeContent'], $raw);
    }

    /**
     * Return merged show data for a published stories_booking section.
     *
     * @param  int        $id The section_id.
     * @return array|null     Merged data, or null if not found / not published.
     */
    public function getShowById(int $id): ?array
    {
        return $this->storiesRepository->getShowById($id);
    }

    /**
     * Return merged show data for a published stories_booking section by URL slug.
     *
     * @param  string     $slug The slug stored in the JSON content field.
     * @return array|null       Merged data, or null if not found / not published.
     */
    public function getShowBySlug(string $slug): ?array
    {
        return $this->storiesRepository->getShowBySlug($slug);
    }

    /**
     * Decode and merge the JSON content column into the section array.
     *
     * @param  array $section Raw section row from the repository.
     * @return array          Section with content fields merged at the top level.
     */
    private function mergeContent(array $section): array
    {
        $content = json_decode((string) ($section['content'] ?? ''), true);
        return is_array($content) ? array_merge($section, $content) : $section;
    }
}
