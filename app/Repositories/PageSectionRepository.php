<?php
declare(strict_types=1);
namespace App\Repositories;
use App\Models\PageSection;
use App\Models\Enum\SectionType;
use App\Core\BaseRepository;

final class PageSectionRepository extends BaseRepository implements IPageSectionRepository
{
    private const TABLE = 'page_section';
    private const PK = 'section_id';

    public function __construct()
    {
        parent::__construct();
    }

    public function getSectionsByPageId(int $pageId): array
    {
        try {
            $sql = "SELECT ps.*, i.file_path AS image_path
                    FROM " . self::TABLE . " ps
                    LEFT JOIN image i ON i.image_id = ps.image_id
                    WHERE ps.page_id = ?
                    ORDER BY ps.sort_order ASC";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([$pageId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to retrieve page sections. ' . $e->getMessage());
        }
    }

    public function getSectionById(int $sectionId): ?PageSection
    {
        try {
            $sql = "SELECT *
                    FROM " . self::TABLE . "
                    WHERE " . self::PK . " = :section_id
                    LIMIT 1";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindValue(':section_id', $sectionId, \PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $row ? $this->hydrateSection($row) : null;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to retrieve page section. ' . $e->getMessage());
        }
    }

    public function createSection(PageSection $section): int
    {
        try {
            $sql = "INSERT INTO " . self::TABLE . "
                    (page_id, section_type, content, sort_order, is_published, created_at)
                    VALUES
                    (:page_id, :section_type, :content, :sort_order, :is_published, NOW())";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($this->sectionToDbArray($section));
            return (int) $this->getConnection()->lastInsertId();
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to create page section. ' . $e->getMessage());
        }
    }

    public function updateSection(PageSection $section): bool
    {
        try {
            $sql = "UPDATE " . self::TABLE . "
                    SET page_id = :page_id,
                        section_type = :section_type,
                        content = :content,
                        sort_order = :sort_order,
                        is_published = :is_published,
                        updated_at = NOW()
                    WHERE " . self::PK . " = :section_id";
            $stmt = $this->getConnection()->prepare($sql);
            return $stmt->execute(array_merge($this->sectionToDbArray($section), [':section_id' => $section->section_id]) );
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to update page section. ' . $e->getMessage());
        }
    }

    public function deleteSection(int $sectionId): bool
    {
        try {
            $sql = "DELETE FROM " . self::TABLE . "
                    WHERE " . self::PK . " = :section_id";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindValue(':section_id', $sectionId, \PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to delete page section. ' . $e->getMessage());
        }
    }

    private function sectionToDbArray(PageSection $section): array
    {
        return [
            ':page_id' => $section->page_id,
            ':section_type' => $section->section_type->value,
            ':content' => $section->content,
            ':sort_order' => $section->sort_order,
            ':is_published' => $section->is_published ? 1 : 0            
        ];
    }

    /** @param array<string,mixed> $row */
    private function hydrateSection(array $row): PageSection
    {
        $sectionType = SectionType::tryFrom((string) ($row['section_type'] ?? '')) ?? SectionType::TEXT_BLOCK;

        $section = new PageSection(
            isset($row['section_id']) ? (int) $row['section_id'] : 0,
            isset($row['page_id']) ? (int) $row['page_id'] : 0,
            $sectionType,
            isset($row['content']) ? (string) $row['content'] : null,
            isset($row['sort_order']) ? (int) $row['sort_order'] : 0,
            isset($row['is_published']) ? (bool) $row['is_published'] : true,
        );

        $section->created_at = isset($row['created_at']) ? (string) $row['created_at'] : null;
        $section->updated_at = isset($row['updated_at']) ? (string) $row['updated_at'] : null;

        return $section;
    }
}