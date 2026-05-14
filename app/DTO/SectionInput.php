<?php

declare(strict_types=1);

namespace App\DTO;

final class SectionInput
{
    public int $pageId;
    public int $sectionId;
    public string $sectionType;
    public int $sortOrder;
    public bool $isPublished;
    /** @var array<string, mixed> */
    public array $fields;
    /** @var array<string, array<string, mixed>> */
    public array $files;

    /**
     * @param array<string, mixed> $fields
     * @param array<string, array<string, mixed>> $files
     */
    public function __construct(
        int $pageId,
        int $sectionId,
        string $sectionType,
        int $sortOrder,
        bool $isPublished,
        array $fields = [],
        array $files = []
    ) {
        $this->pageId = $pageId;
        $this->sectionId = $sectionId;
        $this->sectionType = $sectionType;
        $this->sortOrder = $sortOrder;
        $this->isPublished = $isPublished;
        $this->fields = $fields;
        $this->files = $files;
    }
}
