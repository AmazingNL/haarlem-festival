<?php

namespace App\Repositories;

use App\Models\Page;
use App\Core\BaseRepository;
final class AdminPageRepository extends BaseRepository implements IAdminPageRepository
{
    private const TABLE = 'page';
    private const PK = 'page_id';
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllPages(): array
    {
        try {
            $sql = "SELECT *
            FROM " . self::TABLE;
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return array_map(fn($row) => Page::fromArray($row), $rows);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to retrieve pages. ' . $e->getMessage());
        }
    }

    public function getPageById(int $id): ?Page
    {
        try {
            $sql = "SELECT *
            FROM " . self::TABLE . "
            WHERE " . self::PK . " = :id
            LIMIT 1";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $row ? Page::fromArray($row[0]) : null;
        } 
        catch (\Exception $e) {
            throw new \RuntimeException('Failed to retrieve page. ' . $e->getMessage());
        }
    }

    public function createPage(array $pageData): int
    {
        try {
            // Build Page model from provided array
            $page = Page::fromArray($pageData);
            $sql = "INSERT INTO " . self::TABLE . "
                (title, slug, content, created_at, updated_at, status)
                VALUES
                (:title, :slug, :content, NOW(), NOW(), :status)";

            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($this->pageToDbArray($page));

            return (int) $this->getConnection()->lastInsertId();
        } 
        catch (\Exception $e) {
            throw new \RuntimeException('Failed to create page. ' . $e->getMessage());
        }

    }

    public function updatePage(int $id, array $pageData): bool
    {
        try {
            $existingPage = $this->getPageById($id);
            if ($existingPage === null) {
                throw new \InvalidArgumentException("Page with ID {$id} does not exist.");
            }
            // Build Page model from provided array and ensure id
            $page = Page::fromArray($pageData);
            $page->page_id = $id;
            $sql = "UPDATE " . self::TABLE . "
                    SET title = :title, slug = :slug, content = :content, updated_at = NOW(), status = :status
                    WHERE " . self::PK . " = :id";
            $stmt = $this->getConnection()->prepare($sql);
            $params = $this->pageToDbArray($page);
            $stmt->execute($params);
            return (bool) $stmt->rowCount();
        } 
        catch (\Exception $e) {
            throw new \RuntimeException('Failed to update page. ' . $e->getMessage());
        }
    }

    public function deletePage(int $id): bool
    {
        try {
            $existingPage = $this->getPageById($id);
            if ($existingPage === null) {
                throw new \InvalidArgumentException("Page with ID {$id} does not exist.");
            }
            $sql = "DELETE FROM " . self::TABLE . "
                WHERE " . self::PK . " = :id";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([':id' => $id]);
            return (bool) $stmt->rowCount();
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to delete page. ' . $e->getMessage());
        }
    }

    private function pageToDbArray(Page $page): array
    {
        $status = $page->status;
        $statusVal = is_object($status) && property_exists($status, 'value') ? $status->value : (string) $status;

        $result = [
            'title' => $page->title,
            'slug' => $page->slug,
            'content' => $page->content,
            'status' => $statusVal,
        ];

        // include id only when present (> 0) as it's required for updates but not inserts
        if (!empty($page->page_id) && (int)$page->page_id > 0) {
            $result['id'] = (int) $page->page_id;
        }

        return $result;
    }

}