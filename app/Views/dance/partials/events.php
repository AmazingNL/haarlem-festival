<?php
$formatMoney = static fn(float $amount): string => 'EUR ' . number_format($amount, 2, '.', ',');

$formatEventDateTime = static function (?string $startDateTime, ?string $endDateTime): string {
    $startTimestamp = $startDateTime ? strtotime($startDateTime) : false;
    $endTimestamp = $endDateTime ? strtotime($endDateTime) : false;

    if (!$startTimestamp) {
        return 'Date to be announced';
    }

    $dateLabel = date('l, F j', $startTimestamp);
    $timeLabel = date('H:i', $startTimestamp);

    if ($endTimestamp) {
        $timeLabel .= ' - ' . date('H:i', $endTimestamp);
    }

    return $dateLabel . ' | ' . $timeLabel;
};
?>

<section class="dance-events" id="dance-tickets" aria-labelledby="dance-events-title">
    <div class="dance-events__header">
        <p class="dance-kicker">Tickets</p>
        <h2 id="dance-events-title">Dance Sessions</h2>
        <p>Choose your Dance session, select a quantity, and add the tickets to My Program.</p>
    </div>

    <?php if ($events === []): ?>
        <article class="dance-empty-state">
            <h3>No Dance events found</h3>
            <p>Dance events are not available yet. Please check back later.</p>
        </article>
    <?php else: ?>
        <div class="dance-event-list">
            <?php foreach ($events as $event): ?>
                <?php
                $eventId = max(0, (int) ($event['event_id'] ?? 0));
                $ticketTypes = is_array($event['ticket_types'] ?? null) ? $event['ticket_types'] : [];
                ?>
                <article class="dance-event-card">
                    <div class="dance-event-card__main">
                        <p class="dance-event-card__label">
                            <?= htmlspecialchars((string) ($event['category_label'] ?? 'Dance'), ENT_QUOTES, 'UTF-8') ?>
                        </p>
                        <h3><?= htmlspecialchars((string) ($event['title'] ?? 'Dance Event'), ENT_QUOTES, 'UTF-8') ?></h3>

                        <?php if (!empty($event['description'])): ?>
                            <p class="dance-event-card__description">
                                <?= htmlspecialchars((string) $event['description'], ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="dance-event-card__details">
                        <p>
                            <span>Date &amp; time</span>
                            <?= htmlspecialchars($formatEventDateTime(
                                (string) ($event['start_datetime'] ?? ''),
                                (string) ($event['end_datetime'] ?? '')
                            ), ENT_QUOTES, 'UTF-8') ?>
                        </p>
                        <p>
                            <span>Venue</span>
                            <?= htmlspecialchars((string) ($event['location_name'] ?? 'Haarlem'), ENT_QUOTES, 'UTF-8') ?>
                            <?php if (!empty($event['location_city'])): ?>
                                , <?= htmlspecialchars((string) $event['location_city'], ENT_QUOTES, 'UTF-8') ?>
                            <?php endif; ?>
                        </p>
                    </div>

                    <div class="dance-ticket-list" aria-label="Ticket types">
                        <?php if ($ticketTypes === []): ?>
                            <p class="dance-ticket-list__empty">Ticket types are not configured yet.</p>
                        <?php else: ?>
                            <?php foreach ($ticketTypes as $ticketType): ?>
                                <?php
                                $ticketTypeId = max(0, (int) ($ticketType['ticket_type_id'] ?? 0));
                                $availableQuantity = max(0, (int) ($ticketType['max_quantity'] ?? 0));
                                ?>
                                <?php if ($eventId <= 0 || $ticketTypeId <= 0 || $availableQuantity <= 0): ?>
                                    <div class="dance-ticket-row dance-ticket-row--sold-out">
                                        <div class="dance-ticket-row__details">
                                            <span><?= htmlspecialchars((string) ($ticketType['name'] ?? 'Ticket'), ENT_QUOTES, 'UTF-8') ?></span>
                                            <strong><?= htmlspecialchars($formatMoney((float) ($ticketType['price'] ?? 0)), ENT_QUOTES, 'UTF-8') ?></strong>
                                        </div>
                                        <p>Sold out</p>
                                    </div>
                                <?php else: ?>
                                    <form method="post" action="/events/add-to-program" class="dance-ticket-row dance-ticket-form">
                                        <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) ($csrf ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="event_id" value="<?= $eventId ?>">
                                        <input type="hidden" name="ticket_type_id" value="<?= $ticketTypeId ?>">

                                        <div class="dance-ticket-row__details">
                                            <span><?= htmlspecialchars((string) ($ticketType['name'] ?? 'Ticket'), ENT_QUOTES, 'UTF-8') ?></span>
                                            <strong><?= htmlspecialchars($formatMoney((float) ($ticketType['price'] ?? 0)), ENT_QUOTES, 'UTF-8') ?></strong>
                                            <small>Available: <?= $availableQuantity ?></small>
                                        </div>

                                        <label class="dance-ticket-form__quantity">
                                            <span>Qty</span>
                                            <input
                                                type="number"
                                                name="quantity"
                                                min="1"
                                                max="<?= $availableQuantity ?>"
                                                value="1">
                                        </label>

                                        <button type="submit" class="dance-ticket-form__button">Add to My Program</button>
                                    </form>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
