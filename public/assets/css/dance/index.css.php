body {
    background: #f7ebeb;
}

.dance-container {
    width: min(1200px, calc(100% - 48px));
    margin: 0 auto;
}

.dance-richtext p {
    margin: 0 0 16px;
    line-height: 1.7;
}

.dance-section-heading {
    margin-bottom: 32px;
}

.dance-section-heading h2 {
    font-size: 40px;
    color: #2b0707;
    margin: 0;
}

.dance-btn {
    display: inline-block;
    padding: 12px 20px;
    background: #8f1d1d;
    color: #fff;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 600;
    margin-top: 16px;
}

.dance-btn--dark {
    background: #1b1b1b;
}

.dance-link {
    color: #8f1d1d;
    text-decoration: none;
    font-weight: 600;
}

.dance-hero {
    position: relative;
    min-height: 620px;
    display: flex;
    align-items: center;
    color: #fff;
    overflow: hidden;
}

.dance-hero__bg {
    position: absolute;
    inset: 0;
}

.dance-hero__bg img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    filter: brightness(0.45);
}

.dance-hero__content {
    position: relative;
    z-index: 1;
    max-width: 760px;
    padding: 100px 0;
}

.dance-hero h1 {
    font-size: 72px;
    margin: 0 0 20px;
    color: #fff;
}

.dance-intro,
.dance-artists,
.dance-events,
.dance-tickets {
    padding: 80px 0;
}

.dance-intro h2 {
    font-size: 40px;
    color: #2b0707;
    margin-bottom: 20px;
}

.dance-artists__grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
}

.dance-artist-card {
    background: #fff;
    border-radius: 22px;
    padding: 28px;
    text-align: center;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
}

.dance-artist-card__image {
    width: 160px;
    height: 160px;
    margin: 0 auto 20px;
    border-radius: 50%;
    overflow: hidden;
}

.dance-artist-card__image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.dance-event-card {
    display: grid;
    grid-template-columns: 320px 1fr;
    margin-bottom: 28px;
    border-radius: 22px;
    overflow: hidden;
    background: #111;
    color: #fff;
}

.dance-event-card__image img {
    width: 100%;
    height: 100%;
    min-height: 260px;
    object-fit: cover;
    display: block;
}

.dance-event-card__content {
    padding: 32px;
}

.dance-event-card__content h3 {
    margin-top: 0;
    font-size: 28px;
}

.dance-event-card__meta {
    color: #d8d8d8;
    margin-bottom: 8px;
    font-weight: 600;
}

.dance-tickets__grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

.dance-ticket-card {
    background: #fff;
    border-radius: 22px;
    padding: 28px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
}

.dance-ticket-card__price {
    font-size: 28px;
    font-weight: 700;
    color: #8f1d1d;
}

@media (max-width: 900px) {
    .dance-hero h1 {
        font-size: 44px;
    }

    .dance-artists__grid,
    .dance-tickets__grid,
    .dance-event-card {
        grid-template-columns: 1fr;
    }

    .dance-event-card__image img {
        min-height: 220px;
    }
}
