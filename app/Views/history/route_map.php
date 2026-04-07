<?php
$historyText = static function (?string $value): string {
    return nl2br(htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'));
};

$historyUrl = static function (?string $value, string $default = '#'): string {
    $url = trim((string) $value);
    return htmlspecialchars($url !== '' ? $url : $default, ENT_QUOTES, 'UTF-8');
};

$sectionsByType = [];
foreach (($section ?? []) as $item) {
    if (empty($item['is_published'])) {
        continue;
    }

    $sectionsByType[(string) ($item['section_type'] ?? '')] = $item;
}

$historyPage = (string) ($historyPage ?? 'route-map');
$navSection = $sectionsByType['history_page_nav'] ?? [];
?>

<div class="history-page history-route-map-page">
    <?php require __DIR__ . '/partials/page_nav.php'; ?>

    <?php if (!empty($sectionsByType['history_route_map_hero'])): ?>
        <?php $s = $sectionsByType['history_route_map_hero']; ?>
        <?php require __DIR__ . '/partials/route_map_hero.php'; ?>
    <?php endif; ?>

    <?php if (!empty($sectionsByType['history_route_map_stops'])): ?>
        <?php $s = $sectionsByType['history_route_map_stops']; ?>
        <?php require __DIR__ . '/partials/route_map_stops.php'; ?>
    <?php endif; ?>

    <?php if (!empty($sectionsByType['history_route_map_directions'])): ?>
        <?php $s = $sectionsByType['history_route_map_directions']; ?>
        <?php require __DIR__ . '/partials/route_map_directions.php'; ?>
    <?php endif; ?>

    <?php if (!empty($sectionsByType['history_route_map_cta'])): ?>
        <?php $s = $sectionsByType['history_route_map_cta']; ?>
        <?php require __DIR__ . '/partials/route_map_cta.php'; ?>
    <?php endif; ?>
</div>
