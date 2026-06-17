<?php
$infoItems = [
    ['value' => $s['item_one_value'] ?? '', 'label' => $s['item_one_label'] ?? '', 'marker' => '01'],
    ['value' => $s['item_two_value'] ?? '', 'label' => $s['item_two_label'] ?? '', 'marker' => '02'],
    ['value' => $s['item_three_value'] ?? '', 'label' => $s['item_three_label'] ?? '', 'marker' => '03'],
    ['value' => $s['item_four_value'] ?? '', 'label' => $s['item_four_label'] ?? '', 'marker' => '04'],
];
?>
<section class="history-info">
    <div class="history-container history-info__grid">
        <?php foreach ($infoItems as $item): ?>
            <article class="history-info__card">
                <span class="history-info__marker"><?= htmlspecialchars((string) $item['marker'], ENT_QUOTES, 'UTF-8') ?></span>
                <h3><?= htmlspecialchars((string) $item['value'], ENT_QUOTES, 'UTF-8') ?></h3>
                <p><?= htmlspecialchars((string) $item['label'], ENT_QUOTES, 'UTF-8') ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
