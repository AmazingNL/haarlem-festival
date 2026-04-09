<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseEntity;
use App\Models\Enum\SectionType;
use DateTime;

final class PageSection
{
    public int $section_id = 0;
    public int $page_id = 0;
    public SectionType $section_type = SectionType::TEXT_BLOCK;
    public int $sort_order = 0;
    public bool $is_published = true;
    public ?string $content = '';
    public ?string $created_at = null;
    public ?string $updated_at = null;


    public function __construct(
        $section_id = 0,
        $page_id = 0,
        SectionType $section_type = SectionType::TEXT_BLOCK,
        $content = '',
        $sort_order = 0,
        $is_published = true
    ) {
        $this->section_id = $section_id;
        $this->page_id = $page_id;
        $this->section_type = $section_type;
        $this->content = $content;
        $this->sort_order = $sort_order;
        $this->is_published = $is_published;
        $this->created_at = (new DateTime())->format('Y-m-d H:i:s');
        $this->updated_at = (new DateTime())->format('Y-m-d H:i:s');
    }


}
