<?php
require_once __DIR__ . '/db/auth.php';
$pdo = getDB();

$success = false;
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['first_name'] ?? '') . ' ' . trim($_POST['last_name'] ?? '');
    $email   = trim($_POST['email']   ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === ' ' || $email === '' || $message === '') {
        $error = 'Vul alle verplichte velden in.';
    } else {
        $pdo->prepare('INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)')
            ->execute([trim($name), $email, $subject ?: null, $message]);
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

<!-- Page header -->
<div class="bg-[#2e5435] py-12 px-6">
  <div class="max-w-3xl mx-auto">
    <h1 class="text-white text-3xl font-bold mb-2" style="font-family:'Kadwa',serif;">Contact Us</h1>
    <p class="text-[#a3c7a7]">Got a question? We'd love to hear from you.</p>
  </div>
</div>

<section class="py-14 px-6">
  <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12">

    <!-- Info -->
    <div>
      <h2 class="text-xl font-bold text-[#2e5435] mb-4" style="font-family:'Kadwa',serif;">Get in touch</h2>
      <p class="text-gray-500 text-sm leading-relaxed mb-8">
        Whether you have a question about a booking, want to know more about what we offer, or just want to say hi — reach out. We usually respond within one business day.
      </p>

      <div class="flex flex-col gap-5">
        <?php foreach ([
          ['📧', 'Email',        'highflights@contact.com'],
          ['📍', 'Location',     'Nijmegen, Nederland'],
          ['⏰', 'Office hours', 'Monday – Friday, 09:00 – 17:00'],
        ] as [$icon, $label, $value]): ?>
        <div class="flex items-start gap-4">
          <span class="text-2xl"><?= $icon ?></span>
          <div>
            <div class="font-semibold text-[#2e5435] text-sm"><?= $label ?></div>
            <div class="text-gray-500 text-sm"><?= $value ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="mt-8 bg-[#f0f9f0] border border-[#b8e0bc] rounded-xl p-4 text-sm text-[#2e5435]">
        🌿 For urgent booking questions please include your booking reference number in your message.
      </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">

      <?php if ($success): ?>
      <div class="text-center py-8">
        <div class="text-4xl mb-4">✅</div>
        <h3 class="font-bold text-[#2e5435] text-lg mb-2">Bericht verzonden!</h3>
        <p class="text-gray-500 text-sm mb-6">We nemen zo snel mogelijk contact met je op.</p>
        <a href="contact.php" class="text-sm text-[#2e5435] font-semibold hover:underline">Nog een bericht sturen →</a>
      </div>
      <?php else: ?>

      <h3 class="font-bold text-[#2e5435] text-lg mb-6" style="font-family:'Kadwa',serif;">Send us a message</h3>

      <?php if ($error): ?>
      <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm mb-5">
        <?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>

      <form method="post" class="flex flex-col gap-4">
        <div class="grid grid-cols-2 gap-3">
          <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-[#2e5435] uppercase tracking-wide">Voornaam *</label>
            <input type="text" name="first_name" placeholder="Tobi" required
              value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>"
              class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#2e5435]" />
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-[#2e5435] uppercase tracking-wide">Achternaam *</label>
            <input type="text" name="last_name" placeholder="Quenum" required
              value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>"
              class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#2e5435]" />
          </div>
        </div>

        <div class="flex flex-col gap-1">
          <label class="text-xs font-semibold text-[#2e5435] uppercase tracking-wide">E-mailadres *</label>
          <input type="email" name="email" placeholder="you@example.com" required
            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
            class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#2e5435]" />
        </div>

        <div class="flex flex-col gap-1">
          <label class="text-xs font-semibold text-[#2e5435] uppercase tracking-wide">Onderwerp</label>
          <select name="subject"
            class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#2e5435] bg-white">
            <option value="">Selecteer een onderwerp</option>
            <option value="Vraag over boeking"    <?= ($_POST['subject'] ?? '') === 'Vraag over boeking'    ? 'selected' : '' ?>>Vraag over boeking</option>
            <option value="Annulering"             <?= ($_POST['subject'] ?? '') === 'Annulering'             ? 'selected' : '' ?>>Annulering</option>
            <option value="Bestemmingen"           <?= ($_POST['subject'] ?? '') === 'Bestemmingen'           ? 'selected' : '' ?>>Informatie bestemmingen</option>
            <option value="Algemene vraag"         <?= ($_POST['subject'] ?? '') === 'Algemene vraag'         ? 'selected' : '' ?>>Algemene vraag</option>
          </select>
        </div>

        <div class="flex flex-col gap-1">
          <label class="text-xs font-semibold text-[#2e5435] uppercase tracking-wide">Bericht *</label>
          <textarea name="message" rows="5" placeholder="Stel je vraag..." required
            class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#2e5435] resize-none"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
        </div>

        <button type="submit"
          class="bg-[#2e5435] hover:bg-[#1e3b24] text-white font-bold py-3 rounded-xl text-sm transition-colors">
          Verstuur bericht →
        </button>
      </form>
      <?php endif; ?>
    </div>

  </div>
</section>

<footer class="bg-[#1e3b24] text-[#a3c7a7] py-12 px-6">
  <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between gap-8 text-sm">
    <div class="font-bold text-white" style="font-family:'Kadwa',serif;">🌿 HighFlights</div>
    <div class="flex gap-10">
      <div>
        <div class="text-white font-semibold mb-2">Navigate</div>
        <ul class="space-y-1">
          <li><a href="index.php" class="hover:text-white">Home</a></li>
          <li><a href="destinations.php" class="hover:text-white">Destinations</a></li>
          <li><a href="contact.php" class="hover:text-white">Contact</a></li>
        </ul>
      </div>
      <div>
        <div class="text-white font-semibold mb-2">Account</div>
        <ul class="space-y-1">
          <li><a href="login.php" class="hover:text-white">Log in</a></li>
          <li><a href="register.php" class="hover:text-white">Register</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="max-w-6xl mx-auto mt-8 pt-6 border-t border-[#2e5435] text-xs flex justify-between">
    <span>© 2025 HighFlights — Tobi Quenum & Fero Seifert</span>
    <span>highflights@contact.com</span>
  </div>
</footer>

</body>
</html>