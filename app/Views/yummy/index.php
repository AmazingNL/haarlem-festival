<?php
$resCard = [];
$sectionData = isset($section) && is_array($section) ? $section : [];
$sections = array_values($sectionData);
$total = count($sections);
$showFallbackHero = !empty($showFallbackHero);

if ($showFallbackHero) {
    ?>
    <section class="shop-page">
        <div class="shop-container">
            <header class="shop-hero shop-hero--small">
                <p class="shop-eyebrow">Restaurants</p>
                <h1>Food and drink in Haarlem</h1>
                <p>The full restaurants page is still being prepared.</p>
            </header>
        </div>
    </section>
    <?php
}

for ($i = 0; $i < $total; $i++) {
    $s = $sections[$i];
    if (empty($s['is_published'])) {
        continue;
    }

    $type = trim((string) ($s['section_type'] ?? ''));

    if ($type === 'restaurants_card') {
        $resCard[] = $s;
        continue;
    }

    // Pair a text_block followed immediately by a gallery into one row layout.
    if ($type === 'text_block' && isset($sections[$i + 1])) {
        $next = $sections[$i + 1];
        $nextType = trim((string) ($next['section_type'] ?? ''));
        if (!empty($next['is_published']) && $nextType === 'gallery') {
            $textSection = $s;
            $gallerySection = $next;
            require __DIR__ . '/text_block_gallery.php';
            $i++; // skip the paired gallery section
            continue;
        }
    }

    switch ($type) {
        case 'welcome_banner':
            require __DIR__ . '/welcome_banner.php';
            require __DIR__ . '/breadcrumb.php';
            break;

        case 'text_block':
        case 'gallery':
            $title = trim((string) ($s['title'] ?? ''));
            if($title === "haarlem taste"){
            require __DIR__ . '/haarlem_taste.php';
            }
            else{
            require __DIR__ . '/text_block_gallery.php';
            }
            break;

        case 'haarlem_unique':
            require __DIR__ . '/haarlem_unique.php';
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
