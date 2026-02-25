<?php
namespace App\Services;
use App\Models\PageSection;
use InternalIterator;
interface IPageSectionService
{
    public function getSectionsByPageId(int $pageId): array;
    public function getSectionById(int $sectionId): ?PageSection;
    public function createSection(PageSection $section): bool;
    public function updateSection(PageSection $section): bool;
    public function deleteSection(int $sectionId): bool;
    public function createSectionWithImage(PageSection $section, ?string $publicUrl): int;
}