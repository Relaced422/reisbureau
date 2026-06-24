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
            header('Location: /a_index.php');
        } else {
            header('Location: /account.php');
        }
        exit;
    } else {
        $error = 'E-mailadres of wachtwoord klopt niet.';
    }
}
?>
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

<main class="min-h-screen flex items-center justify-center bg-[#f8f5ef] px-4 py-8">
  <div class="bg-white border border-gray-300 p-6 w-full max-w-sm">
    <a href="/index.php" class="block mb-5 font-bold text-[#2e5435]">HighFlights</a>
    <h1 class="text-xl font-bold text-[#2e5435] mb-1">Log in</h1>
    <p class="text-sm text-gray-500 mb-5">Nog geen account? <a href="/register.php" class="text-[#2e5435] font-semibold">Registreer je hier ›</a></p>

    <?php if ($error !== ''): ?>
      <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 text-sm mb-4">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form action="/login.php" method="post" novalidate class="flex flex-col gap-3">
      <div>
        <label for="email" class="block text-sm font-semibold text-[#2e5435] mb-1">E-mailadres</label>
        <input type="email" id="email" name="email" placeholder="you@example.com" required autocomplete="email"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
               class="w-full border border-gray-300 px-3 py-2 text-sm" />
      </div>
      <div>
        <label for="password" class="block text-sm font-semibold text-[#2e5435] mb-1">Wachtwoord</label>
        <input type="password" id="password" name="password" placeholder="Jouw wachtwoord" required autocomplete="current-password"
               class="w-full border border-gray-300 px-3 py-2 text-sm" />
      </div>
      <div class="text-right">
        <a href="/forgot-password.html" class="text-xs text-gray-400">Wachtwoord vergeten?</a>
      </div>
      <button type="submit" class="bg-[#2e5435] hover:bg-[#1e3b24] text-white font-bold py-2 rounded text-sm">Inloggen</button>
    </form>
  </div>
</main>

</body>
</html>