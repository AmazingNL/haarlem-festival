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
}
