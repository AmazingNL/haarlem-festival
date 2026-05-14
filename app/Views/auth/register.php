<style>
  .two-col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
  }

  @media (max-width: 520px) {
    .two-col {
      grid-template-columns: 1fr;
    }

    .helper-text {
      font-size: 0.8rem;
    }
  }

  @media (max-width: 420px) {
    .error-msg {
      padding: 10px 12px;
      font-size: 0.88rem;
    }
  }

  .error-msg {
    background: #ffe7e4;
    border: 1px solid #f2b5ad;
    color: #7a1a0d;
    padding: 12px 14px;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
  }

  .helper-text {
    margin-top: 6px;
    font-size: 0.85rem;
    color: #777;
  }
</style>

<body>
<div class="split-screen">
  <div class="left-panel">
    <div class="overlay"></div>
    <div class="left-content">
      <h1>Join <br><span>Haarlem</span></h1>
      <p>Create an account to explore everything Haarlem has to offer - from stories and history to restaurants, dance, and jazz.</p>
      <div class="brand-footer">THE CITY OF HAARLEM</div>
    </div>
  </div>

  <div class="right-panel">
    <div class="form-container">
      <a href="/loginForm<?= !empty($next) ? '?next=' . urlencode((string) $next) : '' ?>" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Login</a>

      <h2>Create Account</h2>
      <p class="subtitle">Register to access all of Haarlem's features.</p>

      <?php if (!empty($errorMessage)): ?>
        <div class="error-msg"><?= htmlspecialchars((string) $errorMessage, ENT_QUOTES, 'UTF-8') ?></div>
      <?php elseif (isset($error)): ?>
        <div class="error-msg"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>

      <form action="/register" method="POST" autocomplete="on">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) ($csrf ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="next" value="<?= htmlspecialchars((string) ($next ?? ''), ENT_QUOTES, 'UTF-8') ?>">

        <div class="two-col">
          <div class="form-group">
            <label>First Name</label>
            <div class="input-wrapper">
              <i class="fa-regular fa-id-card input-icon"></i>
              <input type="text" name="first_name" placeholder="Your first name" autocomplete="given-name" required>
            </div>
          </div>

          <div class="form-group">
            <label>Last Name</label>
            <div class="input-wrapper">
              <i class="fa-regular fa-id-card input-icon"></i>
              <input type="text" name="last_name" placeholder="Your last name" autocomplete="family-name" required>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label>Username</label>
          <div class="input-wrapper">
            <i class="fa-regular fa-user input-icon"></i>
            <input type="text" name="username" placeholder="Choose a username" autocomplete="username" required>
          </div>
          <div class="helper-text">You can use your username or email to sign in.</div>
        </div>

        <div class="form-group">
          <label>Email</label>
          <div class="input-wrapper">
            <i class="fa-regular fa-envelope input-icon"></i>
            <input type="email" name="email" placeholder="you@example.com" autocomplete="email" required>
          </div>
        </div>

        <div class="form-group">
          <label>Phone (optional)</label>
          <div class="input-wrapper">
            <i class="fa-solid fa-phone input-icon"></i>
            <input type="tel" name="phone" placeholder="+31 6 12345678" autocomplete="tel">
          </div>
        </div>

        <div class="form-group">
          <label>Password</label>
          <div class="input-wrapper">
            <i class="fa-solid fa-lock input-icon"></i>
            <input type="password" name="password" id="password" placeholder="Create a password" autocomplete="new-password" minlength="8" required>
            <button type="button" class="toggle-password" aria-label="Show or hide password">
              <i class="fa-regular fa-eye"></i>
            </button>
          </div>
          <div class="helper-text">Minimum 8 characters.</div>
        </div>

        <button type="submit" class="btn-signin">Create Account</button>

        <p class="signup-prompt">Already have an account? <a href="/loginForm<?= !empty($next) ? '?next=' . urlencode((string) $next) : '' ?>">Sign in</a></p>
      </form>
    </div>
  </div>
</div>
