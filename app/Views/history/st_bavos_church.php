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

$historyPage = (string) ($historyPage ?? 'st-bavo');
$navSection = $sectionsByType['history_page_nav'] ?? [];
?>

<div class="history-page history-st-bavo-page">
    <?php require __DIR__ . '/partials/page_nav.php'; ?>

    <?php if (!empty($sectionsByType['history_st_bavo_hero'])): ?>
        <?php $s = $sectionsByType['history_st_bavo_hero']; ?>
        <?php require __DIR__ . '/partials/st_bavo_hero.php'; ?>
    <?php endif; ?>

    <?php if (!empty($sectionsByType['history_st_bavo_facts'])): ?>
        <?php $s = $sectionsByType['history_st_bavo_facts']; ?>
        <?php require __DIR__ . '/partials/st_bavo_facts.php'; ?>
    <?php endif; ?>

    <section class="history-st-bavo-content">
        <div class="history-container history-st-bavo-content__grid">
            <?php if (!empty($sectionsByType['history_st_bavo_article'])): ?>
                <?php $s = $sectionsByType['history_st_bavo_article']; ?>
                <?php require __DIR__ . '/partials/st_bavo_article.php'; ?>
            <?php endif; ?>

            <?php if (!empty($sectionsByType['history_st_bavo_sidebar'])): ?>
                <?php $s = $sectionsByType['history_st_bavo_sidebar']; ?>
                <?php require __DIR__ . '/partials/st_bavo_sidebar.php'; ?>
            <?php endif; ?>
        </div>
    </section>

    <?php if (!empty($sectionsByType['history_st_bavo_route_cta'])): ?>
        <?php $s = $sectionsByType['history_st_bavo_route_cta']; ?>
        <?php require __DIR__ . '/partials/st_bavo_route_cta.php'; ?>
    <?php endif; ?>
</div>
