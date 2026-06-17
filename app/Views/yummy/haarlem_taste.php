<?php
$sectionImage = $s['section_image'] ?? '';
$images = [];

if (is_array($sectionImage)) {
    foreach ($sectionImage as $image) {
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

        $imagePath = trim((string) $image);
        if ($imagePath !== '') {
            $images[] = [
                'src' => $imagePath,
                'alt' => '',
                'caption' => '',
            ];
        }
    }
}
?>

<section class="taste-section">
    <div class="carousel-wrap">
        <button class="carousel-btn carousel-prev">&#10094;</button>
        <div class="carousel">
            <?php if (!empty($images)): ?>
                <?php foreach ($images as $img): ?>
                    <figure class="taste-card">
                        <img src="<?= htmlspecialchars((string) ($img['src'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                            alt="<?= htmlspecialchars((string) (($img['alt'] ?? '') !== '' ? $img['alt'] : 'Gallery image'), ENT_QUOTES, 'UTF-8') ?>"
                            loading="lazy">
                        <?php if (($img['caption'] ?? '') !== ''): ?>
                            <figcaption><?= htmlspecialchars((string) $img['caption'], ENT_QUOTES, 'UTF-8') ?></figcaption>
                        <?php endif; ?>
                    </figure>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button class="carousel-btn carousel-next">&#10095;</button>
    </div>
</section>

<style>
    .taste-section {
        margin-top: 40px;
        background: #e5daa1;
        padding: 36px 12px;
    }

    /* .taste-inner {
        max-width: 1200px;
        margin: 0 auto;
    } */

    .taste-title {
        text-align: center;
        margin: 4px 0 12px;
    }

    .taste-copy {
        text-align: center;
        color: #2d1f1f;
        max-width: 900px;
        margin: 0 auto 18px;
    }

    .taste-list {
        list-style: disc;
        display: inline-block;
        text-align: left;
        padding-left: 20px;
        margin: 12px 0;
    }

    .taste-note {
        margin-top: 8px;
    }

    .carousel-wrap {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 24px;
    }

    .carousel {
        overflow: hidden;
        display: flex;
        gap: 16px;
        padding: 20px;
        background: #8b7d3a;
        border: 3px dotted #c9b558;
        border-radius: 12px;
        scroll-snap-type: x mandatory;
        width: 100%;
        box-sizing: border-box;
        scroll-behavior: smooth;
    }

    .carousel {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .carousel::-webkit-scrollbar {
        display: none;
    }

    .card {
        flex: 0 0 calc(25% - 12px);
        min-width: 200px;
        scroll-snap-align: center;
        display: flex;
        flex-direction: column;
        gap: 8px;
        align-items: center;
        color: #fff;
    }

    .card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .card-caption {
        font-size: 11px;
        color: #f7eed8;
        font-weight: 600;
        text-align: center;
        line-height: 1.3;
        padding: 0 4px;
    }

    .taste-card {
        flex: 0 0 calc(25% - 12px);
        min-width: 200px;
        scroll-snap-align: center;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 8px;
        align-items: center;
        color: #fff;
    }

    .taste-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .taste-card figcaption {
        font-size: 11px;
        color: #f7eed8;
        font-weight: 600;
        text-align: center;
        line-height: 1.3;
        padding: 0 4px;
    }

    .carousel-btn {
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #2d1f1f;
        background: #fff;
        color: #2d1f1f;
        font-size: 18px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 5;
        transition: all 0.3s ease;
    }

    .carousel-btn:hover {
        background: #2d1f1f;
        color: #fff;
    }

    .carousel-prev {
        order: -1;
    }

    @media (max-width: 1000px) {
        .card {
            flex: 0 0 calc(33.33% - 11px);
        }

        .carousel {
            gap: 12px;
            padding: 16px;
        }

        .card img {
            height: 140px;
        }

        .carousel-btn {
            width: 36px;
            height: 36px;
            font-size: 16px;
        }

        .taste-card {
            flex: 0 0 calc(33.33% - 11px);
        }

        .taste-card img {
            height: 140px;
        }
    }

    @media (max-width: 700px) {
        .card {
            flex: 0 0 calc(50% - 8px);
        }

        .card img {
            height: 120px;
        }

        .carousel-btn {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }

        .carousel-wrap {
            gap: 12px;
        }

        .taste-card {
            flex: 0 0 calc(50% - 8px);
        }

        .taste-card img {
            height: 120px;
        }
    }

    @media (max-width: 500px) {
        .taste-section {
            padding: 28px 8px;
        }

        .carousel-wrap {
            gap: 8px;
        }

        .carousel {
            gap: 8px;
            padding: 10px;
        }

        .card,
        .taste-card {
            flex: 0 0 calc(100% - 2px);
            min-width: 0;
        }

        .card img,
        .taste-card img {
            height: 180px;
        }

        .carousel-btn {
            width: 28px;
            height: 28px;
            font-size: 13px;
        }
    }
</style>

<script>
    (function () {
        const carousel = document.querySelector('.carousel');
        const prev = document.querySelector('.carousel-prev');
        const next = document.querySelector('.carousel-next');

        if (!carousel || !prev || !next) return;

        const scrollAmount = 280; // ~card width + gap

        prev.addEventListener('click', () => {
            carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        });

        next.addEventListener('click', () => {
            carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        });
    })();
</script>