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

<style>
    .sh-banner {
        background-size: cover;
        background-position: center top;
        background-repeat: no-repeat;
        background-color: #1a0101;
        min-height: 560px;
        display: flex;
        align-items: flex-end;
        position: relative;
    }

    .sh-banner::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(
            to right,
            rgba(10, 2, 2, 0.82) 0%,
            rgba(10, 2, 2, 0.55) 60%,
            rgba(10, 2, 2, 0.1) 100%
        );
        pointer-events: none;
    }

    .sh-banner-inner {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        gap: 24px;
        padding: 64px 56px;
        max-width: 680px;
    }

    .sh-banner-title {
        text-transform: uppercase;
        line-height: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin: 0;
    }

    .sh-title--accent {
        font-size: 5rem;
        font-weight: 900;
        background: linear-gradient(90deg, #f2b53a 0%, #e24a2b 45%, #c21f1f 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        white-space: nowrap;
    }

    .sh-title--main {
        font-size: 3.8rem;
        font-weight: 900;
        color: #fff;
        white-space: nowrap;
    }

    .sh-banner-content {
        font-size: 1.1rem;
        line-height: 1.75;
        color: rgba(255, 255, 255, 0.9);
        max-width: 520px;
    }

    .sh-banner-content p { margin: 0; }

    .sh-banner-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 36px;
        border-radius: 999px;
        background: #7f1414;
        color: #fff;
        font-weight: 800;
        font-size: 1rem;
        text-decoration: none;
        width: fit-content;
        transition: background 0.2s ease;
    }

    .sh-banner-btn:hover { background: #c21f1f; }

    @media (max-width: 900px) {
        .sh-banner { min-height: 440px; }
        .sh-banner-inner { padding: 48px 32px; }
        .sh-title--accent { font-size: 3.8rem; }
        .sh-title--main  { font-size: 2.8rem; }
    }

    @media (max-width: 600px) {
        .sh-banner { min-height: 360px; }
        .sh-banner-inner { padding: 40px 20px; max-width: 100%; }
        .sh-title--accent { font-size: 2.8rem; }
        .sh-title--main  { font-size: 2rem; }
    }
</style>
