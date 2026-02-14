<?php
// src/Domain/Page.php

declare(strict_types=1);
namespace App\Models;
use App\Core\BaseEntity;
use App\Models\Enum\PageStatus;

final class Page extends BaseEntity
{

    public ?int $page_id = null;
    public string $title = '';
    public string $slug = '';
    public string $content = '';
    public int $is_published = 0;

    public ?string $created_at = null;
    public ?string $updated_at = null;
    public PageStatus $status = PageStatus::draft;


    public function __construct(
        $page_id = null,
        $title = '',
        $slug = '',
        $content = '',
        $is_published = 0,
        $created_at = null,
        $updated_at = null
    ) {
        $this->page_id = $page_id;
        $this->title = $title;
        $this->slug = $slug;
        $this->content = $content;
        $this->is_published = $is_published;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->status = PageStatus::draft;
    }
    public static function fromArray(array $row): static
    {
        $rawRole = $row['status'] ?? null;
        unset($row['status']);

        $p = parent::fromArray($row);

        if ($rawRole !== null) {
            $role = PageStatus::tryFrom((string) $rawRole);
            if ($role !== null)
                $p->status = $role;
        }

        return $p;

    }
}
