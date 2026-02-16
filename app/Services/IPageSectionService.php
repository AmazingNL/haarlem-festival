<?php
namespace App\Services;
use App\Models\PageSection;
interface IPageSectionService
{
    public function getSectionsByPageId(int $pageId): array;
    public function getSectionById(int $sectionId): ?PageSection;
    public function createSection(PageSection $section): int;
    public function updateSection(PageSection $section): bool;
    public function deleteSection(int $sectionId): bool;
}