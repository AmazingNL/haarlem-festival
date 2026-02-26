<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">

    <?php
    $base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    if ($base === '/' || $base === '.') {
        $base = '';
    }
    ?>
    <link rel="stylesheet" href="/assets/css/admin_css/edit_page.css">
    <link rel="stylesheet" href="/assets/css/admin_css/create_page.css" />
    <link rel="stylesheet" href="/assets/css/admin_css/create_page_section.css" />
    <link rel="stylesheet" href="/assets/css/main.css" />
    <link rel="stylesheet" href="/assets/css/admin_css/admin_dashboard.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;700&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@500;700&display=swap" />
</head>

<body>
    <main class="main-content">
        <?php if (!empty($flash['success'])): ?>
            <div class="alert" role="status"><?= htmlspecialchars((string) $flash['success'], ENT_QUOTES, 'UTF-8') ?></div>
        <?php elseif (!empty($flash['error'])): ?>
            <div class="alert" style="background: rgba(255,120,120,0.06); border-color: rgba(255,120,120,0.12);"
                role="alert">
                <?= htmlspecialchars((string) $flash['error'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
        <div class="main-container">
            <?php
                require $content;
            ?>
        </div>
    </main>
    <script>
        window.TINYMCE_API_KEY = "<?= htmlspecialchars($_ENV['TINYMCE_API_KEY'] ?? '', ENT_QUOTES, 'UTF-8') ?>";
    </script>

    <script src="/assets/js/admin/tinymce-init.js"></script>

</body>

</html>