<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

/**
 * Business-logic contract for the stories feature.
 */
interface IStoriesService
{
    /**
     * Return all published sections for the stories page identified by $slug,
     * with each section's JSON content merged into the array.
     *
     * @param  string $slug The page slug (e.g. 'stories').
     * @return array        Array of merged section arrays, ordered by sort_order.
     * @throws \RuntimeException If the page or sections cannot be loaded.
     */
    public function getPageSections(string $slug): array;

    /**
     * Return the data for a single bookable show by its section_id.
     *
     * @param  int        $id The section_id of the stories_booking section.
     * @return array|null     Merged show data, or null if not found.
     * @throws \RuntimeException On data-access failure.
     */
    public function getShowById(int $id): ?array;

    /**
     * Return the data for a single bookable show by its URL slug.
     *
     * @param  string     $slug The slug stored in the JSON content field.
     * @return array|null       Merged show data, or null if not found.
     * @throws \RuntimeException On data-access failure.
     */
    public function getShowBySlug(string $slug): ?array;
}
