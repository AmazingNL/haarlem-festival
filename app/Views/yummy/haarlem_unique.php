<section class="haarlem-unique-section">
    <div class="haarlem-unique-wrap">
        <?= $s['content'] ?? '' ?>
    </div>
</section>

<style>
    .haarlem-unique-section {
        background: #f6e9e9;
        padding: 40px 16px;
    }

    .haarlem-unique-wrap {
        max-width: 1100px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 32px;
        align-items: center;
    }

    .unique-images {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .unique-img {
        width: 100%;
        height: 420px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        display: block;
    }

    .unique-text h3 {
        font-family: 'Bebas Neue', system-ui, sans-serif;
        color: #6b1a1a;
        font-size: 28px;
        margin: 0 0 14px 0;
    }

    .unique-text p {
        font-family: 'Poppins', system-ui, sans-serif;
        color: #2f1f1f;
        line-height: 1.7;
        margin: 0;
        font-size: 15px;
    }

    @media (max-width: 900px) {
        .haarlem-unique-wrap {
            grid-template-columns: 1fr;
        }

        .unique-images {
            grid-template-columns: 1fr 1fr;
        }

        .unique-img {
            height: 320px;
        }
    }

    @media (max-width: 520px) {
        .unique-images {
            grid-template-columns: 1fr;
        }

        .unique-img {
            height: 240px;
        }

        .unique-text h3 {
            font-size: 22px;
        }
    }
</style>