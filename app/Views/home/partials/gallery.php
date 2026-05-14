<?php
$galleryId = 'home-gallery-' . (int) ($s['section_id'] ?? 0);
$items = [
    ['label' => $s['item_one_label'] ?? '', 'image' => $s['item_one_image'] ?? '', 'alt' => $s['item_one_alt'] ?? ''],
    ['label' => $s['item_two_label'] ?? '', 'image' => $s['item_two_image'] ?? '', 'alt' => $s['item_two_alt'] ?? ''],
    ['label' => $s['item_three_label'] ?? '', 'image' => $s['item_three_image'] ?? '', 'alt' => $s['item_three_alt'] ?? ''],
    ['label' => $s['item_four_label'] ?? '', 'image' => $s['item_four_image'] ?? '', 'alt' => $s['item_four_alt'] ?? ''],
];
$items = array_values(array_filter($items, static fn(array $item): bool => trim((string) ($item['image'] ?? '')) !== ''));
?>

<?php if ($items !== []): ?>
    <section class="home-gallery-section">
        <div class="home-container home-gallery">
            <button class="home-gallery__button home-gallery__button--prev" type="button"
                data-gallery-target="<?= htmlspecialchars($galleryId, ENT_QUOTES, 'UTF-8') ?>" data-gallery-direction="-1"
                aria-label="Show previous places">
                &lsaquo;
            </button>

            <div class="home-gallery__viewport" id="<?= htmlspecialchars($galleryId, ENT_QUOTES, 'UTF-8') ?>">
                <div class="home-gallery__track">
                    <?php foreach ($items as $item): ?>
                        <article class="home-gallery__card">
                            <?php if ($item['label'] !== ''): ?>
                                <p class="home-gallery__label"><?= htmlspecialchars((string) $item['label'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endif; ?>

                            <img src="<?= htmlspecialchars((string) $item['image'], ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars((string) ($item['alt'] !== '' ? $item['alt'] : $item['label']), ENT_QUOTES, 'UTF-8') ?>">
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>

            <button class="home-gallery__button home-gallery__button--next" type="button"
                data-gallery-target="<?= htmlspecialchars($galleryId, ENT_QUOTES, 'UTF-8') ?>" data-gallery-direction="1"
                aria-label="Show more places">
                &rsaquo;
            </button>
        </div>
    </section>
<?php endif; ?>
