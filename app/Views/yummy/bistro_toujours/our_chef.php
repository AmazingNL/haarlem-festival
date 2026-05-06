<?php
// Prepare images from the gallery data
$sectionImage = $currentGallery['section_image'] ?? [];
$images = [];
$chefArticleText = trim(html_entity_decode(strip_tags((string) ($ourChef['article'] ?? '')), ENT_QUOTES, 'UTF-8'));
$chefNameFallback = '';

if (preg_match('/\bchef\s+([A-Z][a-z]+(?:\s+[A-Z][a-z]+)+)\b/', $chefArticleText, $chefNameMatch)) {
    $chefNameFallback = trim($chefNameMatch[1]);
}

$galleryTitle = trim((string) ($currentGallery['title'] ?? ''));

$buildImage = static function (string $src, string $alt = '', string $caption = '') use ($chefNameFallback, $galleryTitle): array {
    $displayName = trim($caption) !== '' ? trim($caption) : trim($alt);
    if ($displayName === '') {
        $displayName = $chefNameFallback !== '' ? $chefNameFallback : $galleryTitle;
    }

    return [
        'src' => $src,
        'alt' => trim($alt) !== '' ? trim($alt) : $displayName,
        'caption' => $displayName,
    ];
};

if (is_array($sectionImage)) {
    foreach ($sectionImage as $img) {
        if (is_array($img) && !empty($img['src'])) {
            $images[] = $buildImage(
                trim((string) $img['src']),
                trim((string) ($img['alt'] ?? '')),
                trim((string) ($img['caption'] ?? ''))
            );
        } else if (is_string($img) && trim($img) !== '') {
            $images[] = $buildImage(trim($img));
        }
    }
}
?>

<section class="ratatouille-copy-section">
            <h2 class="ratatouille-section-title">
                <?= htmlspecialchars($ourChef['title']) ?>
            </h2>

            <?php if (!empty($ourChef['article'])): ?>
                <div class="ratatouille-body-copy">
                    <?= $ourChef['article'] ?>
                </div>
            <?php endif; ?>

            <div class="ratatouille-chef-grid">
                <?php foreach (array_slice($images, 0, 2) as $img): ?>
                    <div class="ratatouille-chef-card">
                        <div class="ratatouille-chef-image">
                            <img src="<?= htmlspecialchars((string) $img['src']) ?>"
                                alt="<?= htmlspecialchars((string) ($img['alt'] ?? 'Chef')) ?>"
                                class="object-cover">
                        </div>

                        <?php if (!empty($img['caption'])): ?>
                            <p>
                                <?= htmlspecialchars((string) $img['caption']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                <?php endforeach; ?>
            </div>

            <hr class="ratatouille-section-rule">
</section>