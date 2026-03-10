    <section class="taste-section">
        <div class="taste-inner">
            <?= $s['content'] ?? '' ?>
        </div>
    </section>

<style>
    .taste-section {
        background: #e5daa1;
        padding: 36px 12px;
    }

    .taste-inner {
        max-width: 1200px;
        margin: 0 auto;
    }

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
    }

    .carousel {
        overflow-x: auto;
        overflow-y: hidden;
        display: flex;
        gap: 18px;
        padding: 28px;
        background: #6e6112;
        border-radius: 20px;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        width: 100%;
        box-sizing: border-box;
    }

    .carousel {
        -ms-overflow-style: none;
        /* IE and Edge */
        scrollbar-width: none;
        /* Firefox */
    }

    .carousel::-webkit-scrollbar {
        display: none;
        height: 0;
    }

    .card {
        flex: 0 0 320px;
        scroll-snap-align: center;
        display: flex;
        flex-direction: column;
        gap: 8px;
        align-items: flex-start;
        color: #fff;
    }

    .card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .card-caption {
        font-size: 12px;
        color: #f7eed8;
        font-weight: 600;
        background: rgba(0, 0, 0, 0.08);
        padding: 6px 10px;
        border-radius: 8px;
    }

    .carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 44px;
        height: 44px;
        border-radius: 50%;
        border: none;
        background: #1a0101;
        color: #fff;
        font-size: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 5;
    }

    .carousel-prev {
        left: -18px;
    }

    .carousel-next {
        right: -18px;
    }

    @media (max-width: 800px) {
        .card {
            flex: 0 0 260px;
        }

        .card img {
            height: 150px;
        }

        .carousel-prev {
            left: 6px;
        }

        .carousel-next {
            right: 6px;
        }
    }
</style>

<script>
    (function () {
        const carousel = document.querySelector('.carousel');
        const prev = document.querySelector('.carousel-prev');
        const next = document.querySelector('.carousel-next');
        if (!carousel) return;
        const step = Math.round(carousel.clientWidth * 0.6);
        prev && prev.addEventListener('click', () => carousel.scrollBy({ left: -step, behavior: 'smooth' }));
        next && next.addEventListener('click', () => carousel.scrollBy({ left: step, behavior: 'smooth' }));
    })();
</script>