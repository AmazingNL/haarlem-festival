<?php
$uid       = (int)($user->user_id ?? 0);
$firstName = htmlspecialchars((string)($user->first_name ?? ''), ENT_QUOTES, 'UTF-8');
$lastName  = htmlspecialchars((string)($user->last_name  ?? ''), ENT_QUOTES, 'UTF-8');
$email     = htmlspecialchars((string)($user->email      ?? ''), ENT_QUOTES, 'UTF-8');
$username  = htmlspecialchars((string)($user->username   ?? ''), ENT_QUOTES, 'UTF-8');
$roleVal   = $user->role instanceof \App\Models\Enum\UserRole
    ? $user->role->value
    : (string)($user->role ?? 'customer');
$roles = ['admin', 'employee', 'customer'];
?>

<div class="admin-header mb-4">
    <div>
        <h1 class="admin-title">Edit User</h1>
        <p class="muted mb-0">Editing account for <strong><?= $username ?></strong></p>
    </div>
    <div class="admin-actions">
        <a href="/admin/users" class="btn-secondary">← Back to Users</a>
    </div>
</div>

<div class="cards">
    <div class="card" style="max-width:600px;">
        <div class="card-header">
            <h2>User #<?= $uid ?></h2>
        </div>
        <form method="POST" action="/admin/users/<?= $uid ?>/update" class="p-3">
            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf ?? '', ENT_QUOTES, 'UTF-8') ?>">

            <div class="row mb-3">
                <div class="col">
                    <label class="form-label" for="first_name">First name</label>
                    <input
                        id="first_name"
                        type="text"
                        name="first_name"
                        class="form-control"
                        value="<?= $firstName ?>"
                        required
                    >
                </div>
                <div class="col">
                    <label class="form-label" for="last_name">Last name</label>
                    <input
                        id="last_name"
                        type="text"
                        name="last_name"
                        class="form-control"
                        value="<?= $lastName ?>"
                        required
                    >
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-control"
                    value="<?= $email ?>"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label" for="username">Username</label>
                <input
                    id="username"
                    type="text"
                    name="username"
                    class="form-control"
                    value="<?= $username ?>"
                    required
                >
            </div>

            <div class="mb-4">
                <label class="form-label" for="role">Role</label>
                <select id="role" name="role" class="form-select">
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r ?>" <?= $roleVal === $r ? 'selected' : '' ?>>
                            <?= ucfirst($r) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row-actions">
                <button type="submit" class="admin-add-btn">Save Changes</button>
                <a href="/admin/users" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
