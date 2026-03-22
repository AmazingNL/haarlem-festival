<?php

namespace App\Services;

use App\Models\Page;
use App\Repositories\IAdminPageRepository;
use App\Models\Enum\PageStatus;
use RuntimeException;
use Throwable;

final class AdminPageService implements IAdminPageService
{
    private IAdminPageRepository $repository;

    public function __construct(IAdminPageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllPages(): array
    {
        try {
            return $this->repository->getAllPages();
        } catch (Throwable $e) {
            throw new RuntimeException($e);
        }
    }
    public function getPageById(int $id): ?Page
    {
        if ($id === null || $id <= 0) {
            return null;
        }
        return $this->repository->getPageById($id);
    }

    public function preparePageData(string $title, string $slug, string $content, string $status = 'draft'): array
    {
        return [
            'title' => $title,
            'slug' => $slug,
            'content' => $this->sanitizeCmsHtml($content),
            'status' => $status,
        ];
    }


    public function createPage(array $pageData): int
    {

        return $this->repository->createPage($pageData);

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
        return array_values(array_filter(
            $pages,
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

    private function sanitizeCmsHtml(string $html): string
    {
        $html = preg_replace('#<\s*(script|style)\b[^>]*>.*?<\s*/\s*\1\s*>#is', '', $html) ?? '';
        $allowed = '<p><br><b><strong><i><em><u><h1><h2><h3><h4><h5><h6><ul><ol>
        <li><a><img><blockquote><hr><span><div><section><article><figure><figcaption><table><thead><tbody><tr><th><td>';
        $html = strip_tags($html, $allowed);
        $html = preg_replace('/\son\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html) ?? '';
        $html = preg_replace('/\s(href|src)\s*=\s*("|\')\s*javascript:[^"\']*\2/i', ' $1=$2#$2', $html) ?? '';
        return trim($html);
    }

}