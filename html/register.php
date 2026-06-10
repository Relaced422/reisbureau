<?php
require_once __DIR__ . '/includes/auth.php';

// Al ingelogd? Doorsturen
if (isLoggedIn()) {
    header('Location: /index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name']  ?? '');
    $email      = trim($_POST['email']      ?? '');
    $password   = $_POST['password']        ?? '';

    $registered = register([
        'first_name' => $first_name,
        'last_name'  => $last_name,
        'email'      => $email,
        'password'   => $password,
    ]);

    if ($registered) {
        login($email, $password);
        header('Location: /account.php');
        exit;
    } else {
        $error = 'Dit e-mailadres is al in gebruik.';
    }
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registreren — HighFlights</title>
  <link rel="stylesheet" href="/style.css" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>@import url('https://fonts.googleapis.com/css2?family=Kadwa:wght@400;700&display=swap');</style>
</head>
<body>

<main style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:var(--off-white);padding:40px 16px;">
  <div style="background:var(--white);border:1.5px solid var(--border);border-radius:var(--radius);padding:40px 36px;width:100%;max-width:480px;box-shadow:var(--shadow);">
    <a href="/index.php" class="logo" style="display:inline-block;margin-bottom:28px;">
      <span class="logo-pill">🌿 HighFlights</span>
    </a>
    <h1 style="font-size:1.4rem;font-weight:700;color:var(--dark-green);margin-bottom:6px;">Account aanmaken</h1>
    <p style="font-size:0.88rem;color:var(--muted);margin-bottom:24px;">Al een account? <a href="/login.php" style="color:var(--dark-green);font-weight:600;">Log hier in ›</a></p>

    <?php if ($error !== ''): ?>
      <div style="background:#fef2f2;border:1px solid #fca5a5;color:#b91c1c;border-radius:8px;padding:12px 16px;font-size:0.875rem;margin-bottom:20px;">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form action="/register.php" method="post" novalidate>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
        <div>
          <label for="first-name" style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:6px;color:var(--dark-green);">Voornaam *</label>
          <input type="text" id="first-name" name="first_name" placeholder="Tobi" required autocomplete="given-name"
                 value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>"
                 style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;" />
        </div>
        <div>
          <label for="last-name" style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:6px;color:var(--dark-green);">Achternaam *</label>
          <input type="text" id="last-name" name="last_name" placeholder="Quenum" required autocomplete="family-name"
                 value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>"
                 style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;" />
        </div>
      </div>

      <div class="form-group" style="margin-bottom:16px;">
        <label for="email" style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:6px;color:var(--dark-green);">E-mailadres *</label>
        <input type="email" id="email" name="email" placeholder="you@example.com" required autocomplete="email"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
               style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;" />
      </div>

      <div class="form-group" style="margin-bottom:16px;">
        <label for="password" style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:6px;color:var(--dark-green);">Wachtwoord *</label>
        <input type="password" id="password" name="password" placeholder="Kies een sterk wachtwoord" required autocomplete="new-password"
               style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;" />
      </div>

      <div class="form-group" style="margin-bottom:28px;">
        <label for="password-confirm" style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:6px;color:var(--dark-green);">Wachtwoord bevestigen *</label>
        <input type="password" id="password-confirm" name="password_confirm" placeholder="Herhaal je wachtwoord" required autocomplete="new-password"
               style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;" />
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%;padding:12px;">Account aanmaken</button>
    </form>
  </div>
</main>

</body>
</html>