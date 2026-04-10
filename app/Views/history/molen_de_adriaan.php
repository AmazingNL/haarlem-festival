<?php
// Small helper functions used by the Molen partials.
$historyText = static function (?string $value): string {
    return nl2br(htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'));
};

$historyUrl = static function (?string $value, string $default = '#'): string {
    $url = trim((string) $value);
    return htmlspecialchars($url !== '' ? $url : $default, ENT_QUOTES, 'UTF-8');
};

// Re-index sections by section_type so each Molen block can be loaded directly.
$sectionsByType = [];
foreach (($section ?? []) as $item) {
    if (empty($item['is_published'])) {
        continue;
    }

    $sectionsByType[(string) ($item['section_type'] ?? '')] = $item;
}

$historyPage = (string) ($historyPage ?? 'molen');
$navSection = $sectionsByType['history_page_nav'] ?? [];
?>

<div class="history-page history-molen-page">
    <?php require __DIR__ . '/partials/page_nav.php'; ?>

    <?php if (!empty($sectionsByType['history_molen_hero'])): ?>
        <?php $s = $sectionsByType['history_molen_hero']; ?>
        <?php require __DIR__ . '/partials/molen_hero.php'; ?>
    <?php endif; ?>

    <?php if (!empty($sectionsByType['history_molen_facts'])): ?>
        <?php $s = $sectionsByType['history_molen_facts']; ?>
        <?php require __DIR__ . '/partials/molen_facts.php'; ?>
    <?php endif; ?>

    <section class="history-st-bavo-content">
        <div class="history-container history-st-bavo-content__grid">
            <?php if (!empty($sectionsByType['history_molen_article'])): ?>
                <?php $s = $sectionsByType['history_molen_article']; ?>
                <?php require __DIR__ . '/partials/molen_article.php'; ?>
            <?php endif; ?>

            <?php if (!empty($sectionsByType['history_molen_sidebar'])): ?>
                <?php $s = $sectionsByType['history_molen_sidebar']; ?>
                <?php require __DIR__ . '/partials/molen_sidebar.php'; ?>
            <?php endif; ?>
        </div>
    </section>

    <?php if (!empty($sectionsByType['history_molen_route_cta'])): ?>
        <?php $s = $sectionsByType['history_molen_route_cta']; ?>
        <?php require __DIR__ . '/partials/molen_route_cta.php'; ?>
    <?php endif; ?>
</div>
