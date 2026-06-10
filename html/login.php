<?php
require_once __DIR__ . '/db/auth.php';

// Al ingelogd? Doorsturen
if (isLoggedIn()) {
    header('Location: /account.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if (login($email, $password)) {
        // Stuur admin door naar panel, klant naar account
        if (isAdmin()) {
            header('Location: /admin/a_index.php');
        } else {
            header('Location: /account.php');
        }
        exit;
    } else {
        $error = 'E-mailadres of wachtwoord klopt niet.';
    }
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login — HighFlights</title>
  <link rel="stylesheet" href="/style.css" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>@import url('https://fonts.googleapis.com/css2?family=Kadwa:wght@400;700&display=swap');</style>
</head>
<body>

<main style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:var(--off-white);padding:40px 16px;">
  <div style="background:var(--white);border:1.5px solid var(--border);border-radius:var(--radius);padding:40px 36px;width:100%;max-width:420px;box-shadow:var(--shadow);">
    <a href="/index.php" class="logo" style="display:inline-block;margin-bottom:28px;">
      <span class="logo-pill">🌿 HighFlights</span>
    </a>
    <h1 style="font-size:1.4rem;font-weight:700;color:var(--dark-green);margin-bottom:6px;">Log in</h1>
    <p style="font-size:0.88rem;color:var(--muted);margin-bottom:24px;">Nog geen account? <a href="/register.php" style="color:var(--dark-green);font-weight:600;">Registreer je hier ›</a></p>

    <?php if ($error !== ''): ?>
      <div style="background:#fef2f2;border:1px solid #fca5a5;color:#b91c1c;border-radius:8px;padding:12px 16px;font-size:0.875rem;margin-bottom:20px;">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form action="/login.php" method="post" novalidate>
      <div class="form-group" style="margin-bottom:16px;">
        <label for="email" style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:6px;color:var(--dark-green);">E-mailadres</label>
        <input type="email" id="email" name="email" placeholder="you@example.com" required autocomplete="email"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
               style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;outline:none;" />
      </div>
      <div class="form-group" style="margin-bottom:8px;">
        <label for="password" style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:6px;color:var(--dark-green);">Wachtwoord</label>
        <input type="password" id="password" name="password" placeholder="Jouw wachtwoord" required autocomplete="current-password"
               style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;outline:none;" />
      </div>
      <div style="text-align:right;margin-bottom:24px;">
        <a href="/forgot-password.html" style="font-size:0.82rem;color:var(--muted);">Wachtwoord vergeten?</a>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;padding:12px;">Inloggen</button>
    </form>
  </div>
</main>

</body>
</html>