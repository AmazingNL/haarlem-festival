<?php
// Simple login form view
/** @var string $title */
/** @var string $csrf */
?>
<section class="auth-card">
    <h1><?= htmlspecialchars($title ?? 'Login', ENT_QUOTES, 'UTF-8') ?></h1>

    <?php if (!empty($flash['error'])): ?>
        <div class="flash flash-error"><?= htmlspecialchars($flash['error'], ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="post" action="/login" class="auth-form">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <div class="form-row">
            <label for="email_or_Username">Email or Username</label>
            <input id="email_or_Username" name="email_or_Username" type="text" required autofocus />
        </div>

        <div class="form-row">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required />
        </div>

        <div class="form-actions">
            <button class="btn-primary" type="submit">Log in</button>
        </div>
    </form>

    <p class="muted">Don't have an account? <a href="/admin/registerForm">Register</a></p>
</section>

<style>
.auth-card { max-width:420px; margin:40px auto; padding:20px; border:1px solid #eee; border-radius:6px; background:#fff; }
.form-row { margin-bottom:12px; display:flex; flex-direction:column; }
.form-row label { font-weight:600; margin-bottom:6px; }
.form-row input { padding:8px 10px; border:1px solid #ddd; border-radius:4px; }
.form-actions { margin-top:12px; }
.btn-primary { background:#1f6feb; color:#fff; padding:8px 14px; border-radius:4px; border:none; cursor:pointer; }
.muted { color:#666; font-size:0.95rem; }
.flash-error { background:#ffecec; border:1px solid #f5b6b6; padding:8px; margin-bottom:12px; }
</style>
