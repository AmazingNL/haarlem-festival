<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?> - Haarlem Festival</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>

<body>
    <?php
    // Get current path for active state detection
    $currentPath = $_SERVER['REQUEST_URI'] ?? '';
    $currentPath = strtok($currentPath, '?'); // Remove query string

    // Helper function to check if link is active
    function isActive($path, $currentPath) {
        return strpos($currentPath, $path) === 0 ? 'active' : '';
    }

    // Helper function to get display name
    function getDisplayName() {
        $firstName = $_SESSION['first_name'] ?? '';
        $lastName = $_SESSION['last_name'] ?? '';
        $email = $_SESSION['email'] ?? '';

        if (!empty($firstName)) {
            return trim($firstName . ' ' . $lastName);
        }

        if (!empty($email)) {
            return $email;
        }

        return 'Admin';
    }
    ?>

    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="p-3">
                <h4 class="text-white mb-4">
                    <i class="bi bi-star-fill text-warning"></i> Admin Panel
                </h4>

                <!-- Sidebar navigation -->
                <nav class="nav flex-column">
                    <a href="/admin/dashboard" class="nav-link admin-nav-link <?= isActive('/admin/dashboard', $currentPath) ?>">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="/admin/users" class="nav-link admin-nav-link <?= isActive('/admin/users', $currentPath) ?>">
                        <i class="bi bi-people"></i>
                        <span>Users</span>
                    </a>
                    <a href="/admin/events" class="nav-link admin-nav-link <?= isActive('/admin/events', $currentPath) ?>">
                        <i class="bi bi-calendar-event"></i>
                        <span>Events</span>
                    </a>
                    <a href="/admin/orders" class="nav-link admin-nav-link <?= isActive('/admin/orders', $currentPath) ?>">
                        <i class="bi bi-cart"></i>
                        <span>Orders</span>
                    </a>
                    <a href="/admin/content" class="nav-link admin-nav-link <?= isActive('/admin/content', $currentPath) ?>">
                        <i class="bi bi-file-text"></i>
                        <span>Content</span>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="admin-main">
            <!-- Top Navbar -->
            <nav class="admin-navbar navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <!-- Mobile toggle button -->
                    <button class="btn btn-link d-lg-none text-dark p-0" type="button" id="sidebarToggle">
                        <i class="bi bi-list fs-4"></i>
                    </button>

                    <span class="navbar-brand mb-0 h1"><?= $title ?? 'Dashboard' ?></span>

                    <!-- User controls -->
                    <div class="ms-auto d-flex align-items-center gap-3">
                        <!-- User info (if logged in) -->
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <span class="text-muted d-none d-md-inline">
                                <i class="bi bi-person-circle"></i>
                                Hi, <?= htmlspecialchars(getDisplayName(), ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        <?php endif; ?>

                        <!-- Logout button -->
                        <a href="/admin/logout" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="d-none d-sm-inline ms-1">Logout</span>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="admin-content">
                <?php
                require $content;
                ?>
            </main>
        </div>
    </div>

    <!-- Backdrop for mobile sidebar -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Bootstrap 5 JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar toggle script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const toggleBtn = document.getElementById('sidebarToggle');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    backdrop.classList.toggle('show');
                });

                backdrop.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                });
            }
        });
    </script>
</body>

</html>
