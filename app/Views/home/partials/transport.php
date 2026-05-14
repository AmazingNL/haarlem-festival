<?php
$transportItems = [
    $s['item_one'] ?? '',
    $s['item_two'] ?? '',
    $s['item_three'] ?? '',
    $s['item_four'] ?? '',
];
$transportItems = array_values(array_filter($transportItems, static fn(string $item): bool => trim($item) !== ''));
$paragraphs = $homeParagraphs($s['intro'] ?? '');
?>

<section class="home-transport">
    <div class="home-container home-transport__grid">
        <div class="home-transport__content">
            <h2><?= htmlspecialchars((string) ($s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>

            <?php foreach ($paragraphs as $paragraph): ?>
                <p><?= $homeText($paragraph) ?></p>
            <?php endforeach; ?>

            <?php if (!empty($s['list_intro'])): ?>
                <p class="home-transport__list-intro"><?= $homeText($s['list_intro']) ?></p>
            <?php endif; ?>

            <?php if ($transportItems !== []): ?>
                <ul class="home-transport__list">
                    <?php foreach ($transportItems as $item): ?>
                        <li><?= $homeText($item) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if (!empty($s['button_text'])): ?>
                <a class="home-button home-button--wide" href="<?= $homeUrl($s['button_link']) ?>">
                    <?= htmlspecialchars((string) $s['button_text'], ENT_QUOTES, 'UTF-8') ?>
                </a>
            <?php endif; ?>
        </div>

        <div class="home-transport__media">
            <img src="<?= htmlspecialchars((string) ($s['image'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                alt="<?= htmlspecialchars((string) ($s['image_alt'] ?? $s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>
    </div>
</section>
