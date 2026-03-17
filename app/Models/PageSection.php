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
    public ?string $title = null;
    public ?string $content = null;
    public ?int $image_id = null;
    public ?string $button_text = null;
    public ?string $button_link = null;
    public int $sort_order = 0;
    public bool $is_published = true;
    public ?string $settings_json = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;


    public function __construct(
        $section_id = 0,
        $page_id = 0,
        SectionType $section_type = SectionType::TEXT_BLOCK,
        $title = null,
        $content = null,
        $image_id = null,
        $button_text = null,
        $button_link = null,
        $sort_order = 0,
        $is_published = true,
        $settings_json = ''
    ) {
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
        $this->settings_json = $settings_json;
        $this->created_at = (new DateTime())->format('Y-m-d H:i:s');
        $this->updated_at = (new DateTime())->format('Y-m-d H:i:s');
    }


}
