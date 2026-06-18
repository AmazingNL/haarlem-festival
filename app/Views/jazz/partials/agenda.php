<?php
// Group the performances collected in index.php into the festival days.
$dayOrder = ['Thursday', 'Friday', 'Saturday', 'Sunday'];

$eventsByDay = [];
foreach (($agendaEvents ?? []) as $event) {
    $day = ucfirst(strtolower(trim((string) ($event['day'] ?? ''))));
    if ($day === '') {
        $day = 'Other';
    }
    $eventsByDay[$day][] = $event;
}

// Show the known days in festival order first, then any extra days admins added.
$days = [];
foreach ($dayOrder as $day) {
    if (isset($eventsByDay[$day])) {
        $days[] = $day;
    }
}
foreach (array_keys($eventsByDay) as $day) {
    if (!in_array($day, $days, true)) {
        $days[] = $day;
    }
}

$csrfToken = (string) ($csrf ?? '');
?>
<section class="jazz-section jazz-agenda">
    <div class="jazz-container">
        <?php if ($days !== []): ?>
            <div class="jazz-agenda__tabs" role="tablist">
                <?php foreach ($days as $index => $day): ?>
                    <button type="button"
                        class="jazz-agenda__tab<?= $index === 0 ? ' is-active' : '' ?>"
                        data-jazz-tab="<?= htmlspecialchars($day, ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($day, ENT_QUOTES, 'UTF-8') ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <?php foreach ($days as $index => $day): ?>
                <div class="jazz-agenda__panel<?= $index === 0 ? ' is-active' : '' ?>"
                    data-jazz-panel="<?= htmlspecialchars($day, ENT_QUOTES, 'UTF-8') ?>">
                    <div class="jazz-agenda__grid">
                        <?php foreach ($eventsByDay[$day] as $event): ?>
                            <?php
                            $venue = trim((string) ($event['venue'] ?? ''));
                            $title = trim((string) ($event['title'] ?? ''));
                            $timeText = trim((string) ($event['time_text'] ?? ''));
                            $description = (string) ($event['description'] ?? '');
                            $priceLabel = trim((string) ($event['price_label'] ?? ''));
                            $eventId = (int) ($event['event_id'] ?? 0);
                            $ticketTypeId = (int) ($event['ticket_type_id'] ?? 0);
                            $learnMore = trim((string) ($event['learn_more_link'] ?? ''));
                            $cardImage = $jazzImage($event['image'] ?? '');
                            ?>
                            <article class="jazz-event<?= $cardImage !== '' ? ' jazz-event--has-image' : '' ?>"<?= $cardImage !== '' ? ' style="background-image:url(\'' . htmlspecialchars($cardImage, ENT_QUOTES, 'UTF-8') . '\')"' : '' ?>>
                                <?php if ($venue !== ''): ?>
                                    <p class="jazz-event__venue"><?= htmlspecialchars($venue, ENT_QUOTES, 'UTF-8') ?></p>
                                <?php endif; ?>
                                <h3 class="jazz-event__title">
                                    <span><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></span>
                                    <?php if ($timeText !== ''): ?>
                                        <span class="jazz-event__time"><?= htmlspecialchars($timeText, ENT_QUOTES, 'UTF-8') ?></span>
                                    <?php endif; ?>
                                </h3>
                                <?php if (trim($description) !== ''): ?>
                                    <p class="jazz-event__text"><?= $jazzText($description) ?></p>
                                <?php endif; ?>
                                <?php if ($priceLabel !== ''): ?>
                                    <p class="jazz-event__price"><?= htmlspecialchars($priceLabel, ENT_QUOTES, 'UTF-8') ?></p>
                                <?php endif; ?>

                                <div class="jazz-event__actions">
                                    <?php if ($eventId > 0 && $ticketTypeId > 0): ?>
                                        <form method="post" action="/events/add-to-program" class="jazz-event__form">
                                            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                            <input type="hidden" name="event_id" value="<?= $eventId ?>">
                                            <input type="hidden" name="ticket_type_id" value="<?= $ticketTypeId ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="jazz-button jazz-button--add">Add to My Programs</button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if ($learnMore !== ''): ?>
                                        <a class="jazz-button jazz-button--ghost" href="<?= $jazzUrl($learnMore) ?>">Learn More</a>
                                    <?php endif; ?>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
