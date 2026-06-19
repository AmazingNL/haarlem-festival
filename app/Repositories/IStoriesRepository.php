<?php

declare(strict_types=1);

namespace App\Repositories;

/**
 * Data-access contract for stories show records.
 *
 * A "show" is a published page_section row of type stories_booking.
 */
interface IStoriesRepository
{
    /**
     * Fetch a published stories_booking section row by its section_id.
     *
     * @param  int        $id The section_id of the booking section.
     * @return array|null     Associative row with JSON content merged, or null if not found.
     */
    public function getShowById(int $id): ?array;

    /**
     * Fetch a published stories_booking section row by the slug stored in its JSON content.
     *
     * @param  string     $slug The slug field inside the JSON content column.
     * @return array|null       Associative row with JSON content merged, or null if not found.
     */
    public function getShowBySlug(string $slug): ?array;

    /**
     * Fetch all published stories_booking sections for a page.
     *
     * @return list<array<string, mixed>>
     */
    public function getPublishedShowsForPageId(int $pageId): array;
}
