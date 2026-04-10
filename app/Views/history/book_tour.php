<?php
// Small helper functions used by the book tour partials.
$historyText = static function (?string $value): string {
    return nl2br(htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'));
};

$historyUrl = static function (?string $value, string $default = '#'): string {
    $url = trim((string) $value);
    return htmlspecialchars($url !== '' ? $url : $default, ENT_QUOTES, 'UTF-8');
};

// Re-index sections by section_type so this view can quickly pick the blocks it needs.
$sectionsByType = [];
foreach (($section ?? []) as $item) {
    if (empty($item['is_published'])) {
        continue;
    }

    $sectionsByType[(string) ($item['section_type'] ?? '')] = $item;
}

$historyPage = (string) ($historyPage ?? 'book-tour');
$navSection = $sectionsByType['history_page_nav'] ?? [];
?>

<div class="history-page history-tour-page">
    <?php // Build the Book Tour page from CMS blocks in the order we want on screen. ?>
    <?php require __DIR__ . '/partials/page_nav.php'; ?>

    <?php if (!empty($sectionsByType['history_book_tour_hero'])): ?>
        <?php $s = $sectionsByType['history_book_tour_hero']; ?>
        <?php require __DIR__ . '/partials/tour_hero.php'; ?>
    <?php endif; ?>

    <section class="history-tour-board">
        <div class="history-container history-tour-board__grid">
            <?php if (!empty($sectionsByType['history_book_tour_booking'])): ?>
                <?php $s = $sectionsByType['history_book_tour_booking']; ?>
                <?php require __DIR__ . '/partials/tour_booking.php'; ?>
            <?php endif; ?>

            <?php if (!empty($sectionsByType['history_book_tour_route'])): ?>
                <?php $s = $sectionsByType['history_book_tour_route']; ?>
                <?php require __DIR__ . '/partials/tour_route.php'; ?>
            <?php endif; ?>
        </div>
    </section>

    <?php if (!empty($sectionsByType['history_book_tour_schedule'])): ?>
        <?php $s = $sectionsByType['history_book_tour_schedule']; ?>
        <?php require __DIR__ . '/partials/tour_schedule.php'; ?>
    <?php endif; ?>

    <?php if (!empty($sectionsByType['history_book_tour_pricing'])): ?>
        <?php $s = $sectionsByType['history_book_tour_pricing']; ?>
        <?php require __DIR__ . '/partials/tour_pricing.php'; ?>
    <?php endif; ?>

    <?php if (!empty($sectionsByType['history_book_tour_notice'])): ?>
        <?php $s = $sectionsByType['history_book_tour_notice']; ?>
        <?php require __DIR__ . '/partials/tour_notice.php'; ?>
    <?php endif; ?>

    <?php if (!empty($sectionsByType['history_book_tour_alert'])): ?>
        <?php $s = $sectionsByType['history_book_tour_alert']; ?>
        <?php require __DIR__ . '/partials/tour_alert.php'; ?>
    <?php endif; ?>
</div>
