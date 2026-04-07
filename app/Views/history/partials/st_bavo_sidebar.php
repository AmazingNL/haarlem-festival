<?php
$detailRows = [
    ['label' => $s['full_address_label'] ?? '', 'value' => $s['full_address_value'] ?? ''],
    ['label' => $s['construction_label'] ?? '', 'value' => $s['construction_value'] ?? ''],
    ['label' => $s['style_label'] ?? '', 'value' => $s['style_value'] ?? ''],
    ['label' => $s['purpose_label'] ?? '', 'value' => $s['purpose_value'] ?? ''],
    ['label' => $s['function_label'] ?? '', 'value' => $s['function_value'] ?? ''],
    ['label' => $s['opening_label'] ?? '', 'value' => $s['opening_value'] ?? ''],
];

$didYouKnowFacts = [
    $s['fact_one'] ?? '',
    $s['fact_two'] ?? '',
    $s['fact_three'] ?? '',
    $s['fact_four'] ?? '',
    $s['fact_five'] ?? '',
];
?>

<aside class="history-st-bavo-sidebar">
    <article class="history-st-bavo-card history-st-bavo-card--map">
        <div class="history-st-bavo-card__header">
            <h3><?= htmlspecialchars((string) ($s['map_heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h3>
            <?php if (!empty($s['map_address'])): ?>
                <p><?= htmlspecialchars((string) $s['map_address'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
        </div>

        <?php if (!empty($s['map_image'])): ?>
            <img
                class="history-st-bavo-card__map-image"
                src="<?= htmlspecialchars((string) $s['map_image'], ENT_QUOTES, 'UTF-8') ?>"
                alt="<?= htmlspecialchars((string) ($s['map_heading'] ?? 'Map'), ENT_QUOTES, 'UTF-8') ?>">
        <?php endif; ?>

        <?php if (!empty($s['map_link_text'])): ?>
            <a
                class="history-st-bavo-card__map-link"
                href="<?= $historyUrl($s['map_link_url']) ?>"
                target="_blank"
                rel="noopener">
                <?= htmlspecialchars((string) $s['map_link_text'], ENT_QUOTES, 'UTF-8') ?>
            </a>
        <?php endif; ?>
    </article>

    <article class="history-st-bavo-card">
        <h3><?= htmlspecialchars((string) ($s['details_heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h3>

        <dl class="history-st-bavo-details">
            <?php foreach ($detailRows as $row): ?>
                <?php if (trim((string) $row['label']) === '' && trim((string) $row['value']) === '') continue; ?>
                <div class="history-st-bavo-details__row">
                    <dt><?= htmlspecialchars((string) $row['label'], ENT_QUOTES, 'UTF-8') ?></dt>
                    <dd><?= $historyText($row['value']) ?></dd>
                </div>
            <?php endforeach; ?>
        </dl>
    </article>

    <article class="history-st-bavo-card">
        <h3><?= htmlspecialchars((string) ($s['facts_heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h3>

        <ol class="history-st-bavo-did-you-know">
            <?php foreach ($didYouKnowFacts as $index => $fact): ?>
                <?php if (trim((string) $fact) === '') continue; ?>
                <li>
                    <span><?= htmlspecialchars((string) ($index + 1), ENT_QUOTES, 'UTF-8') ?></span>
                    <p><?= htmlspecialchars((string) $fact, ENT_QUOTES, 'UTF-8') ?></p>
                </li>
            <?php endforeach; ?>
        </ol>
    </article>

    <article class="history-st-bavo-tour-card">
        <h3><?= htmlspecialchars((string) ($s['tour_heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h3>
        <?php if (!empty($s['tour_text'])): ?>
            <p><?= $historyText($s['tour_text']) ?></p>
        <?php endif; ?>

        <?php if (!empty($s['tour_button_text'])): ?>
            <a class="history-btn history-btn--gold" href="<?= $historyUrl($s['tour_button_link']) ?>">
                <?= htmlspecialchars((string) $s['tour_button_text'], ENT_QUOTES, 'UTF-8') ?>
            </a>
        <?php endif; ?>
    </article>
</aside>
