<?php
$event = is_array($event ?? null) ? $event : [];
$ticketTypes = is_array($event['ticket_types'] ?? null) ? array_values($event['ticket_types']) : [];
$escape = static fn(mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$formatMoney = static fn(mixed $amount): string => 'EUR ' . number_format((float) $amount, 2, '.', ',');
$formatDate = static function (mixed $value): string {
    $timestamp = strtotime((string) $value);
    return $timestamp ? date('Y-m-d H:i', $timestamp) : '-';
};
$eventId = (int) ($event['event_id'] ?? 0);
?>

<div class="admin-header mb-4">
    <div>
        <h1 class="admin-title">Dance Ticket Availability</h1>
        <p class="muted mb-0"><?= $escape($event['title'] ?? 'Dance event') ?></p>
    </div>
    <div class="admin-actions">
        <a href="/admin/dance/seats" class="btn-secondary">Back to Dance Ticket Availability</a>
    </div>
</div>

<div class="cards mb-4">
    <div class="card">
        <div class="card-header">
            <h2>Event Information</h2>
        </div>
        <table class="table">
            <tbody>
                <tr><th>Event ID</th><td>#<?= $eventId ?></td></tr>
                <tr><th>Date</th><td><?= $escape($formatDate($event['start_datetime'] ?? '')) ?></td></tr>
                <tr><th>Location</th><td><?= $escape($event['location_name'] ?? '') ?></td></tr>
                <tr><th>Status</th><td><?= !empty($event['is_published']) ? 'Published' : 'Draft' ?></td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="cards">
    <div class="card">
        <div class="card-header">
            <h2>Ticket Availability</h2>
        </div>

        <?php if ($ticketTypes === []): ?>
            <p class="muted" style="padding: 1rem;">This Dance event has no ticket types yet.</p>
        <?php else: ?>
            <form method="post" action="/admin/dance/seats/<?= $eventId ?>" class="admin-form">
                <input type="hidden" name="_csrf" value="<?= $escape($csrf ?? '') ?>">

                <table class="table">
                    <thead>
                        <tr>
                            <th>Ticket Type</th>
                            <th>Price</th>
                            <th>Available Tickets</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ticketTypes as $ticketType): ?>
                            <?php $ticketTypeId = (int) ($ticketType['ticket_type_id'] ?? 0); ?>
                            <tr>
                                <td><?= $escape($ticketType['name'] ?? 'Ticket') ?></td>
                                <td><?= $escape($formatMoney($ticketType['price'] ?? 0)) ?></td>
                                <td>
                                    <input
                                        type="number"
                                        min="0"
                                        name="max_quantity[<?= $ticketTypeId ?>]"
                                        value="<?= max(0, (int) ($ticketType['max_quantity'] ?? 0)) ?>"
                                        class="input"
                                        required>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="admin-actions mt-3">
                    <button type="submit" class="admin-add-btn">Save ticket availability</button>
                    <a href="/admin/dance/seats" class="btn-secondary">Cancel</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
