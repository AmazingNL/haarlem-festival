<?php
$sectionImage = $s['section_image'] ?? '';
$img = '';

if (is_array($sectionImage)) {
    $firstImage = $sectionImage[0] ?? '';
    $img = is_array($firstImage) ? (string) ($firstImage['src'] ?? '') : (string) $firstImage;
} else {
    $img = (string) $sectionImage;
}

if (trim($img) === '') {
    $img = '/assets/images/yummy/yummy.jpg';
}

$buttonText = trim((string) ($s['button_text'] ?? ''));
$buttonLink = trim((string) ($s['button_link'] ?? ''));
if ($buttonLink !== '') {
    $buttonLink = '#restaurants';
}

?>

<section class=" welcome-banner">
    <?php if ($img !== ''): ?>
        <img class="welcome-banner-image" src="<?= htmlspecialchars($img, ENT_QUOTES, 'UTF-8') ?>" alt="Welcome banner image">
    <?php endif; ?>
    <div class="welcome-card-inner">
        <?php require __DIR__ . '/../layout/go_back_button.php'; ?>

        <?php if (!empty($s['title'])):
            $title = (string) $s['title'];
            $spacePos = strpos($title, ' ');
            if ($spacePos !== false) {
                $first = substr($title, 0, $spacePos);
                $rest = trim(substr($title, $spacePos));
            } else {
                $first = $title;
                $rest = '';
            }
            ?>
            <section class="welcome-card-title">
                <h1>
                    <span class="welcome-card-title--accent"><?= htmlspecialchars($first, ENT_QUOTES, 'UTF-8') ?></span>
                    <?php if ($rest !== ''): ?>

                        <span class="welcome-card-title--main"><?= htmlspecialchars($rest, ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                </h1>
            </section>
        <?php endif; ?>

        <?php if (!empty($s['introduction'])): ?>
            <article class="welcome-card-content">
                <?= $s['introduction'] ?>
            </article>
        <?php endif; ?>

        <?php if ($buttonText !== '' && $buttonLink !== ''): ?>
            <section class="primary-button">
                <a href="<?= htmlspecialchars($buttonLink, ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($buttonText, ENT_QUOTES, 'UTF-8') ?>
                    <svg class="icon-explore icon-fixed" viewBox="0 0 24 24">
                        <path d="M8 4l8 8-8 8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </section>

        <?php endif; ?>
    </div>
</section>