<?php
// Reusable admin sidebar partial — included in admin layout so it's present on all admin pages
$current = $_SERVER['REQUEST_URI'] ?? '/';
?>
<aside class="col-md-3 mb-4">
    <div class="p-3 bg-dark text-light rounded">
        <div class="brand mb-3">
            <h5 class="m-0">HAARLEM</h5>
        </div>

        <nav class="nav flex-column mt-3">
            <a class="nav-link text-light <?= $current === '/admin' ? 'active' : '' ?>" href="/admin">Dashboard</a>
            <a class="nav-link text-light <?= strpos($current, '/admin/pages') === 0 ? 'active' : '' ?>"
                href="/admin/pages/viewPage">Pages</a>
            <a class="nav-link text-light <?= strpos($current, '/admin/users') === 0 ? 'active' : '' ?>"
                href="/admin/users">Users</a>
            <a class="nav-link text-light <?= strpos($current, '/admin/settings') === 0 ? 'active' : '' ?>"
                href="/admin/settings">Settings</a>
        </nav>
    </div>
</aside>