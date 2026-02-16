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
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="p-3">
                <h4 class="text-white mb-4">
                    <i class="bi bi-star-fill text-warning"></i> Admin Panel
                </h4>

                <!-- Sidebar navigation placeholder -->
                <nav class="nav flex-column">
                    <p class="text-muted small mb-0">Navigation items will be added here</p>
                </nav>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="admin-main">
            <!-- Top Navbar -->
            <nav class="admin-navbar navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <!-- Navbar content placeholder -->
                    <span class="navbar-brand mb-0 h1"><?= $title ?? 'Dashboard' ?></span>

                    <div class="ms-auto">
                        <span class="text-muted">Admin user controls will be added here</span>
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

    <!-- Bootstrap 5 JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
