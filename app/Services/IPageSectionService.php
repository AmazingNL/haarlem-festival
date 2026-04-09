<?php
namespace App\Services;
use App\Models\PageSection;
use InternalIterator;
interface IPageSectionService
{
    public function getSectionsByPageId(int $pageId): array;
    public function getSectionById(int $sectionId): ?PageSection;
    public function resolveSectionFormFields(string $sectionType): array;
    public function buildSectionFromInput(int $fallbackPageId, array $post, array $files): PageSection;
    public function createSection(PageSection $section): bool;
    public function updateSection(PageSection $section): bool;
    public function deleteSection(int $sectionId): bool;
}