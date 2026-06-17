<?php
$bgStyle = '';
if (!empty($s['image_path'])) {
    $bg = htmlspecialchars((string) $s['image_path'], ENT_QUOTES, 'UTF-8');
    $bgStyle = 'style="background-image: url(\'' . $bg . '\');"';
}
?>
<section class="sh-banner" <?= $bgStyle ?>>
    <div class="sh-banner-inner">
        <?php if (!empty($s['title'])):
            $title    = (string) $s['title'];
            $spacePos = strpos($title, ' ');
            $first    = $spacePos !== false ? substr($title, 0, $spacePos) : $title;
            $rest     = $spacePos !== false ? trim(substr($title, $spacePos)) : '';
        ?>
            <h1 class="sh-banner-title">
                <span class="sh-title--accent"><?= htmlspecialchars($first, ENT_QUOTES, 'UTF-8') ?></span>
                <?php if ($rest !== ''): ?>
                    <span class="sh-title--main"><?= htmlspecialchars($rest, ENT_QUOTES, 'UTF-8') ?></span>
                <?php endif; ?>
            </h1>
        <?php endif; ?>

        <?php if (!empty($s['content'])): ?>
            <div class="sh-banner-content"><?= $s['content'] ?></div>
        <?php endif; ?>

        <?php if (!empty($s['button_text']) && !empty($s['button_link'])): ?>
            <a class="sh-banner-btn" href="<?= htmlspecialchars((string) $s['button_link'], ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars((string) $s['button_text'], ENT_QUOTES, 'UTF-8') ?> ›
            </a>
        <?php endif; ?>
    </div>
</section>
