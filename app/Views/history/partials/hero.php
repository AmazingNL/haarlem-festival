<?php
$heroImage = htmlspecialchars((string) ($s['hero_image'] ?? ''), ENT_QUOTES, 'UTF-8');
?>
<section class="history-hero" style="background-image: url('<?= $heroImage ?>');">
    <?php
    $quickLinks = [
        [
            'label' => trim((string) ($s['quick_link_one_label'] ?? 'Book Tour')),
            'href' => $historyUrl($s['quick_link_one_link'] ?? '/history/book-tour', '/history/book-tour'),
        ],
        [
            'label' => trim((string) ($s['quick_link_two_label'] ?? 'Route Map')),
            'href' => $historyUrl($s['quick_link_two_link'] ?? '/history/route-map', '/history/route-map'),
        ],
        [
            'label' => trim((string) ($s['quick_link_three_label'] ?? "St. Bavo's Church")),
            'href' => $historyUrl($s['quick_link_three_link'] ?? '/history/st-bavos-church', '/history/st-bavos-church'),
        ],
        [
            'label' => trim((string) ($s['quick_link_four_label'] ?? 'Molen de Adriaan')),
            'href' => $historyUrl($s['quick_link_four_link'] ?? '/history/molen-de-adriaan', '/history/molen-de-adriaan'),
        ],
    ];
    ?>
    <div class="history-hero__quick-links">
        <?php foreach ($quickLinks as $quickLink): ?>
            <?php if ($quickLink['label'] === '') continue; ?>
            <a href="<?= $quickLink['href'] ?>"><?= htmlspecialchars($quickLink['label'], ENT_QUOTES, 'UTF-8') ?></a>
        <?php endforeach; ?>
    </div>

    <div class="history-hero__inner">
        <div class="history-hero__card">
            <h1 class="history-hero__title">
                <span class="history-hero__title-top"><?= htmlspecialchars((string) ($s['title_line_one'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                <em class="history-hero__title-bottom"><?= htmlspecialchars((string) ($s['title_line_two'] ?? ''), ENT_QUOTES, 'UTF-8') ?></em>
            </h1>

            <?php if (!empty($s['intro'])): ?>
                <p class="history-hero__intro"><?= $historyText($s['intro']) ?></p>
            <?php endif; ?>

            <div class="history-hero__actions">
                <?php if (!empty($s['primary_button_text'])): ?>
                    <a class="history-btn history-btn--primary" href="<?= $historyUrl($s['primary_button_link']) ?>">
                        <span class="history-btn__label"><?= htmlspecialchars((string) $s['primary_button_text'], ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="history-btn__arrow" aria-hidden="true">&rarr;</span>
                    </a>
                <?php endif; ?>

                <?php if (!empty($s['secondary_button_text'])): ?>
                    <a class="history-btn history-btn--secondary" href="<?= $historyUrl($s['secondary_button_link']) ?>">
                        <span class="history-btn__label"><?= htmlspecialchars((string) $s['secondary_button_text'], ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="history-btn__arrow" aria-hidden="true">&rarr;</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>
