<?php

namespace App\Services;

use App\Models\Page;
interface IAdminPageService
{
    public function getAllPages(): array;
    public function getPublishedPages(): array;
    public function getPageById(int $id): ?Page;
    public function createPage(array $pageData): int;
    public function updatePage(int $id, array $pageData): bool;
    public function deletePage(int $id): bool;
    public function getPageBySlug(string $slug): ?Page;
}