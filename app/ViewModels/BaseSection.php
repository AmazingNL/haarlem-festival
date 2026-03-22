<?php

namespace App\ViewModels;

abstract class BaseSection
{
    public int $page_id;
    public int $section_id;
    public string $type;
    public string $customClass = '';
    public int $sortOrder = 0;

    public function __construct(
        int $pageId,
        int $sectionId,
        string $type,
        string $customClass = '',
        int $sortOrder = 0
    ) {
        $this->page_id = $pageId;
        $this->section_id = $sectionId;
        $this->type = $type;
        $this->customClass = $customClass;
        $this->sortOrder = $sortOrder;
    }
}