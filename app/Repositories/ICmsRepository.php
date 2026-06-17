<?php

namespace App\Repositories;
use App\DTO\PageData;
use App\Models\Page;
interface ICmsRepository
{
    public function getAllPages(): array;
    public function getPageById(int $id): ?Page;
    public function createPage(PageData $pageData): int;
    public function updatePage(int $id, PageData $pageData): bool;
    public function deletePage(int $id): bool;



}