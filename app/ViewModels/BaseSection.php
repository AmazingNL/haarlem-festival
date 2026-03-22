<?php

namespace App\ViewModels;

abstract class BaseSection
{
    public int $page_id;
    public int $section_id;
    public string $type;
    public string $customClass = '';
    public int $sortOrder = 0;

}