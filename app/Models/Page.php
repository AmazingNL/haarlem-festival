<?php
// src/Domain/Page.php

declare(strict_types=1);
namespace App\Models;
use App\Core\BaseEntity;
use App\Models\Enum\PageStatus;

final class Page 
{

    public ?int $page_id = null;
    public string $title = '';
    public string $slug = '';
    public ?string $content = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;
    public PageStatus $status = PageStatus::draft;


    public function __construct(
        $page_id = null,
        $title = '',
        $slug = '',
        $content = null,
        $created_at = null,
        $updated_at = null,
        $status = PageStatus::draft
    ) {
        $this->page_id = $page_id;
        $this->title = $title;
        $this->slug = $slug;
        $this->content = $content;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->status = $status;
    }

}
