<?php
$navData = is_array($navSection ?? null) ? $navSection : [];
$historyLinks = [
    [
        'key' => 'book-tour',
        'label' => trim((string) ($navData['book_tour_label'] ?? 'Book Tour')),
        'href' => $historyUrl($navData['book_tour_link'] ?? '/history/book-tour', '/history/book-tour'),
    ],
    [
        'key' => 'route-map',
        'label' => trim((string) ($navData['route_map_label'] ?? 'Route Map')),
        'href' => $historyUrl($navData['route_map_link'] ?? '/history/route-map', '/history/route-map'),
    ],
    [
        'key' => 'st-bavo',
        'label' => trim((string) ($navData['st_bavo_label'] ?? "St. Bavo's Church")),
        'href' => $historyUrl($navData['st_bavo_link'] ?? '/history/st-bavos-church', '/history/st-bavos-church'),
    ],
    [
        'key' => 'molen',
        'label' => trim((string) ($navData['molen_label'] ?? 'Molen de Adriaan')),
        'href' => $historyUrl($navData['molen_link'] ?? '/history/molen-de-adriaan', '/history/molen-de-adriaan'),
    ],
];
?>

<nav class="history-subnav" aria-label="History pages">
    <div class="history-container history-subnav__inner">
        <?php foreach ($historyLinks as $link): ?>
            <?php if ($link['label'] === '') continue; ?>
            <a
                class="history-subnav__link <?= $historyPage === $link['key'] ? 'is-active' : '' ?>"
                href="<?= htmlspecialchars($link['href'], ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8') ?>
            </a>
        <?php endforeach; ?>
    </div>
</nav>
