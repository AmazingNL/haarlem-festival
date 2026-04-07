<?php
namespace App\Services;
use App\Models\Enum\SectionType;
use App\Models\PageSection;
use App\Repositories\IPageSectionRepository;
use App\ViewModels\SectionFactory;
use ErrorException;

final class PageSectionService implements IPageSectionService
{
    private IPageSectionRepository $pageSectionRepository;
    private IImageService $imageService;

    public function __construct(IPageSectionRepository $pageSectionRepository, IImageService $imageService)
    {
        $this->pageSectionRepository = $pageSectionRepository;
        $this->imageService = $imageService;
    }

    public function getSectionsByPageId(int $pageId): array
    {
        return $this->pageSectionRepository->getSectionsByPageId($pageId);
    }

    public function getSectionById(int $sectionId): PageSection
    {
        return $this->pageSectionRepository->getSectionById($sectionId);
    }

    public function resolveSectionFormFields(string $sectionType): array
    {
        if ($sectionType === '') {
            throw new \InvalidArgumentException('Section type can not be null');
        }

        $allowedTypes = array_map(fn($case) => $case->value, SectionType::cases());
        if (!in_array($sectionType, $allowedTypes, true)) {
            throw new \InvalidArgumentException('Section type can not allowed');
        }

        $sectionClass = SectionFactory::returnSectionClass($sectionType);
        if ($sectionClass === null) {
            throw new \InvalidArgumentException('No Section form for this type');
        }

        $sectionVm = new $sectionClass;
        return $sectionVm->getAdminFormFields();
    }

    public function buildSectionFromInput(int $fallbackPageId, array $post, array $files): PageSection
    {
        $postedPageId = (int) ($post['page_id'] ?? 0);
        $pageId = $postedPageId > 0 ? $postedPageId : $fallbackPageId;

        $sectionId = (int) ($post['section_id'] ?? 0);
        $sectionTypeRaw = (string) ($post['section_type'] ?? '');
        $sectionType = SectionType::tryFrom($sectionTypeRaw);
        if ($sectionType === null) {
            throw new \InvalidArgumentException('Invalid section type.');
        }

        $sectionClass = SectionFactory::returnSectionClass($sectionTypeRaw);
        if ($sectionClass === null) {
            throw new \InvalidArgumentException('unsupported section type');
        }

        $sectionVm = new $sectionClass;
        $sectionField = $sectionVm->getAdminFormFields();
        $content = $this->buildSectionContent($sectionField, $post, $files);

        $sortOrder = (int) ($post['sort_order'] ?? 0);
        $isPublished = ((int) ($post['is_published'] ?? 0)) === 1;

        return new PageSection(
            $sectionId,
            $pageId,
            $sectionType,
            json_encode($content, JSON_UNESCAPED_UNICODE),
            $sortOrder,
            $isPublished
        );
    }

    private function buildSectionContent(array $sectionField, array $post, array $files): array
    {
        $content = [];
        $sectionImages = [];

        foreach ($sectionField as $fieldName => $config) {
            $fieldType = (string) ($config['type'] ?? 'text');

            if ($fieldType === 'image') {
                $content[$fieldName] = (string) ($post[$fieldName] ?? '');

                if (!empty($files[$fieldName]) && (($files[$fieldName]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK)) {
                    $filePath = $this->imageService->storeUploadedImage($files[$fieldName], [
                        'folder' => (string) ($config['folder'] ?? 'admin'),
                        'prefix' => (string) ($config['prefix'] ?? $fieldName),
                    ]);
                    $content[$fieldName] = $filePath;
                    $sectionImages[] = $filePath;
                }
                continue;
            }

            $fieldValue = (string) ($post[$fieldName] ?? '');
            $content[$fieldName] = $fieldValue;

            if ($fieldValue !== '') {
                try {
                    $sectionImages = array_merge($sectionImages, $this->imageService->extractUrls($fieldValue));
                } catch (\Throwable $e) {
                    // Plain text content does not contain embedded images.
                }
            }
        }

        if ($sectionImages !== []) {
            $content['section_image'] = array_values(array_unique($sectionImages));
        }

        return $content;
    }

    public function createSection(PageSection $section): bool
    {

        $section_id = $this->pageSectionRepository->createSection($section);
        if ($section_id > 0) {
            return true;
        }
        return false;
    }
    public function updateSection(PageSection $section): bool
    {
        return $this->pageSectionRepository->updateSection($section);
    }

    public function deleteSection(int $sectionId): bool
    {
        try {
            if ($sectionId !== null) {
                return $this->pageSectionRepository->deleteSection($sectionId);
            }
        }
        catch(\Throwable $e){
            throw $e;
        }

    }
}
