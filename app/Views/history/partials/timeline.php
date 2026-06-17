<?php
$timelineItems = [
    [
        'title' => $s['item_one_title'] ?? '',
        'text' => $s['item_one_text'] ?? '',
        'text_secondary' => $s['item_one_text_secondary'] ?? '',
        'image' => $s['item_one_image'] ?? '',
        'label' => $s['item_one_label'] ?? '',
        'caption' => $s['item_one_caption'] ?? '',
    ],
    [
        'title' => $s['item_two_title'] ?? '',
        'text' => $s['item_two_text'] ?? '',
        'text_secondary' => $s['item_two_text_secondary'] ?? '',
        'image' => $s['item_two_image'] ?? '',
        'label' => $s['item_two_label'] ?? '',
        'caption' => $s['item_two_caption'] ?? '',
    ],
    [
        'title' => $s['item_three_title'] ?? '',
        'text' => $s['item_three_text'] ?? '',
        'text_secondary' => $s['item_three_text_secondary'] ?? '',
        'image' => $s['item_three_image'] ?? '',
        'label' => $s['item_three_label'] ?? '',
        'caption' => $s['item_three_caption'] ?? '',
    ],
];
?>
<section class="history-section history-timeline" id="history-timeline">
    <div class="history-container">
        <div class="history-section__heading history-section__heading--center">
            <?php if (!empty($s['eyebrow'])): ?>
                <p class="history-eyebrow"><?= htmlspecialchars((string) $s['eyebrow'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
            <h2><?= htmlspecialchars((string) ($s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>
        </div>

        <div class="history-timeline__list">
            <?php foreach ($timelineItems as $index => $item): ?>
                <article class="history-timeline__item <?= $index % 2 === 1 ? 'history-timeline__item--reverse' : '' ?>">
                    <div class="history-timeline__content">
                        <h3><?= htmlspecialchars((string) $item['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <?php if ($item['text'] !== ''): ?>
                            <p><?= $historyText($item['text']) ?></p>
                        <?php endif; ?>
                        <?php if ($item['text_secondary'] !== ''): ?>
                            <p><?= $historyText($item['text_secondary']) ?></p>
                        <?php endif; ?>
                    </div>

                    <figure class="history-timeline__image-card">
                        <img src="<?= htmlspecialchars((string) $item['image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string) $item['title'], ENT_QUOTES, 'UTF-8') ?>">
                        <?php if ($item['label'] !== '' || $item['caption'] !== ''): ?>
                            <figcaption>
                                <?php if ($item['label'] !== ''): ?>
                                    <strong><?= htmlspecialchars((string) $item['label'], ENT_QUOTES, 'UTF-8') ?></strong>
                                <?php endif; ?>
                                <?php if ($item['caption'] !== ''): ?>
                                    <span><?= htmlspecialchars((string) $item['caption'], ENT_QUOTES, 'UTF-8') ?></span>
                                <?php endif; ?>
                            </figcaption>
                        <?php endif; ?>
                    </figure>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
