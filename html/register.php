<?php
require_once __DIR__ . '/db/auth.php';

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

<main class="min-h-screen flex items-center justify-center bg-[#f8f5ef] px-4 py-8">
  <div class="bg-white border border-gray-300 p-6 w-full max-w-md">
    <a href="/index.php" class="block mb-5 font-bold text-[#2e5435]">HighFlights</a>
    <h1 class="text-xl font-bold text-[#2e5435] mb-1">Account aanmaken</h1>
    <p class="text-sm text-gray-500 mb-5">Al een account? <a href="/login.php" class="text-[#2e5435] font-semibold">Log hier in ›</a></p>

    <?php if ($error !== ''): ?>
      <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 text-sm mb-4">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form action="/register.php" method="post" novalidate class="flex flex-col gap-3">

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label for="first-name" class="block text-sm font-semibold text-[#2e5435] mb-1">Voornaam *</label>
          <input type="text" id="first-name" name="first_name" placeholder="geweldige Tobi" required autocomplete="given-name"
                 value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>"
                 class="w-full border border-gray-300 px-3 py-2 text-sm" />
        </div>
        <div>
          <label for="last-name" class="block text-sm font-semibold text-[#2e5435] mb-1">Achternaam *</label>
          <input type="text" id="last-name" name="last_name" placeholder="De beste" required autocomplete="family-name"
                 value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>"
                 class="w-full border border-gray-300 px-3 py-2 text-sm" />
        </div>
      </div>

      <div>
        <label for="email" class="block text-sm font-semibold text-[#2e5435] mb-1">E-mailadres *</label>
        <input type="email" id="email" name="email" placeholder="you@example.com" required autocomplete="email"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
               class="w-full border border-gray-300 px-3 py-2 text-sm" />
      </div>

      <div>
        <label for="password" class="block text-sm font-semibold text-[#2e5435] mb-1">Wachtwoord *</label>
        <input type="password" id="password" name="password" placeholder="Kies een sterk wachtwoord" required autocomplete="new-password"
               class="w-full border border-gray-300 px-3 py-2 text-sm" />
      </div>

      <div>
        <label for="password-confirm" class="block text-sm font-semibold text-[#2e5435] mb-1">Wachtwoord bevestigen *</label>
        <input type="password" id="password-confirm" name="password_confirm" placeholder="Herhaal je wachtwoord" required autocomplete="new-password"
               class="w-full border border-gray-300 px-3 py-2 text-sm" />
      </div>

      <button type="submit" class="bg-[#2e5435] hover:bg-[#1e3b24] text-white font-bold py-2 rounded text-sm mt-1">Account aanmaken</button>
    </form>
  </div>
</main>

</body>
</html>