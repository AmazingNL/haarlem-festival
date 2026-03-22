<section class="food-culture-section">
    <?php if (!empty($s['title'])): ?>
        <h1 class="food-culture-kicker"><?= htmlspecialchars((string) $s['title']) ?></h1>
    <?php endif; ?>

    <h3 class="food-culture-kicker"><?= htmlspecialchars((string) $s['sub_title'] ?? '') ?></h3>

    <div class="food-culture-container">
        <?= (string) ($s['article'] ?? '') ?>
    </div>
</section>

<style>
    .food-culture-section {
        background: var(--color-background);
        padding: 48px 16px 0;
        color: var(--color-text-header);
    }

    .food-culture-kicker {
        text-align: center;
        font-size: 40px;
        letter-spacing: 0.1em;
        color: var(--color-text-header);
        margin: 0 0 28px 0;
    }

    .food-culture-container {
        max-width: 1100px;
        margin: 50px auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 42px;
        align-items: start;
    }

    /* You will add these wrappers INSIDE TinyMCE */
    .food-culture-text h3 {
        margin: 0;
        color: var(----color-text-header);
    }

    .food-culture-text p {
        margin: 10px 0;
        line-height: 2.2em;
    }

    .food-culture-gallery {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
    }

    .food-culture-gallery figure {
        margin: 0;
        text-align: center;
    }

    .food-culture-gallery img {
        width: 100%;
        height: 190px;
        object-fit: cover;
        border-radius: 18px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .food-culture-gallery figcaption {
        margin-top: 10px;
        font-size: 14px;
        color: #6b1a1a;
    }

    /* Responsive adjustments */
    @media (max-width: 900px) {

        .food-culture-container {
            margin: 50px auto;
            display: grid;
            grid-template-columns: 1fr;
            gap: 42px;
            align-items: start;
        }
    }
</style>