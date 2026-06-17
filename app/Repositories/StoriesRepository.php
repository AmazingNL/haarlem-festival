<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\BaseRepository;

/**
 * Database access for stories show records.
 *
 * A show is a published page_section row with section_type = 'stories_booking'.
 * The section_id serves as the show identifier passed in booking forms.
 */
final class StoriesRepository extends BaseRepository implements IStoriesRepository
{
    private const TABLE = 'page_section';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Fetch a published stories_booking section row by its section_id.
     *
     * @param  int        $id The section_id to look up.
     * @return array|null     Merged row data, or null if not found / not published.
     * @throws \RuntimeException On PDO failure.
     */
    public function getShowById(int $id): ?array
    {
        try {
            $stmt = $this->getConnection()->prepare(
                'SELECT * FROM ' . self::TABLE . ' WHERE section_id = ? AND section_type = ? AND is_published = 1 LIMIT 1'
            );
            $stmt->execute([$id, 'stories_booking']);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $row !== false ? $this->mergeJsonContent($row) : null;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to retrieve show. ' . $e->getMessage());
        }
    }

    /**
     * Decode and merge the JSON content column into the row array.
     *
     * @param  array $row Raw PDO row.
     * @return array      Row with content fields merged at the top level.
     */
    private function mergeJsonContent(array $row): array
    {
        $content = json_decode((string) ($row['content'] ?? ''), true);
        return is_array($content) ? array_merge($row, $content) : $row;
    }
}
