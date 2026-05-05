<?php
namespace App\Services;

use App\DTO\SectionInput;
use App\Models\Enum\SectionType;
use App\Models\PageSection;
use App\Repositories\IPageSectionRepository;
use App\ViewModels\SectionFactory;

final class PageSectionService implements IPageSectionService
{
    private IPageSectionRepository $pageSectionRepository;
    private IImageService $imageService;

    // Inject the section repository and image helper used by the CMS.
    public function __construct(IPageSectionRepository $pageSectionRepository, IImageService $imageService)
    {
        $this->pageSectionRepository = $pageSectionRepository;
        $this->imageService = $imageService;
    }

    // Return all sections that belong to one page.
    public function getSectionsByPageId(int $pageId): array
    {
        $sections = $this->pageSectionRepository->getSectionsByPageId($pageId);

        // Flatten JSON content into top-level fields so views can read section data directly.
        return array_map(
            static function (array $section): array {
                $content = json_decode((string) ($section['content'] ?? ''), true);
                if (is_array($content)) {
                    $section = array_merge($section, $content);
                }
                return $section;
            },
            $sections
        );
    }

    // Return one page section by its id.
    public function getSectionById(int $sectionId): ?PageSection
    {
        return $this->pageSectionRepository->getSectionById($sectionId);
    }

    // Build the admin form field list for a given section type by using its ViewModel.
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

    public function buildSectionFromDto(SectionInput $input): PageSection
    {
        $pageId = $input->pageId;
        if ($pageId === 0) {
            throw new \InvalidArgumentException('Invalid PageId');
        }

        $sectionId = $input->sectionId;
        $sectionTypeRaw = $input->sectionType;
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
        $content = $this->buildSectionContent($sectionField, $input->fields, $input->files);

        return new PageSection(
            $sectionId,
            $pageId,
            $sectionType,
            json_encode($content, JSON_UNESCAPED_UNICODE),
            $input->sortOrder,
            $input->isPublished
        );
    }

    public function buildSectionFromInput(array $post, array $files): PageSection
    {
        $input = new SectionInput(
            (int) ($post['page_id'] ?? 0),
            (int) ($post['section_id'] ?? 0),
            (string) ($post['section_type'] ?? ''),
            (int) ($post['sort_order'] ?? 0),
            ((int) ($post['is_published'] ?? 0)) === 1,
            $post,
            $files
        );

        return $this->buildSectionFromDto($input);
    }

    // Build the JSON content for a section, including uploaded or embedded images.
    private function buildSectionContent(array $sectionField, array $post, array $files): array
    {
        $content = [];
        $sectionImages = [];

        foreach ($sectionField as $fieldName => $config) {
            $fieldType = (string) ($config['type'] ?? 'text');

            if ($fieldType === 'image') {
                $this->normalizeImageField($fieldName, $post, $files, $content, $sectionImages);
                continue;
            }

            $fieldValue = (string) ($post[$fieldName] ?? '');

            if ($fieldName === 'cuisine' || $fieldName === 'session' || $fieldName === 'date') {
                $content[$fieldName] = $this->normalizeArrayValues($fieldValue);
                continue;
            }

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

    private function normalizeImageField(string $fieldName, array $post, array $files, array &$content, array &$sectionImages): void
    {
        $content[$fieldName] = (string) ($post[$fieldName] ?? '');
        $content[$fieldName . '_alt_text'] = trim((string) ($post[$fieldName . '_alt_text'] ?? ''));
        $content[$fieldName . '_caption'] = trim((string) ($post[$fieldName . '_caption'] ?? ''));

        if (!empty($files[$fieldName]) && (($files[$fieldName]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK)) {
            $filePath = $this->imageService->storeUploadedImage($files[$fieldName]);
            $content[$fieldName] = $filePath;
            $sectionImages[] = $filePath;
        }
    }

    private function normalizeArrayValues(string $fieldValue): array
    {
        $parts = preg_split('/[\r\n,]+/', $fieldValue) ?: [];

        return array_values(array_unique(array_filter(array_map(
            static fn (string $item): string => trim($item),
            $parts
        ))));
    }

    // Save a new section in the database.
    public function createSection(PageSection $section): bool
    {
        $sectionId = $this->pageSectionRepository->createSection($section);
        return $sectionId > 0;
    }

    // Update an existing section in the database.
    public function updateSection(PageSection $section): bool
    {
        return $this->pageSectionRepository->updateSection($section);
    }

    // Delete a section by id.
    public function deleteSection(int $sectionId): bool
    {
        try {
            return $this->pageSectionRepository->deleteSection($sectionId);
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
