<?php
$he         = static fn(string $v): string => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
$title      = (string) ($s['title']      ?? '');
$imgPath    = (string) ($s['image_path'] ?? '');
$imgAlt     = (string) ($s['image_alt']  ?? 'Stories in Haarlem');
$rawContent = (string) ($s['content']    ?? '');

// JSON-based sections: mergeContent already unpacked 'html' and 'image_path'
$cardHtml = (string) ($s['html'] ?? '');

// Legacy HTML-based sections: extract <img> from content and strip it from the body
if ($cardHtml === '') {
    if (preg_match('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $rawContent, $m)) {
        $imgPath = $imgPath !== '' ? $imgPath : $m[1];
    }
    if (preg_match('/<img[^>]+alt=["\']([^"\']*)["\'][^>]*>/i', $rawContent, $m)) {
        $imgAlt = $m[1] !== '' ? $m[1] : $imgAlt;
    }
    $cardHtml = trim(preg_replace('/<img\b[^>]*>/i', '', $rawContent) ?? '');
}
?>
<section class="wis-section">
    <div class="wis-inner">
        <?php if ($title !== ''): ?>
            <h2 class="wis-heading"><?= $he($title) ?></h2>
        <?php endif; ?>

        <div class="wis-columns">
            <?php if ($imgPath !== ''): ?>
                <div class="wis-image-wrap">
                    <img class="wis-image"
                         src="<?= $he($imgPath) ?>"
                         alt="<?= $he($imgAlt) ?>">
                </div>
            <?php endif; ?>

            <div class="wis-card <?= $imgPath === '' ? 'wis-card--full' : '' ?>">
                <?php if ($cardHtml !== ''): ?>
                    <?= \App\Support\Html::clean($cardHtml) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
