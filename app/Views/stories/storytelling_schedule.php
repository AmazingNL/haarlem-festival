<section class="sched-section">
    <div class="sched-inner">
        <?php if (!empty($s['title'])): ?>
            <h2 class="sched-title"><?= htmlspecialchars((string) $s['title'], ENT_QUOTES, 'UTF-8') ?></h2>
        <?php endif; ?>

        <nav class="sched-tabs" aria-label="Schedule days">
            <button class="sched-tab active" data-target="thu">Thursday</button>
            <button class="sched-tab" data-target="fri">Friday</button>
            <button class="sched-tab" data-target="sat">Saturday</button>
            <button class="sched-tab" data-target="sun">Sunday</button>
        </nav>

        <?php if (!empty($s['content'])): ?>
            <div class="sched-body"><?= $s['content'] ?></div>
        <?php endif; ?>
    </div>
</section>

<style>
    .sched-section {
        background: #f6e9e9;
        padding: 72px 24px;
    }

    .sched-inner {
        max-width: 960px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 36px;
    }

    .sched-title {
        font-size: 2.2rem;
        font-weight: 900;
        color: #6b1a1a;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        text-align: center;
        margin: 0;
    }

    .sched-tabs {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .sched-tab {
        padding: 11px 28px;
        border-radius: 999px;
        border: 2px solid #7f1414;
        background: transparent;
        color: #7f1414;
        font-weight: 700;
        font-family: Poppins, system-ui, sans-serif;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .sched-tab.active,
    .sched-tab:hover {
        background: #7f1414;
        color: #fff;
    }

    /* Day panels */
    .sched-body .sched-day { display: none; }
    .sched-body .sched-day.active { display: flex; flex-direction: column; gap: 16px; }

    .sched-cards {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .sched-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 3px 16px rgba(65, 6, 6, 0.09);
        overflow: hidden;
        border-left: 4px solid #7f1414;
    }

    .sched-card-body {
        padding: 22px 28px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .sched-card-time {
        font-size: 0.82rem;
        font-weight: 800;
        color: #7f1414;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .sched-card-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #3b0b0b;
        margin: 0;
        line-height: 1.3;
    }

    .sched-card-meta {
        font-size: 0.88rem;
        color: #777;
    }

    @media (max-width: 600px) {
        .sched-title { font-size: 1.6rem; }
        .sched-card-body { padding: 18px 20px; }
    }

    @media (max-width: 420px) {
        .sched-section { padding: 48px 14px; }
        .sched-tabs { gap: 8px; }
        .sched-tab {
            width: calc(50% - 4px);
            padding: 10px 12px;
            text-align: center;
            font-size: 0.85rem;
        }

        .sched-card-title { font-size: 1.05rem; }
    }
</style>
