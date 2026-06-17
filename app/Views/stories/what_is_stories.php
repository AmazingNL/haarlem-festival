<?php
$he      = static fn(string $v): string => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
$title   = (string) ($s['title']      ?? '');
$imgPath = (string) ($s['image_path'] ?? '');
$imgAlt  = (string) ($s['image_alt']  ?? 'Stories in Haarlem');
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
                <?php if (!empty($s['content'])): ?>
                    <?= \App\Support\Html::clean($s['content'] ?? '') ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
