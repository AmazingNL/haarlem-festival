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
        <p>Explore the published Dance events. Adding tickets to My Program will be added in the next slice.</p>
    </div>

    <?php if ($events === []): ?>
        <article class="dance-empty-state">
            <h3>No Dance events found</h3>
            <p>Dance events are not available yet. Please check back later.</p>
        </article>
    <?php else: ?>
        <div class="dance-event-list">
            <?php foreach ($events as $event): ?>
                <?php $ticketTypes = is_array($event['ticket_types'] ?? null) ? $event['ticket_types'] : []; ?>
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
                                <div class="dance-ticket-row">
                                    <span><?= htmlspecialchars((string) ($ticketType['name'] ?? 'Ticket'), ENT_QUOTES, 'UTF-8') ?></span>
                                    <strong><?= htmlspecialchars($formatMoney((float) ($ticketType['price'] ?? 0)), ENT_QUOTES, 'UTF-8') ?></strong>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
