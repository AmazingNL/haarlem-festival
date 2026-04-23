<?php
// Normalize the banner section fields before rendering.
$sectionImage = $s['section_image'] ?? '';
$img = '';

if (is_array($sectionImage)) {
    $img = trim((string) ($sectionImage[0] ?? ''));
} else {
    $img = trim((string) $sectionImage);
}

$title = trim((string) ($s['title'] ?? 'Ratatouille'));
$buttonText = trim((string) ($s['button_text'] ?? ''));
$buttonLink = trim((string) ($s['button_link'] ?? ''));

// Keep only valid welcome banner card rows and ignore empty titles.
$cards = array_values(array_filter(
    $welcomeBannerCard ?? [],
    static fn ($card): bool => is_array($card) && trim((string) ($card['title'] ?? '')) !== ''
));

// Split the cards into two columns so the layout stays balanced.
$groupedCards = array_chunk($cards, (int) ceil(max(1, count($cards)) / 2));
?>

<article class="welcome-card">

    <?php if ($img !== ''): ?>
        <img src="<?= htmlspecialchars($img, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>" class="restaurant-image">
    <?php endif; ?>
<section class="absolute top-0 left-0 w-full">
    <header class="title">
        <h2><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h2>
    </header>

    <?php if ($cards !== []): ?>
        <section class="restaurant-info">
            <?php foreach ($groupedCards as $group): ?>
                <div class="info-group">
                    <?php foreach ($group as $card): ?>
                        <?php
                        // Prepare each card value for safe output.
                        $cardTitle = trim((string) ($card['title'] ?? ''));
                        $cardInfo = trim(strip_tags((string) ($card['info'] ?? '')));
                        ?>
                        <div class="info-item">
                            <!-- <p><strong><?= htmlspecialchars($cardTitle, ENT_QUOTES, 'UTF-8') ?></strong></p> -->
                            <?php if ($cardInfo !== ''): ?>
                                <p><?= nl2br(htmlspecialchars($cardInfo, ENT_QUOTES, 'UTF-8')) ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</section>


    <?php if ($buttonText !== '' && $buttonLink !== ''): ?>
        <footer class="ft">
            <section class="primary-button button">
                <a href="<?= htmlspecialchars($buttonLink, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($buttonText, ENT_QUOTES, 'UTF-8') ?></a>
            </section>
        </footer>
    <?php endif; ?>

</article>