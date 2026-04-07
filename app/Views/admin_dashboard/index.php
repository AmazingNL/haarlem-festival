<?php
$allPages    = $data['allPages']    ?? [];
$recentPages = $data['recentPages'] ?? [];
$userCount   = $data['userCount']   ?? 0;

$totalPages = count($allPages);
$published  = 0;
$drafts     = 0;
foreach ($allPages as $p) {
    $s = is_object($p->status) && property_exists($p->status, 'value')
        ? $p->status->value
        : (string)($p->status ?? '');
    if ($s === 'published') {
        $published++;
    } elseif ($s === 'draft') {
        $drafts++;
    }
}
?>

<div class="admin-header mb-4">
    <div>
        <h1 class="admin-title">Admin Dashboard</h1>
        <p class="muted mb-0">Haarlem Festival 2026 — site overview and quick actions.</p>
    </div>
    <div class="admin-actions">
        <a class="admin-add-btn" href="/admin/pages/createPage">
            <span aria-hidden="true">+</span> New Page
        </a>
    </div>
</div>

<!-- Stat cards -->
<div class="stats-grid mb-4">
    <a href="/admin/pages/viewPage" class="stat-card stat-card--pink">
        <span class="stat-value"><?= $totalPages ?></span>
        <span class="stat-label">Total Pages</span>
    </a>
    <a href="/admin/pages/viewPage" class="stat-card stat-card--green">
        <span class="stat-value"><?= $published ?></span>
        <span class="stat-label">Published</span>
    </a>
    <a href="/admin/pages/viewPage" class="stat-card stat-card--yellow">
        <span class="stat-value"><?= $drafts ?></span>
        <span class="stat-label">Drafts</span>
    </a>
    <a href="/admin/users" class="stat-card stat-card--blue">
        <span class="stat-value"><?= $userCount ?></span>
        <span class="stat-label">Users</span>
    </a>
</div>

<!-- Recent Pages -->
<div class="cards">
    <div class="card">
        <div class="card-header">
            <h2>Recent Pages</h2>
            <a href="/admin/pages/viewPage" class="btn-secondary">View All</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentPages)): ?>
                    <tr>
                        <td colspan="4" class="muted" style="padding: 1rem;">
                            No pages yet.
                            <a href="/admin/pages/createPage">Create your first page →</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentPages as $p):
                        $id        = (int)($p->page_id ?? 0);
                        $title     = htmlspecialchars((string)($p->title ?? ''), ENT_QUOTES, 'UTF-8');
                        $statusVal = is_object($p->status) && property_exists($p->status, 'value')
                            ? $p->status->value
                            : (string)($p->status ?? 'draft');
                        $createdAt = htmlspecialchars((string)($p->created_at ?? '-'), ENT_QUOTES, 'UTF-8');
                        $badgeClass = match ($statusVal) {
                            'published' => 'badge-published',
                            'archived'  => 'badge-archived',
                            default     => 'badge-draft',
                        };
                    ?>
                        <tr>
                            <td><?= $title ?></td>
                            <td>
                                <span class="<?= $badgeClass ?>">
                                    <?= htmlspecialchars($statusVal, ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </td>
                            <td><?= $createdAt ?></td>
                            <td class="row-actions">
                                <a href="/admin/pages/<?= $id ?>/editForm" class="btn-secondary">Edit</a>
                                <a href="/admin/pageSection/<?= $id ?>/pageSectionForm" class="btn-secondary">Sections</a>
                                <a href="/admin/dashboard/<?= $id ?>/delete" class="pill btn-danger"
                                onclick="return confirm('Delete \'<?= addslashes($title) ?>\'?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
