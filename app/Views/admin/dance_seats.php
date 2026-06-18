<?php
$events = is_array($events ?? null) ? array_values($events) : [];
$escape = static fn(mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$formatDate = static function (mixed $value): string {
    $timestamp = strtotime((string) $value);
    return $timestamp ? date('Y-m-d H:i', $timestamp) : '-';
};
?>

<div class="admin-header mb-4">
    <div>
        <h1 class="admin-title">Dance Ticket Availability</h1>
        <p class="muted mb-0">Change available tickets for Dance event ticket types.</p>
    </div>
    <div class="admin-actions">
        <a href="/admin/dashboard" class="btn-secondary">Back to Dashboard</a>
    </div>
</div>

<div class="cards">
    <div class="card">
        <div class="card-header">
            <h2>Dance Events <span class="muted" style="font-size:0.9rem;">(<?= count($events) ?>)</span></h2>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Ticket Types</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($events === []): ?>
                    <tr>
                        <td colspan="6" class="muted" style="padding: 1rem;">No Dance events found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($events as $event): ?>
                        <?php
                        $eventId = (int) ($event['event_id'] ?? 0);
                        $ticketSummaries = [];
                        foreach (($event['ticket_types'] ?? []) as $ticketType) {
                            $ticketSummaries[] = trim((string) ($ticketType['name'] ?? 'Ticket'))
                                . ': '
                                . max(0, (int) ($ticketType['max_quantity'] ?? 0));
                        }
                        ?>
                        <tr>
                            <td><?= $escape($event['title'] ?? '') ?></td>
                            <td><?= $escape($formatDate($event['start_datetime'] ?? '')) ?></td>
                            <td><?= $escape($event['location_name'] ?? '') ?></td>
                            <td><?= $escape(implode(', ', $ticketSummaries)) ?></td>
                            <td><?= !empty($event['is_published']) ? 'Published' : 'Draft' ?></td>
                            <td>
                                <a href="/admin/dance/seats/<?= $eventId ?>" class="btn-secondary">Manage tickets</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
