<?php

namespace App\Services\Implementations;

use App\Services\Interfaces\ICmsService;

use App\DTO\PageData;
use App\Models\Page;
use App\Repositories\ICmsRepository;
use App\Models\Enum\PageStatus;
use RuntimeException;
use Throwable;

final class CmsService implements ICmsService
{
    private ICmsRepository $repository;

    // Inject the page repository used by the CMS page layer.
    public function __construct(ICmsRepository $repository)
    {
        $this->repository = $repository;
    }

    // Return every page from the CMS.
    public function getAllPages(): array
    {
        try {
            return $this->repository->getAllPages();
        } catch (Throwable $e) {
            throw new RuntimeException($e);
        }
    }

    // Return one page by id or null if the id is invalid.
    public function getPageById(int $id): ?Page
    {
        if ($id === null || $id <= 0) {
            return null;
        }
        return $this->repository->getPageById($id);
    }

    // Create a new CMS page.
    public function createPage(PageData $pageData): int
    {
        return $this->repository->createPage($pageData);
    }

    // Update an existing CMS page.
    public function updatePage(int $id, PageData $pageData): bool
    {
        return $this->repository->updatePage($id, $pageData);
    }

    // Delete a CMS page.
    public function deletePage(int $id): bool
    {
        return $this->repository->deletePage($id);
    }

    // Return only the pages that are marked as published.
    public function getPublishedPages(): array
    {
        $pages = $this->repository->getAllPages();
        return array_values(array_filter(
            $pages,
            fn($p) => (((isset($p->status) && $p->status instanceof PageStatus)
                ? $p->status->value
                : (string) ($p->status ?? '')) === PageStatus::published->value)
        ));
    }

    // Find one page by its slug, which is how the History controller loads pages.
    public function getPageBySlug(string $slug): ?Page
    {
        $pages = $this->repository->getAllPages();
        foreach ($pages as $page) {
            if ($page->slug === $slug) {
                return $page;
            }
        }
        return null;
    }


}
