<?php
namespace App\Services\Interfaces;
use App\DTO\SectionInputDTO;
use App\Models\PageSection;
interface IPageSectionService
{
    public function getSectionsByPageId(int $pageId): array;
    public function getSectionById(int $sectionId): ?PageSection;
    public function resolveSectionFormFields(string $sectionType): array;
    // public function buildSectionFromDto(SectionInputDTO $input): PageSection;
    public function createSection(SectionInputDTO $section): bool;
    public function updateSection(PageSection $section): bool;
    public function deleteSection(int $sectionId): bool;
}