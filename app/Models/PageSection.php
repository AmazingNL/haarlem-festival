<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseEntity;
use DateTime;
use App\Enums\SectionType;

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


    public function __construct($section_id = 0, $page_id = 0, $section_type = '', $title = null, $content = null, $image_id = null, $button_text = null, $button_link = null, $sort_order = 0, $is_published = true)
    {
        $this->section_id = $section_id;
        $this->page_id = $page_id;
        $this->section_type = $section_type;
        $this->title = $title;
        $this->content = $content;
        $this->image_id = $image_id;
        $this->button_text = $button_text;
        $this->button_link = $button_link;
        $this->sort_order = $sort_order;
        $this->is_published = $is_published;
        $this->created_at = (new DateTime())->format('Y-m-d H:i:s');
        $this->updated_at = (new DateTime())->format('Y-m-d H:i:s');
    }

    public function getContent(): string
    {
        return $this->content ?? '';
    }
    public function getTitle(): string
    {
        return $this->title ?? '';
    }
    public function getSectionType(): string
    {
        return $this->section_type;
    }

    public function getImageId(): ?int
    {
        return $this->image_id;
    }

    public function getButtonText(): ?string
    {
        return $this->button_text;
    }
    public function getButtonLink(): ?string
    {
        return $this->button_link;
    }
    public function getIsPublished(): bool
    {
        return $this->is_published;
    }
    public function getSortOrder(): int
    {
        return $this->sort_order;
    }
    public function getPageId(): int
    {
        return $this->page_id;
    }

    public function getSectionId(): int
    {
        return $this->section_id;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }
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
