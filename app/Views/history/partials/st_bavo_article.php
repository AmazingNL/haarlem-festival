<?php
$galleryItems = [
    [
        'image' => $s['gallery_one_image'] ?? '',
        'caption' => $s['gallery_one_caption'] ?? '',
        'wide' => false,
    ],
    [
        'image' => $s['gallery_two_image'] ?? '',
        'caption' => $s['gallery_two_caption'] ?? '',
        'wide' => false,
    ],
    [
        'image' => $s['gallery_three_image'] ?? '',
        'caption' => $s['gallery_three_caption'] ?? '',
        'wide' => true,
    ],
];

$significanceParagraphs = [
    $s['significance_one'] ?? '',
    $s['significance_two'] ?? '',
    $s['significance_three'] ?? '',
];

$importanceParagraphs = [
    $s['importance_one'] ?? '',
    $s['importance_two'] ?? '',
    $s['importance_three'] ?? '',
];
?>

<article class="history-st-bavo-article">
    <h2><?= htmlspecialchars((string) ($s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>

    <?php if (!empty($s['intro_one'])): ?>
        <p><?= $historyText($s['intro_one']) ?></p>
    <?php endif; ?>

    <?php if (!empty($s['intro_two'])): ?>
        <p><?= $historyText($s['intro_two']) ?></p>
    <?php endif; ?>

    <?php if (!empty($s['gallery_heading'])): ?>
        <h3><?= htmlspecialchars((string) $s['gallery_heading'], ENT_QUOTES, 'UTF-8') ?></h3>
    <?php endif; ?>

    <div class="history-st-bavo-gallery">
        <?php foreach ($galleryItems as $item): ?>
            <?php if (trim((string) $item['image']) === '') continue; ?>
            <figure class="history-st-bavo-gallery__item <?= $item['wide'] ? 'history-st-bavo-gallery__item--wide' : '' ?>">
                <img src="<?= htmlspecialchars((string) $item['image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string) $item['caption'], ENT_QUOTES, 'UTF-8') ?>">
                <?php if (trim((string) $item['caption']) !== ''): ?>
                    <figcaption><?= htmlspecialchars((string) $item['caption'], ENT_QUOTES, 'UTF-8') ?></figcaption>
                <?php endif; ?>
            </figure>
        <?php endforeach; ?>
    </div>

    <?php if (!empty($s['significance_heading'])): ?>
        <h3><?= htmlspecialchars((string) $s['significance_heading'], ENT_QUOTES, 'UTF-8') ?></h3>
    <?php endif; ?>

    <?php foreach ($significanceParagraphs as $paragraph): ?>
        <?php if (trim((string) $paragraph) === '') continue; ?>
        <p><?= $historyText($paragraph) ?></p>
    <?php endforeach; ?>

    <?php if (!empty($s['importance_heading'])): ?>
        <h3><?= htmlspecialchars((string) $s['importance_heading'], ENT_QUOTES, 'UTF-8') ?></h3>
    <?php endif; ?>

    <?php foreach ($importanceParagraphs as $paragraph): ?>
        <?php if (trim((string) $paragraph) === '') continue; ?>
        <p><?= $historyText($paragraph) ?></p>
    <?php endforeach; ?>
</article>
