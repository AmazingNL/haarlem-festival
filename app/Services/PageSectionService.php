<?php
namespace App\Services;
use App\Models\PageSection;
use App\Models\Image;
use App\Repositories\IImageRepository;
use App\Repositories\IPageSectionRepository;

final class PageSectionService implements IPageSectionService
{
    private IPageSectionRepository $pageSectionRepository;
    private IImageRepository $imageRepository;
    public function __construct(IPageSectionRepository $pageSectionRepository, IImageRepository $imageRepository)
    {
        $this->pageSectionRepository = $pageSectionRepository;
        $this->imageRepository = $imageRepository;
    }

    public function getSectionsByPageId(int $pageId): array
    {
        return $this->pageSectionRepository->getSectionsByPageId($pageId);
    }

    public function getSectionById(int $sectionId): PageSection
    {
        return $this->pageSectionRepository->getSectionById($sectionId);
    }

    public function createSection(PageSection $section): bool
    {
        $section_id = $this->pageSectionRepository->createSection($section);
        if ($section_id > 0) {
            return true;
        }
        return false;
    }

    public function createSectionWithImage(PageSection $section, ?string $tmpImagePath): int
    {

        $this->pageSectionRepository->beginTransaction();

        try {
            if ($tmpImagePath) {
                $image = new Image(
                    image_id: null,
                    file_path: $tmpImagePath,
                    alt_text: null,
                    uploaded_by_user_id: (int) ($_SESSION['user_id'] ?? 0) ?: null,
                    created_at: null
                );

                $imageId = $this->imageRepository->saveImage($image);
                $section->image_id = $imageId;
            }

            $sectionId = $this->pageSectionRepository->createSection($section);

            $this->pageSectionRepository->commit();
            return $sectionId;

        } catch (\Throwable $e) {
            $this->pageSectionRepository->rollBack();

            // Optional: delete uploaded file if DB failed (prevents orphan files)
            if ($tmpImagePath) {
                $filePath = dirname(__DIR__, 2) . $tmpImagePath;
                if (is_file($filePath)) {
                    @unlink($filePath);
                }
            }

            throw $e;
        }
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