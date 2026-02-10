<?php
// src/Domain/Page.php

declare(strict_types=1);
namespace App\Models;
use App\Core\BaseEntity;

final class Page extends BaseEntity
{
    public ?int $page_id = null;
    public string $title = '';
    public string $slug = '';
    public string $content = '';
    public int $is_published = 0;

    public ?string $created_at = null;
    public ?string $updated_at = null;
}
