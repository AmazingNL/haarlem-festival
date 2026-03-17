<?php
declare(strict_types=1);
namespace App\Repositories;
use App\Models\PageSection;
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
            $sql = "SELECT ps.*,
                       i.file_path AS image_path,
                       i.alt_text AS image_alt
                FROM " . self::TABLE . " ps
                LEFT JOIN image i ON ps.image_id = i.image_id
                WHERE ps.page_id = :page_id
                  AND ps.is_published = 1
                ORDER BY ps.sort_order ASC";

            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindValue(':page_id', $pageId, \PDO::PARAM_INT);
            $stmt->execute();

            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($rows as &$row) {
                if (($row['section_type'] ?? '') === 'two_image_row') {
                    $gallerySql = "SELECT i.file_path, i.alt_text
                               FROM page_section_image psi
                               INNER JOIN image i ON psi.image_id = i.image_id
                               WHERE psi.section_id = :section_id
                               ORDER BY psi.sort_order ASC";

                    $galleryStmt = $this->getConnection()->prepare($gallerySql);
                    $galleryStmt->bindValue(':section_id', (int)$row['section_id'], \PDO::PARAM_INT);
                    $galleryStmt->execute();

                    $row['gallery_images'] = $galleryStmt->fetchAll(\PDO::FETCH_ASSOC);
                }
            }

            return $rows;
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
            return $row ? PageSection::fromArray($row) : null;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to retrieve page section. ' . $e->getMessage());
        }
    }

    public function createSection(PageSection $section): int
    {
        try {
            $sql = "INSERT INTO " . self::TABLE . "
                    (page_id, section_type, title, content, image_id, button_text, button_link, sort_order, is_published)
                    VALUES
                    (:page_id, :section_type, :title, :content, :image_id, :button_text, :button_link, :sort_order, :is_published)";
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
                        title = :title,
                        content = :content,
                        image_id = :image_id,
                        button_text = :button_text,
                        button_link = :button_link,
                        sort_order = :sort_order,
                        is_published = :is_published
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
            ':section_type' => $section->section_type,
            ':title' => $section->title,
            ':content' => $section->content,
            ':image_id' => $section->image_id,
            ':button_text' => $section->button_text,
            ':button_link' => $section->button_link,
            ':sort_order' => $section->sort_order,
            ':is_published' => $section->is_published ? 1 : 0,
        ];
    }
}