<?php
$he             = static fn(string $v): string => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
$show           = $show ?? [];
$s              = $show;
$isDetailPage   = true;
?>
<?php require __DIR__ . '/detail_hero.php'; ?>
<?php require __DIR__ . '/detail_info_bar.php'; ?>
<?php require __DIR__ . '/detail_content.php'; ?>
<?php if (!empty($show['highlights'])): ?>
    <?php require __DIR__ . '/detail_highlights.php'; ?>
<?php endif; ?>
<?php if (!empty($show['gallery_images'])): ?>
    <?php require __DIR__ . '/detail_gallery.php'; ?>
<?php endif; ?>
<?php require __DIR__ . '/booking_card.php'; ?>
