<?php
$resCard = [];

foreach (($section ?? []) as $s) {
    if (empty($s['is_published'])) continue;

    $type = trim($s['section_type'] ?? '');

    if ($type === 'restaurants_card') {
        $resCard[] = $s;   // collect
        continue;          
    }

    switch ($type) {
        case 'welcome_banner':
            require __DIR__ . '/welcome_banner.php';
            require __DIR__ . '/breadcrumb.php';
            break;

        case 'text_block':
            require __DIR__ . '/text_block.php';
            break;

        case 'haarlem_unique':
            require __DIR__ . '/haarlem_unique.php';
            break;

        case 'haarlem_taste':
            require __DIR__ . '/haarlem_taste.php';
            break;

        default:
            require __DIR__ . '/inspection.php';
            break;
    }
}

if (!empty($resCard)) {
    require __DIR__ . '/restaurants_card.php';
}
?>