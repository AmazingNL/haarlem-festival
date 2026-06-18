<?php
// Minimal checkout view scaffold
$orderId = $orderId ?? 0;
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Checkout</title>
</head>

<body>
    <h1>Checkout — Order <?= htmlspecialchars($orderId) ?></h1>

    <label>Amount (EUR): <input id="amount" value="10.00"></label>
    <button id="pay">Pay with Stripe</button>

    <script>
        document.getElementById('pay').addEventListener('click', async function () {
            const amount = parseFloat(document.getElementById('amount').value || '0');

            const res = await fetch('/payments/create-session', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ order_id: <?= (int) $orderId ?>, amount: amount })
            });

            const data = await res.json();
            if (data.error) {
                alert('Error: ' + data.error);
                return;
            }

            // In a complete integration you'd redirect to Stripe Checkout here.
            alert('Checkout session created: ' + data.id);
        });
    </script>
</body>

</html>