<?php
// scripts/list_pages.php
// Usage: php scripts/list_pages.php

require __DIR__ . '/../vendor/autoload.php';

// optionally load .env if present
if (file_exists(__DIR__ . '/../.env')) {
    // very small parser for KEY=VALUE lines
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        [$k, $v] = array_map('trim', explode('=', $line, 2) + [1 => '']);
        if ($k !== '') {
            $_ENV[$k] = $_ENV[$k] ?? trim($v, "\"'");
        }
    }
}

use App\Repositories\AdminPageRepository;

$repo = new AdminPageRepository();
$pages = $repo->getAllPages();

if (empty($pages)) {
    echo "No pages found\n";
    exit(0);
}

foreach ($pages as $p) {
    $id = $p->page_id ?? '(no id)';
    $title = $p->title ?? '(no title)';
    $status = isset($p->status) ? (is_object($p->status) && property_exists($p->status, 'value') ? $p->status->value : (string)$p->status) : '(unknown)';
    echo sprintf("%s | %s | %s\n", $id, $status, $title);
}
