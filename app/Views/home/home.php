<?php
$sections = $sections ?? [];
?>

<?php foreach ($sections as $section): ?>
    <?php
    if (empty($section['is_published'])) {
        continue;
    }

    $type = $section['section_type'] ?? '';
    $title = $section['title'] ?? '';
    $content = $section['content'] ?? '';
    $imagePath = $section['image_path'] ?? '';
    $imageAlt = $section['image_alt'] ?? $title;
    $buttonText = $section['button_text'] ?? '';
    $buttonLink = $section['button_link'] ?? '#';
    ?>

    <?php if ($type === 'hero'): ?>
        <section class="home-hero">
            <?php if (!empty($imagePath)): ?>
                <div class="home-hero__bg">
                    <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($imageAlt) ?>">
                </div>
            <?php endif; ?>

            <div class="home-container home-hero__content">
                <h1><?= htmlspecialchars($title) ?></h1>
                <div class="home-richtext">
                    <?= $content ?>
                </div>

                <?php if (!empty($buttonText)): ?>
                    <a href="<?= htmlspecialchars($buttonLink) ?>" class="home-btn">
                        <?= htmlspecialchars($buttonText) ?>
                    </a>
                <?php endif; ?>
            </div>
        </section>

    <?php elseif ($type === 'text_block'): ?>
        <section class="home-text-block">
            <div class="home-container">
                <h2><?= htmlspecialchars($title) ?></h2>
                <div class="home-richtext">
                    <?= $content ?>
                </div>
            </div>
        </section>

    <?php elseif ($type === 'two_image_row'): ?>
        <section class="home-two-image-row">
            <div class="home-container">
                <div class="home-two-image-row__grid">
                    <?php foreach (($section['gallery_images'] ?? []) as $image): ?>
                        <div class="home-two-image-row__item">
                            <img
                                    src="<?= htmlspecialchars($image['file_path'] ?? '') ?>"
                                    alt="<?= htmlspecialchars($image['alt_text'] ?? 'Haarlem image') ?>"
                            >
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

    <?php elseif ($type === 'image_left' || $type === 'image_right'): ?>
        <section class="home-image-section <?= $type === 'image_right' ? 'home-image-section--right' : '' ?>">
            <div class="home-container home-image-section__grid">
                <div class="home-image-section__image">
                    <?php if (!empty($imagePath)): ?>
                        <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($imageAlt) ?>">
                    <?php endif; ?>
                </div>

                <div class="home-image-section__content">
                    <h2><?= htmlspecialchars($title) ?></h2>
                    <div class="home-richtext">
                        <?= $content ?>
                    </div>

                    <?php if (!empty($buttonText)): ?>
                        <a href="<?= htmlspecialchars($buttonLink) ?>" class="home-btn home-btn--secondary">
                            <?= htmlspecialchars($buttonText) ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

    <?php elseif ($type === 'cards_grid'): ?>
        <?php $cards = json_decode($content, true) ?: []; ?>
        <section class="home-cards">
            <div class="home-container">
                <h2><?= htmlspecialchars($title) ?></h2>

                <div class="home-cards__grid">
                    <?php foreach ($cards as $card): ?>
                        <article class="home-card">
                            <h3><?= htmlspecialchars($card['title'] ?? '') ?></h3>
                            <p><?= htmlspecialchars($card['text'] ?? '') ?></p>
                            <a href="<?= htmlspecialchars($card['link'] ?? '#') ?>">Read more</a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

    <?php elseif ($type === 'transport'): ?>
        <section class="home-transport">
            <div class="home-container">
                <h2><?= htmlspecialchars($title) ?></h2>
                <div class="home-richtext">
                    <?= $content ?>
                </div>

                <?php if (!empty($buttonText)): ?>
                    <a href="<?= htmlspecialchars($buttonLink) ?>" class="home-btn">
                        <?= htmlspecialchars($buttonText) ?>
                    </a>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endforeach; ?>