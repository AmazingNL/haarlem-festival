<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars((string) ($title ?? 'Login - Haarlem Festival'), ENT_QUOTES, 'UTF-8') ?></title>

    <link rel="stylesheet" href="/assets/css/output.css">
    <link rel="stylesheet" href="/assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <main class="main-content">
        <div class="main-container">
            <?php require $content; ?>
        </div>
    </main>

    <script defer src="/assets/js/login.js"></script>
</body>

</html>
