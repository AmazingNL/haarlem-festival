<?php

foreach (($section ?? []) as $s) {
    if (empty($s['is_published']))
        continue;

    $type = trim($s['section_type']);

    switch ($type) {
        case 'welcome_banner':
            require __DIR__ . '/welcome_banner.php';
            require __DIR__ . '/breadcrumb.php';

            break;
    }
}

