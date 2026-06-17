<?php
$facts = [
    ['label' => $s['fact_one_label'] ?? '', 'value' => $s['fact_one_value'] ?? ''],
    ['label' => $s['fact_two_label'] ?? '', 'value' => $s['fact_two_value'] ?? ''],
    ['label' => $s['fact_three_label'] ?? '', 'value' => $s['fact_three_value'] ?? ''],
    ['label' => $s['fact_four_label'] ?? '', 'value' => $s['fact_four_value'] ?? ''],
];
?>

<section class="history-st-bavo-facts">
    <div class="history-container history-st-bavo-facts__grid">
        <?php foreach ($facts as $fact): ?>
            <?php if (trim((string) $fact['label']) === '' && trim((string) $fact['value']) === '') continue; ?>
            <article class="history-st-bavo-facts__card">
                <p class="history-st-bavo-facts__label"><?= htmlspecialchars((string) $fact['label'], ENT_QUOTES, 'UTF-8') ?></p>
                <h2 class="history-st-bavo-facts__value"><?= htmlspecialchars((string) $fact['value'], ENT_QUOTES, 'UTF-8') ?></h2>
            </article>
        <?php endforeach; ?>
    </div>
</section>
