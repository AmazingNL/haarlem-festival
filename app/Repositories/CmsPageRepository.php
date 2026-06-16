<?php

namespace App\Repositories;
use App\Models\Page;
use App\Models\Enum\PageStatus;
use App\Core\BaseRepository;
final class CmsPageRepository extends BaseRepository implements ICmsPageRepository
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
            return array_map(fn($row) => $this->hydratePage($row), $rows);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to retrieve pages.');
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
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $row ? $this->hydratePage($row) : null;
        } 
        catch (\Exception $e) {
            throw new \RuntimeException('Failed to retrieve page.');
        }
    }

    public function createPage(array $pageData): int
    {
        try {
            $page = $this->hydratePage($pageData);
            $sql = "INSERT INTO " . self::TABLE . "
                (title, slug, created_at, updated_at, status)
                VALUES
                (:title, :slug, NOW(), NOW(), :status)";

            $stmt = $this->getConnection()->prepare($sql);
            $pageData = $this->pageToDbArray($page);
            $stmt->execute($pageData);

            return (int) $this->getConnection()->lastInsertId();
        } 
        catch (\Exception $e) {
            throw new \RuntimeException('Failed to create page.');
        }

    }

    public function updatePage(int $id, array $pageData): bool
    {
        try {
            $existingPage = $this->getPageById($id);
            if ($existingPage === null) {
                throw new \InvalidArgumentException("Page with ID {$id} does not exist.");
            }
            $page = $this->hydratePage($pageData);
            $page->page_id = $id;
            $sql = "UPDATE " . self::TABLE . "
                    SET title = :title, slug = :slug, updated_at = NOW(), status = :status
                    WHERE " . self::PK . " = :id";
            $stmt = $this->getConnection()->prepare($sql);
            $params = $this->pageToDbArray($page);
            $params['id'] = $id; 
            $stmt->execute($params);
            return (bool) $stmt->rowCount();
        } 
        catch (\Exception $e) {
            throw new \RuntimeException('Failed to update page.');
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
            throw new \RuntimeException('Failed to delete page.');
        }
    }

    private function pageToDbArray(Page $page): array
    {
        return [
            'title' => $page->title,
            'slug' => $page->slug,
            'status' => $page->status->value
        ];
    }

    private function hydratePage(array $data): Page
    {
        $statusRaw = $data['status'] ?? PageStatus::draft;
        $status = $statusRaw instanceof PageStatus
            ? $statusRaw
            : PageStatus::draft;

        return new Page(
            isset($data['page_id']) ? (int) $data['page_id'] : null,
            (string) ($data['title'] ?? ''),
            (string) ($data['slug'] ?? ''),
            (string) ($data['content'] ?? ''),
            $data['created_at'] ?? null,
            $data['updated_at'] ?? null,
            $status
        );
    }

}