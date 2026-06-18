<?php
$heroImage = $jazzImage($s['section_image'] ?? '');
$lineOne = trim((string) ($s['title_line_one'] ?? ''));
$lineTwo = trim((string) ($s['title_line_two'] ?? ''));
?>
<section class="jazz-hero"<?= $heroImage !== '' ? ' style="background-image:url(\'' . htmlspecialchars($heroImage, ENT_QUOTES, 'UTF-8') . '\')"' : '' ?>>
    <div class="jazz-hero__overlay"></div>
    <div class="jazz-hero__inner">
        <h1 class="jazz-hero__title">
            <span class="jazz-hero__title-line"><?= htmlspecialchars($lineOne, ENT_QUOTES, 'UTF-8') ?></span>
            <?php if ($lineTwo !== ''): ?>
                <span class="jazz-hero__title-line"><?= htmlspecialchars($lineTwo, ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
        </h1>
    </div>
</section>
