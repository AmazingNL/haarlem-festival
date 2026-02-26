<?php
declare(strict_types=1);

namespace App\Repositories;
use App\Core\BaseRepository;
use App\Models\Image;

final class ImageRepository extends BaseRepository implements IImageRepository
{

    private const TABLE = 'image';
    private const PK = 'image_id';
    public function __construct()
    {
        parent::__construct();
    }

public function saveImage(Image $image): int
{
    $sql = "INSERT INTO image (file_path, alt_text, uploaded_by_user_id, created_at)
            VALUES (:file_path, :alt_text, :uploaded_by_user_id, :created_at)";
    $stmt = $this->getConnection()->prepare($sql);

    $stmt->execute([
        ':file_path' => $image->file_path,
        ':alt_text' => $image->alt_text,
        ':uploaded_by_user_id' => $image->uploaded_by_user_id,
        ':created_at' => $image->created_at,
    ]);

    return (int)$this->getConnection()->lastInsertId();
}

    public function getImageById($image_id): Image{
        try {
            $sql = "SELECT *
                    FROM " . self::TABLE . "
                    WHERE " . self::PK . " = :image_id
                    LIMIT 1";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindValue(':section_id', $image_id, \PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $row ? Image::fromArray($row) : null;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to retrieve page section. ' . $e->getMessage());
        }
    }

    public function getAllImage(): array{
                try {
        $sql = "SELECT * FROM " . self::TABLE . " ORDER BY created_at DESC";
        $stmt = $this->getConnection()->query($sql);

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map(fn($row) => Image::fromArray($row), $rows);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to retrieve image. ' . $e->getMessage());
        }
    }
    private function sectionToDbArray(Image $image): array {
        return [
            ':image_id' => $image->image_id,
            ':file_path' => $image->file_path,
            ':alt_text' => $image->alt_text,
            ':uploaded_by_user_id' => $image->uploaded_by_user_id,
            ':created_at' => $image->created_at
        ];
    }
}