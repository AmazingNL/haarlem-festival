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

        $pageId = (int) $page->page_id;
        $shows = $this->storiesRepository->getPublishedShowsForPageId($pageId);
        $raw = $this->pageSectionService->getSectionsByPageId($pageId);

        return array_map(
            fn(array $section): array => $this->prepareSection($section, $shows),
            $raw
        );
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

    /**
     * @param list<array<string, mixed>> $shows
     */
    private function prepareSection(array $section, array $shows): array
    {
        $section = $this->mergeContent($section);

        if (($section['section_type'] ?? '') !== 'storytelling_schedule') {
            return $section;
        }

        if (trim((string) ($section['subtitle'] ?? '')) === '') {
            $section['subtitle'] = 'LAST WEEKEND OF JULY 2025 · HAARLEM';
        }

        if (trim((string) ($section['title'] ?? '')) === '') {
            $section['title'] = 'Storytelling Schedule';
        }

        $section['events'] = $this->buildScheduleEvents($shows);

        return $section;
    }

    /**
     * @param list<array<string, mixed>> $shows
     * @return list<array<string, mixed>>
     */
    private function buildScheduleEvents(array $shows): array
    {
        $events = [];

        foreach ($shows as $show) {
            $event = $this->mapShowToScheduleEvent($show);
            if ($event !== null) {
                $events[] = $event;
            }
        }

        usort($events, static function (array $a, array $b): int {
            $dayOrder = ['thursday' => 1, 'friday' => 2, 'saturday' => 3, 'sunday' => 4];
            $dayCompare = ($dayOrder[$a['day']] ?? 99) <=> ($dayOrder[$b['day']] ?? 99);
            if ($dayCompare !== 0) {
                return $dayCompare;
            }

            return strcmp((string) ($a['time'] ?? ''), (string) ($b['time'] ?? ''));
        });

        return $events;
    }

    private function mapShowToScheduleEvent(array $show): ?array
    {
        $title = trim((string) ($show['show_title'] ?? $show['title'] ?? ''));
        if ($title === '') {
            return null;
        }

        $day = $this->normalizeScheduleDay((string) ($show['schedule_day'] ?? ''), (string) ($show['date'] ?? ''));
        if ($day === '') {
            return null;
        }

        $rawLang = strtolower(trim((string) ($show['schedule_language'] ?? $show['language'] ?? 'nl')));
        $lang = in_array($rawLang, ['en', 'eng', 'english'], true) ? 'en' : 'nl';

        return [
            'section_id' => (int) ($show['section_id'] ?? 0),
            'slug' => trim((string) ($show['slug'] ?? '')),
            'day' => $day,
            'language' => $lang,
            'time' => trim((string) ($show['time'] ?? '')),
            'title' => $title,
            'location' => trim((string) ($show['location'] ?? '')),
            'age' => trim((string) ($show['schedule_age'] ?? '')),
            'price' => trim((string) ($show['price'] ?? '')),
            'type' => trim((string) ($show['schedule_type'] ?? $show['show_description'] ?? '')),
        ];
    }

    private function normalizeScheduleDay(string $scheduleDay, string $dateLabel): string
    {
        $day = strtolower(trim($scheduleDay));
        $map = [
            'thu' => 'thursday',
            'thur' => 'thursday',
            'thursday' => 'thursday',
            'fri' => 'friday',
            'friday' => 'friday',
            'sat' => 'saturday',
            'saturday' => 'saturday',
            'sun' => 'sunday',
            'sunday' => 'sunday',
        ];

        if (isset($map[$day])) {
            return $map[$day];
        }

        if (preg_match('/\b(thursday|friday|saturday|sunday)\b/i', $dateLabel, $match)) {
            return strtolower($match[1]);
        }

        return '';
    }
}
