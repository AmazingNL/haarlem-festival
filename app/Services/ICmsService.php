<?php

namespace App\Services;

use App\DTO\PageData;
use App\Models\Page;
interface ICmsService
{
    public function getAllPages(): array;
    public function getPublishedPages(): array;
    public function getPageById(int $id): ?Page;
    public function createPage(PageData $pageData): int;
    public function updatePage(int $id, PageData $pageData): bool;
    public function deletePage(int $id): bool;
    public function getPageBySlug(string $slug): ?Page;
}