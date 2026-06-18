<?php
$order = is_array($order ?? null) ? $order : [];
$items = is_array($order['items'] ?? null) ? array_values($order['items']) : [];
$tickets = is_array($order['tickets'] ?? null) ? array_values($order['tickets']) : [];
$escape = static fn(mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$formatMoney = static fn(mixed $amount): string => 'EUR ' . number_format((float) $amount, 2, '.', ',');
$formatDate = static function (mixed $value): string {
    $timestamp = strtotime((string) $value);
    return $timestamp ? date('Y-m-d H:i', $timestamp) : '-';
};

$orderId = (int) ($order['order_id'] ?? 0);
$customerName = trim((string) ($order['first_name'] ?? '') . ' ' . (string) ($order['last_name'] ?? ''));
if ($customerName === '') {
    $customerName = 'Unknown customer';
}
?>

<div class="admin-header mb-4">
    <div>
        <h1 class="admin-title">Order #<?= $orderId ?></h1>
        <p class="muted mb-0">Order, customer, payment, item, and ticket data.</p>
    </div>
    <div class="admin-actions">
        <a href="/admin/orders/export" class="admin-add-btn">Export orders</a>
        <a href="/admin/orders" class="btn-secondary">Back to Orders</a>
    </div>
</div>

<div class="cards mb-4">
    <div class="card">
        <div class="card-header">
            <h2>Order Information</h2>
        </div>
        <table class="table">
            <tbody>
                <tr><th>Order ID</th><td>#<?= $orderId ?></td></tr>
                <tr><th>Status</th><td><?= $escape($order['status'] ?? '') ?></td></tr>
                <tr><th>Total</th><td><?= $escape($formatMoney($order['total_price'] ?? 0)) ?></td></tr>
                <tr><th>Created</th><td><?= $escape($formatDate($order['created_at'] ?? '')) ?></td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="cards mb-4">
    <div class="card">
        <div class="card-header">
            <h2>Customer Information</h2>
        </div>
        <table class="table">
            <tbody>
                <tr><th>Name</th><td><?= $escape($customerName) ?></td></tr>
                <tr><th>Email</th><td><?= $escape($order['email'] ?? '') ?></td></tr>
                <tr><th>Phone</th><td><?= $escape($order['phone'] ?? '') ?></td></tr>
                <tr><th>User ID</th><td><?= (int) ($order['user_id'] ?? 0) ?></td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="cards mb-4">
    <div class="card">
        <div class="card-header">
            <h2>Payment Information</h2>
        </div>
        <table class="table">
            <tbody>
                <tr><th>Provider</th><td><?= $escape($order['provider'] ?? '') ?></td></tr>
                <tr><th>Payment Status</th><td><?= $escape($order['payment_status'] ?? '') ?></td></tr>
                <tr><th>Paid At</th><td><?= $escape($formatDate($order['paid_at'] ?? '')) ?></td></tr>
                <tr><th>Stripe Session</th><td><?= $escape($order['stripe_session_id'] ?? '') ?></td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="cards mb-4">
    <div class="card">
        <div class="card-header">
            <h2>Order Items</h2>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Selection</th>
                    <th>Ticket Type</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Line Total</th>
                    <th>Location</th>
                    <th>Event</th>
                    <th>Ticket Type ID</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($items === []): ?>
                    <tr>
                        <td colspan="9" class="muted" style="padding: 1rem;">No order items found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <?php
                        $ticketType = trim((string) ($item['ticket_title'] ?? ''));
                        if ($ticketType === '') {
                            $ticketType = (string) ($item['ticket_summary_text'] ?? '');
                        }
                        ?>
                        <tr>
                            <td><?= $escape($item['title'] ?? '') ?></td>
                            <td><?= $escape($item['selection_text'] ?? '') ?></td>
                            <td><?= $escape($ticketType) ?></td>
                            <td><?= (int) ($item['quantity'] ?? 0) ?></td>
                            <td><?= $escape($formatMoney($item['unit_price'] ?? 0)) ?></td>
                            <td><?= $escape($formatMoney($item['line_total'] ?? 0)) ?></td>
                            <td><?= $escape($item['location_name'] ?? '') ?></td>
                            <td><?= (int) ($item['event_id'] ?? 0) ?></td>
                            <td><?= (int) ($item['ticket_type_id'] ?? 0) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="cards">
    <div class="card">
        <div class="card-header">
            <h2>Tickets</h2>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Ticket Type ID</th>
                    <th>Status</th>
                    <th>QR Token</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($tickets === []): ?>
                    <tr>
                        <td colspan="4" class="muted" style="padding: 1rem;">No tickets found for this order.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tickets as $ticket): ?>
                        <tr>
                            <td><?= (int) ($ticket['ticket_id'] ?? 0) ?></td>
                            <td><?= (int) ($ticket['ticket_type_id'] ?? 0) ?></td>
                            <td><?= $escape($ticket['status'] ?? '') ?></td>
                            <td style="word-break: break-all;"><?= $escape($ticket['qr_token'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
