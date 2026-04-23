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

    public function __construct(IPageSectionRepository $pageSectionRepository, IImageService $imageService)
    {
        $this->pageSectionRepository = $pageSectionRepository;
        $this->imageService = $imageService;
    }

    public function getSectionsByPageId(int $pageId): array
    {
        $sections = $this->pageSectionRepository->getSectionsByPageId($pageId);

        // Flatten JSON content so views can access section fields directly.
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

    public function getSectionById(int $sectionId): PageSection
    {
        return $this->pageSectionRepository->getSectionById($sectionId);
    }


    // Validate the section type and resolve admin form fields via SectionFactory.
    public function resolveSectionFormFields(string $sectionType): array
    {
        // Reject empty section types before doing any lookup.
        if ($sectionType === '') {
            throw new \InvalidArgumentException('Section type can not be null');
        }

        // Only allow known enum values.
        $allowedTypes = array_map(fn($case) => $case->value, SectionType::cases());
        if (!in_array($sectionType, $allowedTypes, true)) {
            throw new \InvalidArgumentException('Section type can not allowed');
        }

        // Resolve the view model that defines the admin form fields.
        $sectionClass = SectionFactory::returnSectionClass($sectionType);
        if ($sectionClass === null) {
            throw new \InvalidArgumentException('No Section form for this type');
        }

        $sectionVm = new $sectionClass;
        return $sectionVm->getAdminFormFields();
    }

    public function buildSectionFromDto(SectionInput $input): PageSection
    {
        // Read and validate the page id from the submitted form.
        $pageId = $input->pageId;
        if ($pageId === 0) {
            throw new \InvalidArgumentException('Invalid PageId');
        }

        // Normalize the section id and type before building the model.
        $sectionId = $input->sectionId;
        $sectionTypeRaw = $input->sectionType;
        $sectionType = SectionType::tryFrom($sectionTypeRaw);
        if ($sectionType === null) {
            throw new \InvalidArgumentException('Invalid section type.');
        }

        // Resolve the section-specific form definition.
        $sectionClass = SectionFactory::returnSectionClass($sectionTypeRaw);
        if ($sectionClass === null) {
            throw new \InvalidArgumentException('unsupported section type');
        }

        // Build the JSON content payload from the submitted form values.
        $sectionVm = new $sectionClass;
        $sectionField = $sectionVm->getAdminFormFields();
        $content = $this->buildSectionContent($sectionField, $input->fields, $input->files);

        // Return the hydrated domain model ready for persistence.
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

    private function buildSectionContent(array $sectionField, array $post, array $files): array
    {
        $content = [];
        $sectionImages = [];

        foreach ($sectionField as $fieldName => $config) {
            // Image fields may come from an upload or an existing path.
            $fieldType = (string) ($config['type'] ?? 'text');

            if ($fieldType === 'image') {
                $this->normalizeImageField($fieldName, $post, $files, $content, $sectionImages);
                continue;
            }
            // Read normal text fields as strings.
            $fieldValue = (string) ($post[$fieldName] ?? '');

            // Split cuisine input into a clean list.
            if ($fieldName === 'cuisine') {
                $content[$fieldName] = $this->normalizeCuisineValues($fieldValue);
                continue;
            }
            // Keep the raw text value and collect any image URLs inside it.
            $content[$fieldName] = $fieldValue;

            if ($fieldValue !== '') {
                $sectionImages = array_merge($sectionImages, $this->imageService->extractUrls($fieldValue));
            }
        }
        // Store extracted image URLs in a dedicated content field.
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

    private function normalizeCuisineValues(string $fieldValue): array
    {
        $parts = preg_split('/[\r\n,]+/', $fieldValue) ?: [];

        return array_values(array_unique(array_filter(array_map(
            static fn (string $item): string => trim($item),
            $parts
        ))));
    }

    public function createSection(PageSection $section): bool
    {

        // Persist the new section and convert the inserted id into a success flag.
        $section_id = $this->pageSectionRepository->createSection($section);
        if ($section_id > 0) {
            return true;
        }
        return false;
    }
    public function updateSection(PageSection $section): bool
    {
        // Update the existing section directly through the repository.
        return $this->pageSectionRepository->updateSection($section);
    }

    public function deleteSection(int $sectionId): bool
    {
        try {
            // Delete only when a valid id is provided.
            if ($sectionId !== null) {
                return $this->pageSectionRepository->deleteSection($sectionId);
            }
        }
        catch(\Throwable $e){
            throw $e;
        }

    }
}