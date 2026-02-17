<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Repositories\AdminPageRepository;

$repo = new AdminPageRepository();
try {
    $pages = $repo->getAllPages();
    echo "Found " . count($pages) . " pages\n";
    foreach ($pages as $p) {
        printf("id=%s title=%s status=%s created_at=%s\n", $p->page_id ?? 'NULL', $p->title ?? 'NULL', $p->status->value ?? (string)$p->status, $p->created_at ?? 'NULL');
    }
} catch (Throwable $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
