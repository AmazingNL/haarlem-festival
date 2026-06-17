<?php
require __DIR__ . '/partials/_helpers.php';
?>

<div class="history-page">
    <?php // Loop through the CMS sections and load the matching partial for each section type. ?>
    <?php foreach (($section ?? []) as $s): ?>
        <?php if (empty($s['is_published'])) continue; ?>

        <?php switch ((string) ($s['section_type'] ?? '')):
            case 'history_hero':
                require __DIR__ . '/partials/hero.php';
                break;
            case 'history_timeline':
                require __DIR__ . '/partials/timeline.php';
                break;
            case 'history_gallery':
                require __DIR__ . '/partials/gallery.php';
                break;
            case 'history_featured_locations':
                require __DIR__ . '/partials/featured_locations.php';
                break;
            case 'history_route':
                require __DIR__ . '/partials/route.php';
                break;
            case 'history_info':
                require __DIR__ . '/partials/info.php';
                break;
            case 'history_cta':
                require __DIR__ . '/partials/cta.php';
                break;
        endswitch; ?>
    <?php endforeach; ?>
</div>
