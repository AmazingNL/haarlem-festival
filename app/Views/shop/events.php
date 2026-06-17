<?php
$events = is_array($events ?? null) ? $events : [];
$activeTag = trim((string) ($activeTag ?? ''));

$formatMoney = static fn(float $amount): string => 'EUR ' . number_format($amount, 2, '.', ',');
$hasRenderableImage = static function (?string $path): bool {
    $imagePath = trim((string) $path);
    if ($imagePath === '') {
        return false;
    }

    if (preg_match('#^https?://#i', $imagePath)) {
        return true;
    }

    if (!str_starts_with($imagePath, '/')) {
        return false;
    }

    return is_file(dirname(__DIR__, 3) . '/public' . $imagePath);
};
$formatEventDateTime = static function (?string $startDateTime, ?string $endDateTime): string {
    $startTimestamp = $startDateTime ? strtotime($startDateTime) : false;
    $endTimestamp = $endDateTime ? strtotime($endDateTime) : false;

    if (!$startTimestamp) {
        return '-';
    }

    $dateLabel = date('D d M Y', $startTimestamp);
    $startTimeLabel = date('H:i', $startTimestamp);

    if (!$endTimestamp) {
        return $dateLabel . ' | ' . $startTimeLabel;
    }

    return $dateLabel . ' | ' . $startTimeLabel . ' - ' . date('H:i', $endTimestamp);
};
$tagLabel = $activeTag !== '' ? ucwords(str_replace(['-', '_'], ' ', $activeTag)) : '';
?>

<section class="shop-page">
    <div class="shop-container">
        <header class="shop-hero">
            <p class="shop-eyebrow">Festival events</p>
            <h1>Build Your Program</h1>
            <p>Add tickets from every available event to My Program, then register or log in when you're ready to pay.</p>

            <?php if ($activeTag !== ''): ?>
                <div class="shop-hero__actions">
                    <a href="/events" class="shop-button shop-button--secondary">Show all events</a>
                </div>
            <?php endif; ?>
        </header>

        <?php if ($events === []): ?>
            <article class="shop-card shop-card--empty">
                <div class="shop-card__body">
                    <h2>No events found</h2>
                    <p>
                        <?php if ($tagLabel !== ''): ?>
                            There are no published <?= htmlspecialchars($tagLabel, ENT_QUOTES, 'UTF-8') ?> events available right now.
                        <?php else: ?>
                            There are no published festival events available right now.
                        <?php endif; ?>
                    </p>
                </div>
            </article>
        <?php else: ?>
            <div class="shop-grid">
                <?php foreach ($events as $event): ?>
                    <?php
                    $ticketTypes = is_array($event['ticket_types'] ?? null) ? $event['ticket_types'] : [];
                    $imagePath = trim((string) ($event['image_path'] ?? ''));
                    $minTicketPrice = null;
                    foreach ($ticketTypes as $ticketType) {
                        $price = (float) ($ticketType['price'] ?? 0);
                        $minTicketPrice = $minTicketPrice === null ? $price : min($minTicketPrice, $price);
                    }
                    ?>
                    <article class="shop-card">
                        <?php if ($hasRenderableImage($imagePath)): ?>
                            <div class="shop-card__image">
                                <img src="<?= htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8') ?>"
                                    alt="<?= htmlspecialchars((string) ($event['title'] ?? 'Festival Event'), ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                        <?php else: ?>
                            <div class="shop-card__placeholder">
                                <?= htmlspecialchars((string) ($event['title'] ?? 'Festival Event'), ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>

                        <div class="shop-card__body">
                            <p class="shop-card__eyebrow"><?= htmlspecialchars((string) ($event['category_label'] ?? 'Festival'), ENT_QUOTES, 'UTF-8') ?></p>
                            <h2><?= htmlspecialchars((string) ($event['title'] ?? 'Festival Event'), ENT_QUOTES, 'UTF-8') ?></h2>

                            <p class="shop-card__meta">
                                <?= htmlspecialchars($formatEventDateTime(
                                    (string) ($event['start_datetime'] ?? ''),
                                    (string) ($event['end_datetime'] ?? '')
                                ), ENT_QUOTES, 'UTF-8') ?>
                            </p>

                            <p class="shop-card__meta">
                                <?= htmlspecialchars((string) ($event['location_name'] ?? 'Haarlem'), ENT_QUOTES, 'UTF-8') ?>
                                <?php if (!empty($event['location_city'])): ?>
                                    , <?= htmlspecialchars((string) $event['location_city'], ENT_QUOTES, 'UTF-8') ?>
                                <?php endif; ?>
                            </p>

                            <?php if (!empty($event['description'])): ?>
                                <p class="shop-card__meta"><?= htmlspecialchars((string) $event['description'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endif; ?>

                            <?php if ($minTicketPrice !== null): ?>
                                <p class="shop-card__meta"><strong>From <?= htmlspecialchars($formatMoney($minTicketPrice), ENT_QUOTES, 'UTF-8') ?></strong></p>
                            <?php endif; ?>

                            <?php if ($ticketTypes === []): ?>
                                <p class="shop-card__note">Ticket sales for this event are not configured yet.</p>
                            <?php else: ?>
                                <div class="shop-ticket-list">
                                    <?php foreach ($ticketTypes as $ticketType): ?>
                                        <?php $availableSeats = max(0, (int) ($ticketType['max_quantity'] ?? 0)); ?>
                                        <?php if ($availableSeats < 1): ?>
                                            <div class="shop-ticket-form">
                                                <div class="shop-ticket-form__details">
                                                    <strong><?= htmlspecialchars((string) ($ticketType['name'] ?? 'Ticket'), ENT_QUOTES, 'UTF-8') ?></strong>
                                                    <span><?= htmlspecialchars($formatMoney((float) ($ticketType['price'] ?? 0)), ENT_QUOTES, 'UTF-8') ?></span>
                                                    <small>Sold out</small>
                                                </div>
                                                <div class="shop-ticket-form__quantity">
                                                    <span>Qty</span>
                                                    <span>0</span>
                                                </div>
                                                <span class="shop-button shop-button--secondary">Sold out</span>
                                            </div>
                                        <?php else: ?>
                                            <?php $maxQuantity = min(10, $availableSeats); ?>
                                            <form method="post" action="/events/add-to-program" class="shop-ticket-form">
                                                <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) ($csrf ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                                <input type="hidden" name="event_id" value="<?= (int) ($event['event_id'] ?? 0) ?>">
                                                <input type="hidden" name="ticket_type_id" value="<?= (int) ($ticketType['ticket_type_id'] ?? 0) ?>">

                                                <div class="shop-ticket-form__details">
                                                    <strong><?= htmlspecialchars((string) ($ticketType['name'] ?? 'Ticket'), ENT_QUOTES, 'UTF-8') ?></strong>
                                                    <span><?= htmlspecialchars($formatMoney((float) ($ticketType['price'] ?? 0)), ENT_QUOTES, 'UTF-8') ?></span>
                                                    <small>Available seats: <?= $availableSeats ?></small>
                                                </div>

                                                <label class="shop-ticket-form__quantity">
                                                    <span>Qty</span>
                                                    <input
                                                        type="number"
                                                        name="quantity"
                                                        min="1"
                                                        max="<?= $maxQuantity ?>"
                                                        value="1">
                                                </label>

                                                <button type="submit" class="shop-button">Add to My Program</button>
                                            </form>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
