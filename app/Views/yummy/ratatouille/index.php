<?php

// Collect all welcome banner cards before the banner view renders.
$welcomeBannerCard = [];
$welcomeBannerSection = null;

foreach (($section ?? []) as $s) {
    if (empty($s['is_published']))
        continue;

    $type = trim($s['section_type']);

    if ($type === 'welcome_banner_card') {
        $welcomeBannerCard[] = $s;
        continue;
    }

    if ($type === 'welcome_banner') {
        $welcomeBannerSection = $s;
    }
}

if ($welcomeBannerSection !== null) {
    $s = $welcomeBannerSection;
    require __DIR__ . '/welcome_banner.php';
    require __DIR__ . '/breadcrumb.php';
}

