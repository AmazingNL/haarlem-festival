<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Account - Haarlem Festival</title>

  <link rel="stylesheet" href="/assets/css/login.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script defer src="/assets/js/login.js"></script>

  <style>
    .two-col {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
    }
    @media (max-width: 520px) {
      .two-col { grid-template-columns: 1fr; }
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
</head>

<body>
  <div class="split-screen">
    <div class="left-panel">
      <div class="overlay"></div>
      <div class="left-content">
        <h1>Join <br><span>Haarlem</span></h1>
        <p>Create an account to explore everything Haarlem has to offer—from stories and history to restaurants, dance, and jazz.</p>
        <div class="brand-footer">THE CITY OF HAARLEM</div>
      </div>
    </div>

    <div class="right-panel">
      <div class="form-container">
        <a href="/login" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Login</a>

        <h2>Create Account</h2>
        <p class="subtitle">Register to access all of Haarlem's features.</p>

        <?php if (isset($error)): ?>
          <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="/register" method="POST" autocomplete="on">
          <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf ?? '') ?>">

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
              <button type="button" class="toggle-password" aria-label="Show/Hide password">
                <i class="fa-regular fa-eye"></i>
              </button>
            </div>
            <div class="helper-text">Minimum 8 characters.</div>
          </div>

          <button type="submit" class="btn-signin">Create Account</button>

          <p class="signup-prompt">Already have an account? <a href="/login">Sign in</a></p>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
