<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseEntity;
use DateTime;

final class PageSection extends BaseEntity
{
    public int $section_id = 0;
    public int $page_id = 0;
    public string $section_type = '';
    public ?string $title = null;
    public ?string $content = null;
    public ?int $image_id = null;
    public ?string $button_text = null;
    public ?string $button_link = null;
    public int $sort_order = 0;
    public bool $is_published = true;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    public static function fromArray(array $row): static
    {
        $obj = parent::fromArray($row);

        // Normalize types
        $obj->section_id = isset($row['section_id']) ? (int)$row['section_id'] : ($obj->section_id ?? 0);
        $obj->page_id = isset($row['page_id']) ? (int)$row['page_id'] : ($obj->page_id ?? 0);
        $obj->image_id = isset($row['image_id']) && $row['image_id'] !== null ? (int)$row['image_id'] : null;
        $obj->sort_order = isset($row['sort_order']) ? (int)$row['sort_order'] : ($obj->sort_order ?? 0);
        $obj->is_published = isset($row['is_published']) ? (bool)$row['is_published'] : ($obj->is_published ?? true);

        return $obj;
    }
}
