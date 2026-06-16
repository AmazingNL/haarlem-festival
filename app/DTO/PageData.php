<?php

declare(strict_types=1);

namespace App\DTO;

use App\Models\Enum\PageStatus;

final class PageData
{
    public string $title;
    public string $slug;
    public string $content;
    public PageStatus $status;

    public function __construct(
        string $title,
        string $slug,
        string $content = '',
        PageStatus $status = PageStatus::draft
    ) {
        $this->title = $title;
        $this->slug = $slug;
        $this->content = $content;
        $this->status = $status;
    }
}
