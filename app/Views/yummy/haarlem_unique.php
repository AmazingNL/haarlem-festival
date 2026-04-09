<?php
$title = trim((string) ($s['title'] ?? ''));
$content = (string) ($s['content'] ?? '');

$sectionImage = $s['section_image'] ?? [];
$images = [];

if (is_array($sectionImage)) {
    foreach ($sectionImage as $image) {
        if (count($images) >= 2) {
            break;
        }

        if (is_array($image)) {
            $src = trim((string) ($image['src'] ?? ''));
            if ($src === '') {
                continue;
            }

            $images[] = [
                'src' => $src,
                'alt' => trim((string) ($image['alt'] ?? '')),
                'caption' => trim((string) ($image['caption'] ?? '')),
            ];
            continue;
        }

        $src = trim((string) $image);
        if ($src !== '') {
            $images[] = [
                'src' => $src,
                'alt' => '',
                'caption' => '',
            ];
        }
    }
}

?>

<div class="haarlem-unique">
    <?php if (!empty($images)): ?>
        <div class="haarlem-unique-media">
            <div class="haarlem-unique-gallery">
                <?php foreach ($images as $img): ?>
                    <?php $caption = trim((string) ($img['caption'] ?? '')); ?>
                    <figure class="haarlem-unique-figure">
                        <img src="<?= htmlspecialchars((string) ($img['src'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                            alt="<?= htmlspecialchars((string) (($img['alt'] ?? '') !== '' ? $img['alt'] : 'Haarlem image'), ENT_QUOTES, 'UTF-8') ?>"
                            class="haarlem-unique-image" loading="lazy">
                        <?php if ($caption !== ''): ?>
                            <figcaption class="haarlem-unique-caption">
                                <?= htmlspecialchars($caption, ENT_QUOTES, 'UTF-8') ?>
                            </figcaption>
                        <?php endif; ?>
                    </figure>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <article class="haarlem-unique-copy-wrap<?= empty($images) ? ' haarlem-unique-copy-wrap--full' : '' ?>">
        <h2 class="haarlem-unique-title">
            <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>
        </h2>

        <div class="haarlem-unique-copy">
            <?= $content ?>
        </div>
    </article>
</div>

<style>

    .haarlem-unique {
        margin: auto;
        max-width: 1240px;
        display: grid;
        grid-template-columns: minmax(360px, 1fr) minmax(360px, 1fr);
        gap: 30px;
        align-items: start;
    }

    .haarlem-unique-gallery {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .haarlem-unique-figure {
        margin: 0;
    }

    .haarlem-unique-image {
        display: block;
        width: 100%;
        height: 350px;
        object-fit: cover;
        border-radius: 4px;
    }

    .haarlem-unique-caption {
        margin-top: 6px;
        font-size: 12px;
        color: #6c3d42;
        line-height: 1.35;
    }

    .haarlem-unique-copy-wrap {
        padding-top: 2px;
    }

    .haarlem-unique-copy-wrap--full {
        max-width: 760px;
    }

    .haarlem-unique-title {
        margin: 0;
        color: #351318;
        font-size: 2rem;
        line-height: 1.12;
        letter-spacing: -0.01em;
    }

    .haarlem-unique-copy {
        margin-top: 12px;
        color: #4f2b30;
        font-size: 17px;
        line-height: 1.95;
        max-width: 58ch;
    }

    .haarlem-unique-copy p {
        margin: 0 0 0.95rem;
    }

    .haarlem-unique-copy p:last-child {
        margin-bottom: 0;
    }

    @media (max-width: 1024px) {
        .haarlem-unique-grid {
            grid-template-columns: 1fr;
            gap: 18px;
        }

        .haarlem-unique-title {
            font-size: 34px;
        }

        .haarlem-unique-copy {
            max-width: none;
            line-height: 1.8;
        }
    }

    @media (max-width: 640px) {
        .haarlem-unique-section {
            padding: 18px 0;
        }

        .haarlem-unique-inner {
            padding: 0 14px;
        }

        .haarlem-unique-gallery {
            grid-template-columns: 1fr;
        }

        .haarlem-unique-image {
            height: 240px;
        }

        .haarlem-unique-title {
            font-size: 29px;
        }

        .haarlem-unique-copy {
            font-size: 16px;
        }
    }
</style>