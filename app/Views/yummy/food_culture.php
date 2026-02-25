<section class="food-culture-section">
    <?php if (!empty($s['title'])): ?>
        <h2 class="food-culture-kicker"><?= htmlspecialchars((string) $s['title']) ?></h2>
    <?php endif; ?>

    <div class="food-culture-container">
        <?php if (!empty($s['content'])): ?>
            <?= $s['content'] ?>
        <?php endif; ?>
    </div>
</section>

<style>
    .food-culture-section {
        background: #f6e9e9;
        padding: 48px 16px 72px;
        color: #231815;
    }

    .food-culture-kicker {
        text-align: center;
        font-size: 40px;
        letter-spacing: 0.2em;
        color: #6b1a1a;
        margin: 0 0 28px 0;
    }

    .food-culture-container {
        max-width: 1100px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 42px;
        align-items: start;
    }

    /* You will add these wrappers INSIDE TinyMCE */
    .food-culture-text h3 {
        margin-top: 0;
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
</style>