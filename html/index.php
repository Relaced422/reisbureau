<?php
require_once __DIR__ . '/db/auth.php';

$pdo = getDB();

$flights = $pdo->query('SELECT * FROM flights WHERE active = 1 ORDER BY price ASC LIMIT 4')->fetchAll();
$reviews = $pdo->query('SELECT * FROM reviews WHERE validated = 1 ORDER BY created_at DESC LIMIT 3')->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HighFlights — Your High (Quality) Journey</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>@import url('https://fonts.googleapis.com/css2?family=Kadwa:wght@400;700&display=swap');</style>
</head>
<body class="bg-[#f8f5ef]">

<?php include __DIR__ . '/includes/header.php'; ?>

<!-- ── Hero ── -->
<section class="bg-[#2e5435] py-12 px-4">
  <div class="max-w-5xl mx-auto w-full text-center">
    <h1 class="text-white text-3xl md:text-4xl font-bold mb-2" style="font-family:'Kadwa',serif;">
      Your High (Quality) Journey<br>Starts Here
    </h1>
    <p class="text-[#a3c7a7] mb-6">Your comfort is our top priority</p>

    <!-- Search card -->
    <?php include __DIR__ . '/includes/searchbar.php'; ?>
  </div>
</section>

<!-- Vlucht display -->
<section class="py-10 px-4">
  <div class="max-w-6xl mx-auto">
    <h2 class="text-2xl font-bold text-[#2e5435] mb-1" style="font-family:'Kadwa',serif;">Popular Destinations</h2>
    <p class="text-gray-500 mb-6">Hand-selected destinations where the calm continues long after you've landed.</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <?php foreach ($flights as $f){  ?>
      <a href="flight-detail.php" class="bg-white border border-gray-300 overflow-hidden flex flex-col no-underline">
        <div class="bg-[#2e5435] h-24 flex items-center justify-center text-4xl">✈️</div>
        <div class="p-4 flex flex-col flex-1">
          <div class="font-bold text-[#2e5435] mb-1"><?= htmlspecialchars($f['destination_name']) ?></div>
          <div class="text-gray-400 text-xs mb-3"><?= htmlspecialchars($f['airline']) ?> · <?= date('d M Y', strtotime($f['departure_date'])) ?></div>
          <div class="flex items-center justify-between mt-auto">
            <span class="font-bold text-[#2e5435]">from €<?= number_format($f['price'], 0, ',', '.') ?></span>
            <span class="text-xs border border-[#2e5435] text-[#2e5435] px-2 py-1 hover:bg-[#2e5435] hover:text-white">View →</span>
          </div>
        </div>
      </a>
      <?php }; ?>

      <?php if (empty($flights)): ?>
      <p class="text-gray-400 col-span-4">Momenteel geen vluchten beschikbaar.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- The HighFlights Standard -->
<section class="bg-[#1e3b24] py-10 px-4">
  <div class="max-w-6xl mx-auto">
    <h2 class="text-2xl font-bold text-white mb-1" style="font-family:'Kadwa',serif;">The HighFlights Standard</h2>
    <p class="text-[#a3c7a7] mb-8">We don't oversell. We don't overcomplicate.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

      <?php
      $standards = [
        ['title' => 'Cannabis-friendly flights', 'desc' => 'Every flight we book allows in-flight cannabis consumption. Exhale before you even land.'],
        ['title' => 'Pre-rolls included', 'desc' => 'Sourced from growers we know personally. Rolled the morning of departure. Matched to your destination.'],
        ['title' => 'Curated destinations', 'desc' => 'Slow cities, quiet coasts, retreats that don\'t require an itinerary. You pick the place, we handle the rest.'],
      ];

      foreach ($standards as $item) {
      ?>

      <div class="border-l-2 border-[#a3c7a7] pl-4">
        <h3 class="text-white font-bold mb-1"><?php echo $item['title']; ?></h3>
        <p class="text-[#a3c7a7] text-sm"><?php echo $item['desc']; ?></p>
      </div>

      <?php
      }
      ?>

    </div>
  </div>
</section>

<!-- Reviews -->
<section class="py-10 px-4">
  <div class="max-w-6xl mx-auto">
    <h2 class="text-2xl font-bold text-[#2e5435] mb-1" style="font-family:'Kadwa',serif;">What travelers say</h2>
    <p class="text-gray-500 mb-6">Real experiences from real HighFlights customers.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <?php foreach ($reviews as $r): ?>
      <div class="bg-white border border-gray-200 p-4 flex flex-col gap-2">
        <div class="flex items-start justify-between">
          <div>
            <div class="font-bold text-[#2e5435] text-sm"><?= htmlspecialchars($r['user_name']) ?></div>
            <!-- AI SUGGESTIE -->
            <div class="text-gray-400 text-xs"><?= date('M Y', strtotime($r['created_at'])) ?> · <?= htmlspecialchars($r['destination_name']) ?></div>
          </div>
          <!-- AI SUGGESTIE -->
          <div class="text-yellow-400 text-sm"><?= str_repeat('★', $r['rating']) . str_repeat('☆', 5 - $r['rating']) ?></div>
        </div>
        <p class="text-gray-600 text-sm">"<?= htmlspecialchars($r['review_text']) ?>"</p>
      </div>
      <?php endforeach; ?>

      <?php if (empty($reviews)): ?>
      <p class="text-gray-400">Nog geen reviews.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Footer -->
<?php include __DIR__ . '/includes/footer.php'; ?>


<script>
  const slider = document.getElementById('budget');
  const display = document.getElementById('budget-display');
  if (slider) {
    slider.addEventListener('input', () => {
      display.textContent = '€ ' + parseInt(slider.value).toLocaleString('nl-NL');
    });
  }
</script>

</body>
</html>