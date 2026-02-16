<?php

namespace App\Repositories;
use App\Models\Page;
interface IAdminPageRepository
{
    public function getAllPages(): array;
    public function getPageById(int $id): ?Page;
    public function createPage(array $pageData): int;
    public function updatePage(int $id, array $pageData): bool;
    public function deletePage(int $id): bool;

}