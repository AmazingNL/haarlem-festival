<?php

declare(strict_types=1);
namespace App\Repositories;
use App\Models\PageSection;

interface IPageSectionRepository
{
    public function getSectionsByPageId(int $pageId): array;
    public function getSectionById(int $sectionId): ?PageSection;
    public function createSection(PageSection $section): int;
    public function updateSection(PageSection $section): bool;
    public function deleteSection(int $sectionId): bool;
}