<?php
$heroImage  = (string) ($show['hero_image']  ?? '');
$showTitle  = (string) ($show['show_title']  ?? '');
$heroStyle  = $heroImage !== '' ? ' style="background-image:url(\'' . $he($heroImage) . '\')"' : '';
?>
<section class="sd-hero"<?= $heroStyle ?>>
    <div class="sd-hero-overlay"></div>
    <div class="sd-hero-inner">
        <?php if ($showTitle !== ''): ?>
            <h1 class="sd-hero-title"><?= $he($showTitle) ?></h1>
        <?php endif; ?>
    </div>
</section>
