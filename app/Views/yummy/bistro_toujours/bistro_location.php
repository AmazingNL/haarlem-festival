<?php
$address = '';

foreach (($welcomeBannerCard ?? []) as $card) {
    $cardTitle = strtolower(trim((string) ($card['title'] ?? '')));
    if ($cardTitle !== 'address') {
        continue;
    }

    $address = trim(html_entity_decode(strip_tags((string) ($card['info'] ?? '')), ENT_QUOTES, 'UTF-8'));
    break;
}

if ($address === '') {
    $address = 'Oude Groenmarkt 10-12, 2011 HL Haarlem, Nederland';
}

$mapUrl = 'https://www.google.com/maps?q=' . rawurlencode($address) . '&output=embed';
?>

<section class="ratatouille-copy-section ratatouille-location-section">
    <div class="ratatouille-location-header">
        <h2 class="ratatouille-section-title">Location</h2>
        <p class="ratatouille-location-help">
            <span aria-hidden="true">i</span>
            Hold and drag to move the map
        </p>
    </div>

    <iframe
        class="ratatouille-map"
        src="<?= htmlspecialchars($mapUrl, ENT_QUOTES, 'UTF-8') ?>"
        title="Bistro Toujours location map"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        allowfullscreen>
    </iframe>
</section>