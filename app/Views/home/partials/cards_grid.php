<?php
$cards = [
    [
        'title' => $s['card_one_title'] ?? '',
        'text' => $s['card_one_text'] ?? '',
        'image' => $s['card_one_image'] ?? '',
        'alt' => $s['card_one_alt'] ?? '',
        'button_text' => $s['card_one_button_text'] ?? '',
        'button_link' => $s['card_one_button_link'] ?? '',
        'id' => 'home-stories',
    ],
    [
        'title' => $s['card_two_title'] ?? '',
        'text' => $s['card_two_text'] ?? '',
        'image' => $s['card_two_image'] ?? '',
        'alt' => $s['card_two_alt'] ?? '',
        'button_text' => $s['card_two_button_text'] ?? '',
        'button_link' => $s['card_two_button_link'] ?? '',
        'id' => 'home-history',
    ],
    [
        'title' => $s['card_three_title'] ?? '',
        'text' => $s['card_three_text'] ?? '',
        'image' => $s['card_three_image'] ?? '',
        'alt' => $s['card_three_alt'] ?? '',
        'button_text' => $s['card_three_button_text'] ?? '',
        'button_link' => $s['card_three_button_link'] ?? '',
        'id' => 'home-restaurants',
    ],
    [
        'title' => $s['card_four_title'] ?? '',
        'text' => $s['card_four_text'] ?? '',
        'image' => $s['card_four_image'] ?? '',
        'alt' => $s['card_four_alt'] ?? '',
        'button_text' => $s['card_four_button_text'] ?? '',
        'button_link' => $s['card_four_button_link'] ?? '',
        'id' => 'home-dance',
    ],
    [
        'title' => $s['card_five_title'] ?? '',
        'text' => $s['card_five_text'] ?? '',
        'image' => $s['card_five_image'] ?? '',
        'alt' => $s['card_five_alt'] ?? '',
        'button_text' => $s['card_five_button_text'] ?? '',
        'button_link' => $s['card_five_button_link'] ?? '',
        'id' => 'home-jazz',
    ],
];
$cards = array_values(array_filter($cards, static fn(array $card): bool => trim((string) ($card['title'] ?? '')) !== ''));
?>

<?php if ($cards !== []): ?>
    <section class="home-activities" id="home-activities">
        <div class="home-container">
            <h2><?= htmlspecialchars((string) ($s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>

            <div class="home-activities__grid">
                <?php foreach ($cards as $index => $card): ?>
                    <article class="home-activity-card<?= $index === 4 ? ' home-activity-card--wide' : '' ?>"
                        id="<?= htmlspecialchars((string) $card['id'], ENT_QUOTES, 'UTF-8') ?>">
                        <div class="home-activity-card__image">
                            <img src="<?= htmlspecialchars((string) $card['image'], ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars((string) ($card['alt'] !== '' ? $card['alt'] : $card['title']), ENT_QUOTES, 'UTF-8') ?>">
                        </div>

                        <div class="home-activity-card__body">
                            <h3><?= htmlspecialchars((string) $card['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <p><?= $homeText($card['text']) ?></p>

                            <?php if ($card['button_text'] !== ''): ?>
                                <a class="home-button home-button--small" href="<?= $homeUrl($card['button_link']) ?>">
                                    <?= htmlspecialchars((string) $card['button_text'], ENT_QUOTES, 'UTF-8') ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
