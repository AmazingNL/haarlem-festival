<?php
$passEventId = (int) ($s['pass_event_id'] ?? 0);
$dayPassLabel = trim((string) ($s['day_pass_label'] ?? 'All-Access Day Pass'));
$dayPassPrice = trim((string) ($s['day_pass_price'] ?? ''));
$fullPassLabel = trim((string) ($s['full_pass_label'] ?? 'All-Access Pass'));
$fullPassPrice = trim((string) ($s['full_pass_price'] ?? ''));
$fullPassTicketId = (int) ($s['full_pass_ticket_id'] ?? 0);

// Each day of the day-pass is its own ticket type, so the dropdown can submit one id.
$dayChoices = array_filter([
    'Thursday' => (int) ($s['day_pass_thursday_ticket_id'] ?? 0),
    'Friday' => (int) ($s['day_pass_friday_ticket_id'] ?? 0),
    'Saturday' => (int) ($s['day_pass_saturday_ticket_id'] ?? 0),
    'Sunday' => (int) ($s['day_pass_sunday_ticket_id'] ?? 0),
], static fn (int $ticketId): bool => $ticketId > 0);

$csrfToken = (string) ($csrf ?? '');
?>
<section class="jazz-section jazz-passes">
    <div class="jazz-container">
        <h2 class="jazz-section__title"><?= htmlspecialchars((string) ($s['heading'] ?? 'All-Access Passes'), ENT_QUOTES, 'UTF-8') ?></h2>
        <?php if (!empty($s['intro'])): ?>
            <p class="jazz-section__intro"><?= $jazzText($s['intro']) ?></p>
        <?php endif; ?>

        <div class="jazz-passes__list">
            <?php if ($dayChoices !== [] && $passEventId > 0): ?>
                <form method="post" action="/events/add-to-program" class="jazz-pass">
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="event_id" value="<?= $passEventId ?>">
                    <input type="hidden" name="quantity" value="1">
                    <div class="jazz-pass__info">
                        <span class="jazz-pass__name"><?= htmlspecialchars($dayPassLabel, ENT_QUOTES, 'UTF-8') ?></span>
                        <?php if ($dayPassPrice !== ''): ?>
                            <span class="jazz-pass__price"><?= htmlspecialchars($dayPassPrice, ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endif; ?>
                    </div>
                    <label class="jazz-pass__choose">
                        <span class="sr-only">Choose a day</span>
                        <select name="ticket_type_id" required>
                            <option value="" disabled selected>Choose</option>
                            <?php foreach ($dayChoices as $day => $ticketId): ?>
                                <option value="<?= $ticketId ?>"><?= htmlspecialchars($day, ENT_QUOTES, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <button type="submit" class="jazz-button jazz-button--add">Add</button>
                </form>
            <?php endif; ?>

            <?php if ($fullPassTicketId > 0 && $passEventId > 0): ?>
                <form method="post" action="/events/add-to-program" class="jazz-pass">
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="event_id" value="<?= $passEventId ?>">
                    <input type="hidden" name="ticket_type_id" value="<?= $fullPassTicketId ?>">
                    <input type="hidden" name="quantity" value="1">
                    <div class="jazz-pass__info">
                        <span class="jazz-pass__name"><?= htmlspecialchars($fullPassLabel, ENT_QUOTES, 'UTF-8') ?></span>
                        <?php if ($fullPassPrice !== ''): ?>
                            <span class="jazz-pass__price"><?= htmlspecialchars($fullPassPrice, ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="jazz-button jazz-button--add">Add</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</section>
