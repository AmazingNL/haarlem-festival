<section class="wis-section">
    <div class="wis-inner">
        <?php if (!empty($s['title'])): ?>
            <h2 class="wis-title"><?= htmlspecialchars((string) $s['title'], ENT_QUOTES, 'UTF-8') ?></h2>
        <?php endif; ?>
        <?php if (!empty($s['content'])): ?>
            <div class="wis-body"><?= $s['content'] ?></div>
        <?php endif; ?>
    </div>
</section>

<style>
    .wis-section {
        background: #f6e9e9;
        padding: 72px 24px;
    }

    .wis-inner {
        max-width: 1100px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 64px;
        align-items: center;
    }

    .wis-title {
        font-size: 2.4rem;
        font-weight: 900;
        color: #6b1a1a;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        line-height: 1.15;
        margin: 0;
    }

    /* Classes used in TinyMCE content */
    .wis-body p {
        font-size: 1.05rem;
        line-height: 1.9;
        color: #3a1010;
        margin: 0 0 16px 0;
    }

    .wis-body p:last-child { margin-bottom: 0; }

    .wis-image {
        width: 100%;
        height: 380px;
        object-fit: cover;
        border-radius: 16px;
        box-shadow: 0 12px 32px rgba(65, 6, 6, 0.15);
        display: block;
    }

    @media (max-width: 900px) {
        .wis-inner {
            grid-template-columns: 1fr;
            gap: 36px;
        }
    }

    @media (max-width: 560px) {
        .wis-section {
            padding: 48px 16px;
        }

        .wis-title {
            font-size: 1.9rem;
        }

        .wis-body p {
            font-size: 1rem;
            line-height: 1.7;
        }

        .wis-image {
            height: 260px;
        }
    }
</style>
