<?php
$users = $data['users'] ?? [];
?>

<div class="admin-header mb-4">
    <div>
        <h1 class="admin-title">Manage Users</h1>
        <p class="muted mb-0">All registered users on the Haarlem Festival platform.</p>
    </div>
    <div class="admin-actions">
        <a href="/admin/dashboard" class="btn-secondary">← Dashboard</a>
    </div>
</div>

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
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="muted" style="padding: 1rem;">No users found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $u):
                        $userId    = (int)($u->user_id ?? 0);
                        $firstName = htmlspecialchars((string)($u->first_name ?? ''), ENT_QUOTES, 'UTF-8');
                        $lastName  = htmlspecialchars((string)($u->last_name ?? ''), ENT_QUOTES, 'UTF-8');
                        $email     = htmlspecialchars((string)($u->email ?? ''), ENT_QUOTES, 'UTF-8');
                        $username  = htmlspecialchars((string)($u->username ?? ''), ENT_QUOTES, 'UTF-8');
                        $roleVal   = is_object($u->role) && property_exists($u->role, 'value')
                            ? $u->role->value
                            : (string)($u->role ?? 'customer');
                        $createdAt = htmlspecialchars((string)($u->created_at ?? '-'), ENT_QUOTES, 'UTF-8');
                        $roleBadge = match ($roleVal) {
                            'admin'    => 'badge-published',
                            'employee' => 'badge-draft',
                            default    => 'badge-archived',
                        };
                    ?>
                        <tr>
                            <td class="muted"><?= $userId ?></td>
                            <td><?= $firstName . ' ' . $lastName ?></td>
                            <td><?= $email ?></td>
                            <td><?= $username ?></td>
                            <td>
                                <span class="<?= $roleBadge ?>">
                                    <?= htmlspecialchars($roleVal, ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </td>
                            <td><?= $createdAt ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
