<?php
namespace App\Services;
use App\DTO\SectionInput;
use App\Models\PageSection;
use InternalIterator;
interface IPageSectionService
{
    public function getSectionsByPageId(int $pageId): array;
    public function getSectionById(int $sectionId): ?PageSection;
    public function resolveSectionFormFields(string $sectionType): array;
    public function buildSectionFromDto(SectionInput $input): PageSection;
    public function buildSectionFromInput(array $post, array $files): PageSection;
    public function createSection(PageSection $section): bool;
    public function updateSection(PageSection $section): bool;
    public function deleteSection(int $sectionId): bool;
}