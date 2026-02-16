<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">

    <?php
    $base = str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME']));
    if ($base === '/' || $base === '.') { $base = ''; }
    ?>
    <link rel="stylesheet" href="<?php echo $base; ?>/assets/css/main.css" />
    <link rel="stylesheet" href="<?php echo $base; ?>/assets/css/admin_dashboard.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;700&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@500;700&display=swap" />
</head>
<body>
    <main class="main-content">
        <div class="main-container">
            <?php 
            // Flash messages injected by BaseController
            $flash = $flash ?? [];
            if (!empty($flash)) {
                if (!empty($flash['success'])) {
                    $s = $flash['success'];
                    if (is_array($s)) {
                        foreach ($s as $m) echo '<div class="flash flash-success">' . htmlspecialchars($m) . '</div>';
                    } else {
                        echo '<div class="flash flash-success">' . htmlspecialchars($s) . '</div>';
                    }
                }
                if (!empty($flash['error'])) {
                    $e = $flash['error'];
                    if (is_array($e)) {
                        foreach ($e as $m) echo '<div class="flash flash-error">' . htmlspecialchars($m) . '</div>';
                    } else {
                        echo '<div class="flash flash-error">' . htmlspecialchars($e) . '</div>';
                    }
                }
            }

            require $content;
            ?>
        </div>
    </main>
</body>
</html>