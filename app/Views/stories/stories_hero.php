<?php
$he    = static fn(string $v): string => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
$bg    = !empty($s['image_path']) ? 'style="background-image: url(\'' . $he((string) $s['image_path']) . '\');"' : '';
$title = (string) ($s['title']        ?? '');
$btn   = (string) ($s['button_text']  ?? '');
$link  = (string) ($s['button_link']  ?? '');
$week  = (string) ($s['date_week']    ?? '');
$range = (string) ($s['date_range']   ?? '');
$days  = (string) ($s['date_days']    ?? '');
?>
<section class="sh-banner" <?= $bg ?>>
    <div class="sh-banner-inner">
        <div class="sh-banner-bottom">
            <div class="sh-banner-left">
                <?php if ($title !== ''): ?>
                    <h1 class="sh-banner-title"><?= $he($title) ?></h1>
                <?php endif; ?>
                <?php if ($btn !== '' && $link !== ''): ?>
                    <a class="sh-banner-btn" href="<?= $he($link) ?>"><?= $he($btn) ?></a>
                <?php endif; ?>
            </div>

            <?php if ($week !== '' || $range !== ''): ?>
                <div class="sh-date-box">
                    <?php if ($week !== ''): ?>
                        <span class="sh-date-label"><?= $he($week) ?></span>
                    <?php endif; ?>
                    <?php if ($range !== ''): ?>
                        <span class="sh-date-value"><?= $he($range) ?></span>
                    <?php endif; ?>
                    <?php if ($days !== ''): ?>
                        <span class="sh-date-sub"><?= $he($days) ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
