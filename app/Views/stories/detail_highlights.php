<?php
$highlights         = is_array($show['highlights'] ?? null) ? $show['highlights'] : [];
$highlightsTitle    = (string) ($show['highlights_title']    ?? 'Story Highlights');
$highlightsSubtitle = (string) ($show['highlights_subtitle'] ?? '');
?>
<section class="sd-highlights-section">
    <div class="sd-highlights-inner">
        <h2 class="sd-section-title"><?= $he($highlightsTitle) ?></h2>
        <?php if ($highlightsSubtitle !== ''): ?>
            <p class="sd-section-subtitle"><?= $he($highlightsSubtitle) ?></p>
        <?php endif; ?>

        <div class="sd-highlights-grid">
            <?php foreach ($highlights as $item): ?>
                <?php
                $hImage = (string) ($item['image'] ?? '');
                $hTag   = (string) ($item['tag']   ?? '');
                $hTitle = (string) ($item['title'] ?? '');
                $hText  = (string) ($item['text']  ?? '');
                if ($hTitle === '' && $hImage === '') continue;
                ?>
                <article class="sd-highlight-card">
                    <?php if ($hImage !== ''): ?>
                        <div class="sd-highlight-image-wrap">
                            <img class="sd-highlight-image"
                                 src="<?= $he($hImage) ?>"
                                 alt="<?= $he($hTitle) ?>"
                                 loading="lazy">
                            <?php if ($hTag !== ''): ?>
                                <span class="sd-highlight-tag"><?= $he($hTag) ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="sd-highlight-body">
                        <?php if ($hTitle !== ''): ?>
                            <h3 class="sd-highlight-title"><?= $he($hTitle) ?></h3>
                        <?php endif; ?>
                        <?php if ($hText !== ''): ?>
                            <p class="sd-highlight-text"><?= $he($hText) ?></p>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
