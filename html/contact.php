<?php
require_once __DIR__ . '/db/auth.php';
$pdo = getDB();

$success = false;
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        $error = 'Vul alle velden in.';
    } else {
        $pdo->prepare('INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)')
            ->execute([$name, $email, $message]);
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact — HighFlights</title>
  <link rel="stylesheet" href="style.css" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>@import url('https://fonts.googleapis.com/css2?family=Kadwa:wght@400;700&display=swap');</style>
</head>
<body class="bg-[#f8f5ef]">

<?php include __DIR__ . '/includes/header.php'; ?>

<section class="py-16 px-6">
  <div class="max-w-xl mx-auto">
    <h1 class="text-2xl font-bold text-[#2e5435] mb-8" style="font-family:'Kadwa',serif;">Contact</h1>

    <?php if ($success): ?>
      <p class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm">
        Bericht verzonden! We nemen zo snel mogelijk contact met je op.
      </p>
    <?php else: ?>

      <?php if ($error): ?>
        <p class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm mb-5">
          <?= htmlspecialchars($error) ?>
        </p>
      <?php endif; ?>

      <form method="post" class="flex flex-col gap-4">
        <div class="flex flex-col gap-1">
          <label class="text-sm font-semibold text-[#2e5435]">Naam</label>
          <input type="text" name="name" required
            value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
            class="border border-black rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#2e5435] bg-gray-200" />
        </div>

        <div class="flex flex-col gap-1">
          <label class="text-sm font-semibold text-[#2e5435]">E-mailadres</label>
          <input type="email" name="email" required
            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
            class="border border-black rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#2e5435] bg-gray-200" />
        </div>

        <div class="flex flex-col gap-1">
          <label class="text-sm font-semibold text-[#2e5435]">Bericht</label>
          <textarea name="message" rows="5" required
            class="border border-black rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#2e5435] resize-none bg-gray-200"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
        </div>

        <button type="submit"
          class="bg-[#2e5435] hover:bg-[#1e3b24] text-white font-bold py-3 rounded-xl text-sm transition-colors">
          Verstuur
        </button>
      </form>

    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

</body>
</html>