<?php
$programItems = is_array($programItems ?? null) ? $programItems : [];
$programTotal = (float) ($programTotal ?? 0);

$formatMoney = static function (float $amount): string {
    return 'EUR ' . number_format($amount, 2, '.', ',');
};
?>

<div class="history-page history-program-page">
    <section class="history-tour-hero history-program-hero">
        <div class="history-container history-tour-hero__inner">
            <p class="history-tour-hero__eyebrow">Saved Selections</p>
            <h1 class="history-tour-hero__title">My Program</h1>
            <p class="history-tour-hero__intro">Review your selected History tour bookings here.</p>
        </div>
    </section>

    <section class="history-tour-board history-program-board">
        <div class="history-container">
            <?php if (empty($programItems)): ?>
                <article class="history-tour-card history-program-empty">
                    <h2>No bookings yet</h2>
                    <p>Your selected History tour tickets will appear here after you add them from the Book Tour page.</p>
                    <a class="history-btn history-btn--dark" href="/history/book-tour">Go to Book Tour</a>
                </article>
            <?php else: ?>
                <div class="history-program-layout">
                    <div class="history-program-list">
                        <?php foreach ($programItems as $item): ?>
                            <article class="history-tour-card history-program-item">
                                <div class="history-program-item__head">
                                    <div>
                                        <p class="history-program-item__eyebrow">History Tour</p>
                                        <h2><?= htmlspecialchars((string) ($item['title'] ?? 'History Book Tour'), ENT_QUOTES, 'UTF-8') ?></h2>
                                    </div>

                                    <form method="post" action="/program/remove">
                                        <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) ($csrf ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="item_id" value="<?= htmlspecialchars((string) ($item['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                        <button type="submit" class="history-program-item__remove">Remove</button>
                                    </form>
                                </div>

                                <dl class="history-program-item__details">
                                    <div>
                                        <dt>Selection</dt>
                                        <dd><?= htmlspecialchars((string) ($item['selection_text'] ?? ''), ENT_QUOTES, 'UTF-8') ?></dd>
                                    </div>
                                    <div>
                                        <dt>Ticket</dt>
                                        <dd><?= htmlspecialchars((string) ($item['ticket_summary_text'] ?? ''), ENT_QUOTES, 'UTF-8') ?></dd>
                                    </div>
                                    <div>
                                        <dt>Total</dt>
                                        <dd><?= htmlspecialchars($formatMoney((float) ($item['total_price'] ?? 0)), ENT_QUOTES, 'UTF-8') ?></dd>
                                    </div>
                                </dl>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <aside class="history-tour-card history-program-total">
                        <h2>Program Total</h2>
                        <p><?= count($programItems) ?> item(s) selected</p>
                        <strong><?= htmlspecialchars($formatMoney($programTotal), ENT_QUOTES, 'UTF-8') ?></strong>
                        <a class="history-btn history-btn--dark" href="/history/book-tour">Add another tour</a>
                    </aside>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>
