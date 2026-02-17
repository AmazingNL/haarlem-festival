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

    <header class="header">
        <nav class="navbar">
            <!-- Logo Section -->
            <a href="<?php echo $base; ?>/" class="logo-link">
                <span class="logo-text">HA</span>
                <img class="logo-icon" alt="logo" src="<?php echo $base; ?>/assets/svg/main/Star 3.svg">
                <span class="logo-text">RLEM</span>
            </a>

            <!-- Hamburger Menu Button (Mobile) -->
            <button class="menu-toggle" aria-label="Toggle menu">
                <span class="hamburger"></span>
                <span class="hamburger"></span>
                <span class="hamburger"></span>
            </button>

            <!-- Navigation Links -->
            <ul class="nav-links">
                <li><a href="<?php echo $base; ?>/admin/dashboard" class="nav-link active">Dashboard</a></li>
                <li><a href="<?php echo $base; ?>/admin/users" class="nav-link">Users</a></li>
                <li><a href="<?php echo $base; ?>/admin/events" class="nav-link">Events</a></li>
                <li><a href="<?php echo $base; ?>/admin/content" class="nav-link">Content</a></li>
                <li><a href="<?php echo $base; ?>/admin/orders" class="nav-link">Orders</a></li>
            </ul>

            <!-- Icon Actions (User Icons) -->
            <div class="icon-actions">
                <a href="<?php echo $base; ?>/" class="icon-btn" aria-label="View Site" title="View Site">
                    <img class="icon-svg" alt="home icon" src="<?php echo $base; ?>/assets/svg/main/location.svg">
                </a>
                <a href="<?php echo $base; ?>/auth/logout" class="icon-btn" aria-label="Logout" title="Logout">
                    <img class="icon-svg" alt="logout icon" src="<?php echo $base; ?>/assets/svg/main/search.svg">
                </a>
            </div>
        </nav>
    </header>

    <!-- Main Content Area -->
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

    <footer class="footer">
        <div class="footer-content">
            <!-- Footer Columns -->
            <div class="footer-columns">
                <!-- Admin Column -->
                <div class="footer-column">
                    <h3 class="footer-title">Admin Panel</h3>
                    <ul class="footer-links">
                        <li><a href="<?php echo $base; ?>/admin/dashboard" class="footer-link">Dashboard</a></li>
                        <li><a href="<?php echo $base; ?>/admin/users" class="footer-link">User Management</a></li>
                        <li><a href="<?php echo $base; ?>/admin/events" class="footer-link">Event Management</a></li>
                    </ul>
                </div>

                <!-- Content Column -->
                <div class="footer-column">
                    <h3 class="footer-title">Content</h3>
                    <ul class="footer-links">
                        <li><a href="<?php echo $base; ?>/admin/content" class="footer-link">Pages</a></li>
                        <li><a href="<?php echo $base; ?>/admin/orders" class="footer-link">Orders</a></li>
                        <li><a href="<?php echo $base; ?>/admin/settings" class="footer-link">Settings</a></li>
                    </ul>
                </div>

                <!-- Links Column -->
                <div class="footer-column">
                    <h3 class="footer-title">Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="<?php echo $base; ?>/" class="footer-link">View Site</a></li>
                        <li><a href="<?php echo $base; ?>/admin/help" class="footer-link">Help & Support</a></li>
                        <li><a href="<?php echo $base; ?>/auth/logout" class="footer-link">Logout</a></li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <p class="footer-copyright">© 2025 Haarlem Festival Admin Panel. All rights reserved.</p>
                <nav class="footer-nav">
                    <a href="#" class="footer-bottom-link">Privacy Policy</a>
                    <a href="#" class="footer-bottom-link">Terms of Service</a>
                </nav>
            </div>
        </div>
    </footer>

    <script src="<?php echo $base; ?>/assets/js/navbar.js"></script>
</body>

</html>