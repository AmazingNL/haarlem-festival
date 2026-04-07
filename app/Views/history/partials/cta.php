<?php
$backgroundImage = htmlspecialchars((string) ($s['background_image'] ?? ''), ENT_QUOTES, 'UTF-8');
?>
<section class="history-cta" id="history-cta" style="background-image: url('<?= $backgroundImage ?>');">
    <div class="history-container">
        <div class="history-cta__content">
            <?php if (!empty($s['eyebrow'])): ?>
                <p class="history-eyebrow history-eyebrow--light"><?= htmlspecialchars((string) $s['eyebrow'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

            <h2 class="history-cta__title">
                <span class="history-cta__title-main"><?= htmlspecialchars((string) ($s['title_line_one'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                <em class="history-cta__title-accent"><?= htmlspecialchars((string) ($s['title_line_two'] ?? ''), ENT_QUOTES, 'UTF-8') ?></em>
            </h2>

            <?php if (!empty($s['body'])): ?>
                <p class="history-cta__body"><?= $historyText($s['body']) ?></p>
            <?php endif; ?>

            <div class="history-cta__actions">
                <?php if (!empty($s['primary_button_text'])): ?>
                    <a class="history-btn history-btn--gold" href="<?= $historyUrl($s['primary_button_link']) ?>">
                        <span class="history-btn__label"><?= htmlspecialchars((string) $s['primary_button_text'], ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="history-btn__arrow" aria-hidden="true">&rarr;</span>
                    </a>
                <?php endif; ?>
                <?php if (!empty($s['secondary_button_text'])): ?>
                    <a class="history-btn history-btn--secondary-light" href="<?= $historyUrl($s['secondary_button_link']) ?>">
                        <span class="history-btn__label"><?= htmlspecialchars((string) $s['secondary_button_text'], ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="history-btn__arrow" aria-hidden="true">&rarr;</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
