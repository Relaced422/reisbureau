<?php
require_once __DIR__ . '/db/auth.php';
requireLogin();

$pdo = getDB();
$msg = '';
$msgType = 'green';

$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking'])) {
    $pdo->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ? AND user_id = ?")
        ->execute([(int)$_POST['booking_id'], $user['id']]);
    $msg = 'Je boeking is geannuleerd.';
    $msgType = 'red';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name']  ?? '');
    $email      = trim($_POST['email']      ?? '');

    $check = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
    $check->execute([$email, $user['id']]);

    if ($check->fetch()) {
        $msg = 'Dat e-mailadres is al in gebruik.';
        $msgType = 'red';
    } else {
        $pdo->prepare('UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?')
            ->execute([$first_name, $last_name, $email, $user['id']]);
        $_SESSION['user_name'] = $first_name;
        $msg = 'Profiel opgeslagen.';

        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$user['id']]);
        $user = $stmt->fetch();
    }
}

$bookings = $pdo->prepare('SELECT * FROM bookings WHERE user_id = ? ORDER BY created_at DESC');
$bookings->execute([$user['id']]);
$bookings = $bookings->fetchAll();

$statusLabel = ['confirmed' => 'Bevestigd', 'pending' => 'In afwachting', 'cancelled' => 'Geannuleerd'];
$statusColor = ['confirmed' => 'bg-green-100 text-green-700', 'pending' => 'bg-yellow-100 text-yellow-700', 'cancelled' => 'bg-red-100 text-red-500'];
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Account — HighFlights</title>
  <link rel="stylesheet" href="style.css" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>@import url('https://fonts.googleapis.com/css2?family=Kadwa:wght@400;700&display=swap');</style>
</head>
<body class="bg-[#f8f5ef]">

<?php include __DIR__ . '/includes/header.php'; ?>

<div class="max-w-6xl mx-auto px-6 py-12 flex flex-col md:flex-row gap-10">

  <!-- Sidebar -->
  <aside class="w-full md:w-64 shrink-0">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col items-center text-center mb-4">
      <div class="text-5xl mb-3">🌿</div>
      <div class="font-bold text-[#2e5435] text-lg"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></div>
      <div class="text-xs text-gray-400 mt-1"><?= htmlspecialchars($user['email']) ?></div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <a href="account.php" class="flex items-center gap-3 px-5 py-3 text-sm font-semibold text-[#2e5435] bg-[#f0f9f0] border-l-4 border-[#2e5435]">
        📋 Mijn boekingen
      </a>
      <a href="logout.php" class="flex items-center gap-3 px-5 py-3 text-sm text-red-400 hover:bg-red-50 transition-colors">
        ← Uitloggen
      </a>
    </div>
  </aside>

  <!-- Main content -->
  <main class="flex-1 flex flex-col gap-8">

    <?php if ($msg): ?>
    <div class="rounded-xl px-4 py-3 text-sm font-medium
      <?= $msgType === 'red' ? 'bg-red-50 border border-red-200 text-red-700' : 'bg-green-50 border border-green-200 text-green-700' ?>">
      <?= htmlspecialchars($msg) ?>
    </div>
    <?php endif; ?>

    <!-- Boekingen -->
    <section>
      <h2 class="text-xl font-bold text-[#2e5435] mb-4" style="font-family:'Kadwa',serif;">Mijn boekingen</h2>

      <?php if (empty($bookings)): ?>
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center text-gray-400">
        Nog geen boekingen.
        <a href="destinations.php" class="block mt-2 text-[#2e5435] font-semibold hover:underline">Bekijk onze vluchten →</a>
      </div>
      <?php endif; ?>

      <div class="flex flex-col gap-4">
        <?php foreach ($bookings as $b): ?>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col sm:flex-row sm:items-center gap-5">
          <div class="flex-1">
            <div class="flex flex-wrap items-center gap-2 mb-2">
              <span class="font-bold text-[#2e5435]">AMS → <?= htmlspecialchars($b['destination_name']) ?></span>
              <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full <?= $statusColor[$b['status']] ?? 'bg-gray-100 text-gray-500' ?>">
                <?= $statusLabel[$b['status']] ?? $b['status'] ?>
              </span>
            </div>
            <div class="text-sm text-gray-400 mb-1">
              <?= date('d M Y', strtotime($b['departure_date'])) ?> · <?= $b['travelers'] ?> reiziger<?= $b['travelers'] > 1 ? 's' : '' ?>
            </div>
            <?php if ($b['extras']): ?>
            <div class="text-xs text-gray-400 mb-1">Extra's: <?= htmlspecialchars($b['extras']) ?></div>
            <?php endif; ?>
            <div class="text-xs text-gray-400">Ref: <strong class="text-gray-600"><?= htmlspecialchars($b['reference']) ?></strong></div>
          </div>
          <div class="flex flex-col items-end gap-3">
            <div class="text-xl font-bold text-[#2e5435]">€<?= number_format($b['total_price'], 2, ',', '.') ?></div>
            <?php if ($b['status'] !== 'cancelled'): ?>
            <form method="post" onsubmit="return confirm('Weet je zeker dat je wil annuleren?');">
              <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
              <button type="submit" name="cancel_booking"
                class="text-xs text-red-400 border border-red-200 hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors">
                Annuleer
              </button>
            </form>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- Profiel -->
    <section>
      <h2 class="text-xl font-bold text-[#2e5435] mb-4" style="font-family:'Kadwa',serif;">Persoonlijke gegevens</h2>
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form method="post" class="flex flex-col gap-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="flex flex-col gap-1">
              <label class="text-xs font-semibold text-[#2e5435] uppercase tracking-wide">Voornaam</label>
              <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required
                class="border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#2e5435] transition-colors" />
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs font-semibold text-[#2e5435] uppercase tracking-wide">Achternaam</label>
              <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required
                class="border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#2e5435] transition-colors" />
            </div>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-[#2e5435] uppercase tracking-wide">E-mailadres</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required
              class="border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#2e5435] transition-colors" />
          </div>
          <div>
            <button type="submit" name="update_profile"
              class="bg-[#2e5435] hover:bg-[#1e3b24] text-white font-bold px-8 py-3 rounded-xl text-sm transition-colors">
              Opslaan
            </button>
          </div>
        </form>
      </div>
    </section>

  </main>
</div>

<footer class="bg-[#1e3b24] text-[#a3c7a7] py-12 px-6 mt-10">
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
        <div class="text-white font-semibold mb-2">Legal</div>
        <ul class="space-y-1">
          <li><a href="privacy.html" class="hover:text-white">Privacy Policy</a></li>
          <li><a href="terms.html" class="hover:text-white">Terms</a></li>
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