<section class="sp-section">
    <div class="sp-inner">
        <?php if (!empty($s['title'])): ?>
            <h2 class="sp-title"><?= htmlspecialchars((string) $s['title'], ENT_QUOTES, 'UTF-8') ?></h2>
        <?php endif; ?>
        <?php if (!empty($s['content'])): ?>
            <div class="sp-body"><?= $s['content'] ?></div>
        <?php endif; ?>
    </div>
</section>

<style>
    .sp-section {
        background: #1a0101;
        padding: 72px 24px;
    }

    .sp-inner {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 40px;
    }

    .sp-title {
        font-size: 2.2rem;
        font-weight: 900;
        color: var(--color-accent-yellow);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        text-align: center;
        margin: 0;
    }

    /* Mosaic grid — used inside TinyMCE content */
    .sp-body .sp-mosaic {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        grid-template-rows: 220px 220px;
        gap: 14px;
    }

    .sp-body .sp-mosaic img:first-child {
        grid-row: 1 / 3;
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 14px;
    }

    .sp-body .sp-mosaic img:not(:first-child) {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 14px;
    }

    @media (max-width: 768px) {
        .sp-body .sp-mosaic {
            grid-template-columns: 1fr 1fr;
            grid-template-rows: auto;
        }
        .sp-body .sp-mosaic img:first-child { grid-row: auto; height: 180px; }
        .sp-body .sp-mosaic img:not(:first-child) { height: 180px; }
    }

    @media (max-width: 480px) {
        .sp-section {
            padding: 48px 16px;
        }

        .sp-title {
            font-size: 1.8rem;
        }

        .sp-inner {
            gap: 26px;
        }

        .sp-body .sp-mosaic { grid-template-columns: 1fr; }
        .sp-body .sp-mosaic img:first-child,
        .sp-body .sp-mosaic img:not(:first-child) {
            height: 160px;
        }
    }
</style>
