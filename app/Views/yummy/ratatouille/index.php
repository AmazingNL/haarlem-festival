<?php

// Collect all welcome banner cards before the banner view renders.

$welcomeBannerCard = [];
$welcomeBannerSection = null;
$ourChef = null;
$aboutRatatouille;
$gallery = [];
$reservation = null;

$sectionData = isset($section) && is_array($section) ? $section : [];
$sections = array_values($sectionData);


foreach ($sections as $s) {
    if (empty($s['is_published'])) {
        continue;
    }
    $type = trim($s['section_type']);

    if ($type === 'welcome_banner_card') {
        $welcomeBannerCard[] = $s;
    }
    if ($type === 'welcome_banner') {
        $welcomeBannerSection = $s;
    }
    if ($type === 'text_block' && trim($s['sub_title']) === 'about') {
        $aboutRatatouille = $s;
    }
    if ($type === 'text_block' && trim($s['custom_class']) === 'chef_section') {
        $ourChef = $s;
    }
    if ($type === 'gallery' && trim($s['custom_class']) === 'chef_section') {
        $gallery[] = $s;
    }
    if ($type === 'reservation') {
        $reservation = $s;
    }
}

if ($welcomeBannerSection) {
    $s = $welcomeBannerSection;
    require __DIR__ . '/welcome_banner.php';
    require __DIR__ . '/breadcrumb.php';
}

?>

<section class="ratatouille-detail-shell">
    <div class="ratatouille-detail-grid">
        <div class="ratatouille-detail-content">
            <?php
            // Handle "About Ratatouille" (Standard Text Block + Banner Cards)
            if (!empty($aboutRatatouille) && !empty($welcomeBannerCard)) {
                $s = $aboutRatatouille;
                require __DIR__ . '/about_ratatouille.php';
            }

            if (!empty($ourChef) && !empty($gallery)) {
                $currentGallery = $gallery[0];
                require __DIR__ . '/our_chef.php';
            }
            ?>
        </div>

        <?php if (!empty($reservation)): ?>
            <aside id="reservation" class="ratatouille-reservation-column" aria-label="Book your reservation">
                <?php
                $s = $reservation;
                require __DIR__ . '/reservation.php';
                ?>
            </aside>
        <?php endif; ?>
    </div>
</section>

