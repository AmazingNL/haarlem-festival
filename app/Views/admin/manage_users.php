<?php
$users  = $users  ?? [];
$role   = $role   ?? '';
$search = $search ?? '';
$sort   = $sort   ?? 'date_desc';
$roles  = ['admin', 'employee', 'customer'];
?>

<div class="admin-header mb-4">
    <div>
        <h1 class="admin-title">Manage Users</h1>
        <p class="muted mb-0">All registered users on the Haarlem Festival platform.</p>
    </div>
    <div class="admin-actions">
        <a href="/admin/users/create" class="admin-add-btn">+ Create User</a>
        <a href="/admin/dashboard" class="btn-secondary">← Dashboard</a>
    </div>
</div>

<!-- Filter / sort bar -->
<form method="GET" action="/admin/users" class="d-flex flex-wrap gap-2 mb-3 align-items-center">
    <input
        type="text"
        name="search"
        class="form-control form-control-sm"
        style="max-width:220px;"
        placeholder="Search name or email…"
        value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"
    >
    <select name="role" class="form-select form-select-sm" style="max-width:150px;">
        <option value="">All roles</option>
        <?php foreach ($roles as $r): ?>
            <option value="<?= $r ?>" <?= $role === $r ? 'selected' : '' ?>><?= ucfirst($r) ?></option>
        <?php endforeach; ?>
    </select>
    <select name="sort" class="form-select form-select-sm" style="max-width:160px;">
        <option value="date_desc" <?= $sort === 'date_desc' ? 'selected' : '' ?>>Newest first</option>
        <option value="date_asc"  <?= $sort === 'date_asc'  ? 'selected' : '' ?>>Oldest first</option>
        <option value="name_asc"  <?= $sort === 'name_asc'  ? 'selected' : '' ?>>Name A–Z</option>
        <option value="name_desc" <?= $sort === 'name_desc' ? 'selected' : '' ?>>Name Z–A</option>
    </select>
    <button type="submit" class="btn-secondary">Filter</button>
    <?php if ($role !== '' || $search !== '' || $sort !== 'date_desc'): ?>
        <a href="/admin/users" class="btn-secondary">Reset</a>
    <?php endif; ?>
</form>

<div class="cards">
    <div class="card">
        <div class="card-header">
            <h2>Users <span class="muted" style="font-size:0.9rem;">(<?= count($users) ?>)</span></h2>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="7" class="muted" style="padding: 1rem;">No users found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $u):
                        $uid       = (int)($u->user_id ?? 0);
                        $firstName = htmlspecialchars((string)($u->first_name ?? ''), ENT_QUOTES, 'UTF-8');
                        $lastName  = htmlspecialchars((string)($u->last_name  ?? ''), ENT_QUOTES, 'UTF-8');
                        $email     = htmlspecialchars((string)($u->email      ?? ''), ENT_QUOTES, 'UTF-8');
                        $username  = htmlspecialchars((string)($u->username   ?? ''), ENT_QUOTES, 'UTF-8');
                        $roleVal   = $u->role instanceof \App\Models\Enum\UserRole
                            ? $u->role->value
                            : (string)($u->role ?? 'customer');
                        $createdAt = htmlspecialchars((string)($u->created_at ?? '-'), ENT_QUOTES, 'UTF-8');
                        $roleBadge = match ($roleVal) {
                            'admin'    => 'badge-published',
                            'employee' => 'badge-draft',
                            default    => 'badge-archived',
                        };
                        $isSelf = $uid === (int)($_SESSION['user_id'] ?? 0);
                    ?>
                        <tr>
                            <td class="muted"><?= $uid ?></td>
                            <td><?= $firstName . ' ' . $lastName ?></td>
                            <td><?= $email ?></td>
                            <td><?= $username ?></td>
                            <td>
                                <span class="<?= $roleBadge ?>">
                                    <?= htmlspecialchars($roleVal, ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </td>
                            <td><?= $createdAt ?></td>
                            <td>
                                <div class="row-actions">
                                    <a href="/admin/users/<?= $uid ?>/edit" class="btn-secondary">Edit</a>
                                    <?php if ($isSelf): ?>
                                        <span class="muted" style="font-size:0.8rem; padding:0.5rem 0.4rem;">You</span>
                                    <?php else: ?>
                                        <a href="/admin/users/<?= $uid ?>/delete"
                                           class="btn-danger"
                                           onclick="return confirm('Delete user <?= addslashes($username) ?>?')">Delete</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
