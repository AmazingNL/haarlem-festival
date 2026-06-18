<?php
$galleryImages = is_array($show['gallery_images'] ?? null) ? $show['gallery_images'] : [];
$galleryTitle  = (string) ($show['gallery_title'] ?? "The Market's Gallery");
?>
<section class="sd-gallery-section">
    <div class="sd-gallery-inner">
        <h2 class="sd-section-title"><?= $he($galleryTitle) ?></h2>
        <div class="sd-gallery-grid">
            <?php foreach ($galleryImages as $imgPath): ?>
                <?php $imgPath = (string) $imgPath; if ($imgPath === '') continue; ?>
                <div class="sd-gallery-item">
                    <img src="<?= $he($imgPath) ?>"
                         alt=""
                         class="sd-gallery-image"
                         loading="lazy">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
