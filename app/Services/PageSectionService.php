<?php
namespace App\Services;
use App\Models\PageSection;
use App\Repositories\IPageSectionRepository;

final class PageSectionService implements IPageSectionService
{
    private IPageSectionRepository $pageSectionRepository;
    public function __construct(IPageSectionRepository $pageSectionRepository) {
        $this->pageSectionRepository = $pageSectionRepository;
    }

    public function getSectionsByPageId(int $pageId): array
    {
        return $this->pageSectionRepository->getSectionsByPageId($pageId);
    }

    public function getSectionById(int $sectionId): ?PageSection
    {
        return $this->pageSectionRepository->getSectionById($sectionId);
    }

    public function createSection(PageSection $section): int
    {
        return $this->pageSectionRepository->createSection($section);
    }

    public function updateSection(PageSection $section): bool
    {
        return $this->pageSectionRepository->updateSection($section);
    }

    public function deleteSection(int $sectionId): bool
    {
        return $this->pageSectionRepository->deleteSection($sectionId);
    }
}