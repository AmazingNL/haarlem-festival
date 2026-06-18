<?php
$storyImage      = (string) ($show['story_image']      ?? '');
$fullDescription = (string) ($show['full_description'] ?? $show['show_description'] ?? '');
$experience      = is_array($show['what_you_experience'] ?? null) ? $show['what_you_experience'] : [];
?>
<section class="sd-content-section">
    <div class="sd-content-inner">
        <div class="sd-content-grid <?= $storyImage === '' ? 'sd-content-grid--no-image' : '' ?>">

            <?php if ($storyImage !== ''): ?>
                <div class="sd-story-image-wrap">
                    <img class="sd-story-image"
                         src="<?= $he($storyImage) ?>"
                         alt="<?= $he((string) ($show['show_title'] ?? 'Story')) ?>">
                </div>
            <?php endif; ?>

            <div class="sd-story-text">
                <?php if ($fullDescription !== ''): ?>
                    <p class="sd-description"><?= $he($fullDescription) ?></p>
                <?php endif; ?>

                <?php if (!empty($experience)): ?>
                    <div class="sd-experience">
                        <h2 class="sd-experience-title">What You'll Experience</h2>
                        <div class="sd-experience-grid">
                            <?php foreach ($experience as $item): ?>
                                <?php
                                $expTitle = (string) ($item['title']    ?? '');
                                $expSub   = (string) ($item['subtitle'] ?? '');
                                if ($expTitle === '') continue;
                                ?>
                                <div class="sd-experience-item">
                                    <span class="sd-experience-check" aria-hidden="true">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    </span>
                                    <div>
                                        <p class="sd-experience-name"><?= $he($expTitle) ?></p>
                                        <?php if ($expSub !== ''): ?>
                                            <p class="sd-experience-sub"><?= $he($expSub) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>
