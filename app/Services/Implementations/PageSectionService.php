<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Services\Interfaces\IImageService;
use App\Services\Interfaces\IPageSectionService;
use App\DTO\SectionInputDTO;
use App\Models\Enum\SectionType;
use App\Models\PageSection;
use App\Repositories\IPageSectionRepository;
use App\Schemas\SectionFactory;
use Exception;

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

    public function createSection(SectionInputDTO $input): bool
    {
        $section = $this->buildSectionFromDto($input);
        $sectionId = $this->pageSectionRepository->createSection($section);
        return $sectionId > 0;
    }

    // Update an existing section from submitted form data (mirrors createSection).
    // The DTO's sectionId decides which row is updated.
    public function updateSectionFromDto(SectionInputDTO $input): bool
    {
        $section = $this->buildSectionFromDto($input);
        return $this->pageSectionRepository->updateSection($section);
    }

    // Update an existing section in the database.
    public function updateSection(PageSection $section): bool
    {
        return $this->pageSectionRepository->updateSection($section);
    }

    // Delete a section by id.
    public function deleteSection(int $sectionId): bool
    {
        return $this->pageSectionRepository->deleteSection($sectionId);
    }

    // Build the admin form field list for a given section type by using its Admin UI schemas.
    public function resolveSectionFormFields(string $sectionType): array
    {
        if ($sectionType === '') {
            throw new \InvalidArgumentException('Section type can not be empty');
        }

        // Comparing the input section type to the allowed section type
        // Throw an error if there is no match.
        $sectionType = SectionType::tryFrom($sectionType);
        if ($sectionType === null) {
            throw new \InvalidArgumentException('Section type not allowed');
        }

        // Use the section factory to return specific section class that is being called with type
        $sectionClass = SectionFactory::returnSectionClass($sectionType->value);
        if ($sectionClass === null) {
            throw new \InvalidArgumentException('No Section form for this type');
        }

        // Instantiate the class and get the form fields
        $sectionSchema = new $sectionClass;
        return $sectionSchema->getAdminFormFields();
    }


    // Using sectionDTO to build PageSection object and returning it.
    private function buildSectionFromDto(SectionInputDTO $input): PageSection
    {
        if ($input->pageId === 0) {
            throw new \InvalidArgumentException('Invalid PageId');
        }

        $sectionType = SectionType::tryFrom($input->sectionType);
        if ($sectionType === null) {
            throw new \InvalidArgumentException('Invalid section type.');
        }

        // add the section form field to a variable by using the function above
        $sectionField = $this->resolveSectionFormFields($input->sectionType);
        $content = $this->buildSectionContent($sectionField, $input->fields, $input->files);

        return new PageSection(
            $input->sectionId,
            $input->pageId,
            $sectionType,
            // encoding the content array into Json type.
            json_encode($content, JSON_UNESCAPED_UNICODE),
            $input->sortOrder,
            $input->isPublished
        );
    }

    // Build the JSON content for a section, including uploaded or embedded images.
    private function buildSectionContent(array $sectionField, array $fields, array $files): array
    {
        // Start with empty accumulators: $content is the field data, 
        // $galleryImages 
        // collects every image found so they can be combined into one gallery list.
        $content = [];
        $galleryImages = [];
        // True when "section_image" is a single uploaded background image (a string path),
        // so we never clobber it with the combined gallery array below.
        $hasSingleSectionImage = false;

        // Walk each field the schema declares: $fieldName is the key, $config its settings.
        foreach ($sectionField as $fieldName => $config) {
            $fieldType = (string) ($config['type'] ?? 'text');

            // A single uploaded image is stored as a string path by normalizeImageField().
            if ($fieldType === 'image') {
                $this->normalizeImageField($fieldName, $fields, $files, $content, $galleryImages);
                if ($fieldName === 'section_image') {
                    $hasSingleSectionImage = true;
                }
                continue;
            }

            $fieldValue = (string) ($fields[$fieldName] ?? '');

            // List fields (declared with 'multiple' in the schema) become normalized arrays.
            if (!empty($config['multiple'])) {
                $content[$fieldName] = $this->normalizeArrayValues($fieldValue);
                continue;
            }

            $content[$fieldName] = $fieldValue;

            // Collect any <img> embedded in HTML content for the combined section gallery.
            // extractImageObjects() returns [] for plain text, so this is safe for every field.
            if ($fieldValue !== '') {
                $galleryImages = array_merge($galleryImages, $this->extractImageObjects($fieldValue));
            }
        }

        // Build the combined gallery only when section_image is not a single background image,
        // so an uploaded background path is never overwritten with an array.
        if (!$hasSingleSectionImage && $galleryImages !== []) {
            $content['section_image'] = $this->uniqueSectionImages($galleryImages);
        }

        return $content;
    }

    private function extractImageObjects(string $html): array
    {
        if ($html === '') {
            return [];
        }

        $images = [];
        $previous = libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        // The <meta charset> tells libxml the bytes are UTF-8; without it loadHTML()
        // assumes ISO-8859-1 and corrupts non-ASCII alt/caption text (e.g. "Café", "Eén").
        $dom->loadHTML('<!DOCTYPE html><html><head><meta charset="utf-8"></head><body>' .
            $html . '</body></html>', LIBXML_NOERROR | LIBXML_NOWARNING);

        foreach ($dom->getElementsByTagName('img') as $img) {
            if (!($img instanceof \DOMElement)) {
                continue;
            }

            $src = trim($img->getAttribute('src'));
            if ($src === '' || str_starts_with($src, 'data:')) {
                continue;
            }

            $caption = '';
            $parent = $img->parentNode;
            if ($parent instanceof \DOMElement && strtolower($parent->tagName) === 'figure') {
                foreach ($parent->getElementsByTagName('figcaption') as $figcaption) {
                    $caption = trim($figcaption->textContent);
                    break;
                }
            }

            $images[] = [
                'src' => $src,
                'alt' => trim($img->getAttribute('alt')),
                'caption' => $caption,
            ];
        }

        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        return $images;
    }

    private function uniqueSectionImages(array $images): array
    {
        $unique = [];
        $seen = [];

        foreach ($images as $image) {
            if (is_array($image)) {
                $src = trim((string) ($image['src'] ?? ''));
                if ($src === '' || isset($seen[$src])) {
                    continue;
                }

                $seen[$src] = true;
                $unique[] = [
                    'src' => $src,
                    'alt' => trim((string) ($image['alt'] ?? '')),
                    'caption' => trim((string) ($image['caption'] ?? '')),
                ];
                continue;
            }

            $src = trim((string) $image);
            if ($src === '' || isset($seen[$src])) {
                continue;
            }

            $seen[$src] = true;
            $unique[] = $src;
        }

        return $unique;
    }

    private function normalizeImageField(string $fieldName, array $fields, array $files, array &$content, array &$sectionImages): void
    {
        $content[$fieldName] = (string) ($fields[$fieldName] ?? '');
        // On edit the file input is empty, so keep the existing image that the
        // form carries in "<field>_current" instead of wiping it.
        if ($content[$fieldName] === '') {
            $content[$fieldName] = (string) ($fields[$fieldName . '_current'] ?? '');
        }
        $content[$fieldName . '_alt_text'] = trim((string) ($fields[$fieldName . '_alt_text'] ?? ''));
        $content[$fieldName . '_caption'] = trim((string) ($fields[$fieldName . '_caption'] ?? ''));

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
            static fn(string $item): string => trim($item),
            $parts
        ))));
    }


}
