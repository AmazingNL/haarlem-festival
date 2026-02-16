<?php

namespace App\Services;

use App\Models\Page;
use App\Repositories\IAdminPageRepository;
use App\Models\Enum\PageStatus;

final class AdminPageService implements IAdminPageService
{
    private IAdminPageRepository $repository;

    public function __construct(IAdminPageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllPages(): array
    {
        return $this->repository->getAllPages();
    }
    public function getPageById(int $id): ?Page
    {
        if ($id === null || $id <= 0) {
            return null;
        }
        return $this->repository->getPageById($id);
    }


    public function createPage(array $pageData): int
    {
        try {
            return $this->repository->createPage($pageData);
        } catch (\Throwable $e) {
            throw new \RuntimeException($e);
        }
    }

    public function updatePage(int $id, array $pageData): bool
    {
        return $this->repository->updatePage($id, $pageData);
    }

    public function deletePage(int $id): bool
    {
        return $this->repository->deletePage($id);
    }

    public function getPublishedPages(): array
    {
        $pages = $this->repository->getAllPages();
        return array_values(array_filter($pages,
            fn($p) => (((isset($p->status) && $p->status instanceof PageStatus)
                ? $p->status->value
                : (string) ($p->status ?? '')) === PageStatus::published->value)
        ));
    }

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