<?php

namespace App\Services;

use App\Models\Page;
use App\Repositories\IAdminPageRepository;

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
            throw new \RuntimeException(
                'Failed creating page for slug ' 
                . ($pageData['slug'] ?? 'n/a'), 0, $e);
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
}