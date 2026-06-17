<?php
$sectionImage = $s['section_image'] ?? '';

$img = is_array($sectionImage)
    ? trim((string) ($sectionImage[0] ?? ''))
    : trim((string) $sectionImage);

$title      = trim((string) ($s['title'] ?? 'Ratatouille'));
$buttonText = trim((string) ($s['button_text'] ?? ''));
$buttonLink = trim((string) ($s['button_link'] ?? ''));
if ($buttonLink === '/reservation' || $buttonLink === 'reservation') {
    $buttonLink = '#reservation';
}

$cards = array_values(array_filter(
    $welcomeBannerCard ?? [],
    static fn($card): bool => is_array($card)
        && trim((string) ($card['title'] ?? '')) !== ''
        && trim((string) ($card['title'] ?? '')) !== 'Signature Highlights'
));

$groupedCards = array_chunk($cards, (int) ceil(max(1, count($cards)) / 2));
?>

<article class="welcome-card">

    <?php if ($img !== ''): ?>
        <img src="<?= htmlspecialchars($img, ENT_QUOTES, 'UTF-8') ?>"
             alt="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>"
             class="restaurant-image">
    <?php endif; ?>

    <section class="welcome-overlay">
        <header class="title">
            <h2><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h2>
        </header>

        <?php if ($cards !== []): ?>
            <section class="restaurant-info">
                <?php foreach ($groupedCards as $group): ?>
                    <div class="info-group">
                        <?php foreach ($group as $card): ?>
                            <?php $cardInfo = trim(strip_tags((string) ($card['info'] ?? ''))); ?>
                            <?php if ($cardInfo !== ''): ?>
                                <div class="info-item">
                                    <p><?= nl2br(htmlspecialchars($cardInfo, ENT_QUOTES, 'UTF-8')) ?></p>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>

        <?php if ($buttonText !== '' && $buttonLink !== ''): ?>
            <footer class="ft">
                <section class="primary-button button">
                    <a href="<?= htmlspecialchars($buttonLink, ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($buttonText, ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </section>
            </footer>
        <?php endif; ?>
    </section>

</article>
