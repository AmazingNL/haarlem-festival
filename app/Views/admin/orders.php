<?php
$orders = is_array($orders ?? null) ? array_values($orders) : [];

$escape = static fn(mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$formatMoney = static fn(mixed $amount): string => 'EUR ' . number_format((float) $amount, 2);
$formatDate = static function (mixed $value): string {
    $timestamp = strtotime((string) $value);
    return $timestamp ? date('Y-m-d H:i', $timestamp) : '-';
};
$getOrderValue = static fn(mixed $order, string $key): mixed => is_array($order) ? ($order[$key] ?? '') : '';
$getOrderText = static fn(mixed $order, string $key): string => (string) $getOrderValue($order, $key);
$formatCustomerName = static function (mixed $order) use ($getOrderText): string {
    $customerName = trim($getOrderText($order, 'first_name') . ' ' . $getOrderText($order, 'last_name'));

    return $customerName !== '' ? $customerName : 'Unknown customer';
};
?>

<div class="admin-header mb-4">
    <div>
        <h1 class="admin-title">Orders</h1>
        <p class="muted mb-0">Paid festival orders, customer details, and ticket line items.</p>
    </div>
    <div class="admin-actions">
        <a href="/admin/orders/export" class="admin-add-btn">Export orders</a>
        <a href="/admin/dashboard" class="btn-secondary">Back to Dashboard</a>
    </div>
</div>

<div class="cards">
    <div class="card">
        <div class="card-header">
            <h2>Orders <span class="muted" style="font-size:0.9rem;">(<?= count($orders) ?>)</span></h2>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Total</th>
                    <th>Order Status</th>
                    <th>Payment</th>
                    <th>Provider</th>
                    <th>Created</th>
                    <th>Paid</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($orders === []): ?>
                    <tr>
                        <td colspan="10" class="muted" style="padding: 1rem;">No orders found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <?php
                        $orderId = (int) $getOrderValue($order, 'order_id');
                        $rowCells = [
                            '#' . $orderId,
                            $formatCustomerName($order),
                            $getOrderText($order, 'email'),
                            $formatMoney($getOrderValue($order, 'total_price')),
                            $getOrderText($order, 'status'),
                            $getOrderText($order, 'payment_status'),
                            $getOrderText($order, 'provider'),
                            $formatDate($getOrderValue($order, 'created_at')),
                            $formatDate($getOrderValue($order, 'paid_at')),
                        ];
                        ?>
                        <tr>
                            <?php foreach ($rowCells as $cell): ?>
                                <td><?= $escape($cell) ?></td>
                            <?php endforeach; ?>
                            <td>
                                <a href="/admin/orders/<?= $orderId ?>" class="btn-secondary">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
