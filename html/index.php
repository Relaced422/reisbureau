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
  <link rel="stylesheet" href="style.css" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>@import url('https://fonts.googleapis.com/css2?family=Kadwa:wght@400;700&display=swap');</style>
</head>
<body class="bg-[#f8f5ef]">

<?php include __DIR__ . '/includes/header.php'; ?>

<!-- ── Hero ── -->
<section class="bg-[#2e5435] min-h-[85vh] flex items-center">
  <div class="max-w-5xl mx-auto px-6 py-20 w-full text-center">
    <h1 class="text-white text-4xl md:text-5xl font-bold leading-tight mb-3" style="font-family:'Kadwa',serif;">
      Your High (Quality) Journey<br>Starts Here
    </h1>
    <p class="text-[#a3c7a7] text-lg mb-10">Your comfort is our top priority</p>

    <!-- Search card -->
    <div class="bg-white rounded-2xl shadow-xl p-8 text-left">
      <form action="destinations.html" method="get">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
          <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-[#2e5435] uppercase tracking-wide">Destination</label>
            <input type="text" name="destination" placeholder="Where to?"
              class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#2e5435]" />
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-[#2e5435] uppercase tracking-wide">Departure Date</label>
            <input type="date" name="departure"
              class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#2e5435]" />
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-[#2e5435] uppercase tracking-wide">Return Date</label>
            <input type="date" name="return"
              class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#2e5435]" />
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-[#2e5435] uppercase tracking-wide">Travelers</label>
            <select name="travelers"
              class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#2e5435] bg-white">
              <option value="1">1 traveler</option>
              <option value="2" selected>2 travelers</option>
              <option value="3">3 travelers</option>
              <option value="4">4 travelers</option>
              <option value="5">5 travelers</option>
              <option value="6">6+ travelers</option>
            </select>
          </div>
        </div>

        <!-- Budget slider -->
        <div class="mb-5">
          <label class="text-xs font-semibold text-[#2e5435] uppercase tracking-wide block mb-2">Budget (EUR)</label>
          <div class="flex items-center gap-4">
            <input type="range" id="budget" name="budget" min="500" max="15000" value="6000" class="flex-1 accent-[#2e5435]" />
            <span id="budget-display" class="text-sm font-bold text-[#2e5435] w-24 text-right">€ 6.000</span>
          </div>
        </div>

        <!-- Extra options -->
        <div class="mb-6">
          <p class="text-xs font-semibold text-[#2e5435] uppercase tracking-wide mb-3">Extra Options</p>
          <div class="flex flex-wrap gap-2">
            <?php foreach ([
              'high-onboarding' => '🌿 High Onboarding',
              'hotel'           => '🏨 Hotel Included',
              'transport'       => '🚌 Transport Included',
              'pet-friendly'    => '🐾 Pet Friendly',
              'limits'          => '✋ I Know My Limits',
            ] as $val => $label): ?>
            <label class="flex items-center gap-2 px-3 py-1.5 border border-gray-200 rounded-full text-sm cursor-pointer hover:border-[#2e5435] hover:bg-[#f0f9f0] transition-colors">
              <input type="checkbox" name="extras[]" value="<?= $val ?>" class="accent-[#2e5435]" />
              <?= $label ?>
            </label>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="flex justify-end">
          <button type="submit" class="bg-[#2e5435] hover:bg-[#1e3b24] text-white font-bold px-8 py-3 rounded-xl transition-colors text-sm">
            Search Flights →
          </button>
        </div>
      </form>
    </div>
  </div>
</section>

<!-- Vlucht display -->
<section class="py-20 px-6">
  <div class="max-w-6xl mx-auto">
    <h2 class="text-2xl md:text-3xl font-bold text-[#2e5435] mb-2" style="font-family:'Kadwa',serif;">Popular Destinations</h2>
    <p class="text-gray-500 mb-10">Hand-selected destinations where the calm continues long after you've landed.</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($flights as $f): ?>
      <a href="flight-detail.html" class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden flex flex-col no-underline">
        <div class="bg-[#2e5435] h-32 flex items-center justify-center text-5xl">✈️</div>
        <div class="p-5 flex flex-col flex-1">
          <div class="font-bold text-[#2e5435] text-lg mb-1"><?= htmlspecialchars($f['destination_name']) ?></div>
          <div class="text-gray-400 text-xs mb-4"><?= htmlspecialchars($f['airline']) ?> · <?= date('d M Y', strtotime($f['departure_date'])) ?></div>
          <div class="flex items-center justify-between mt-auto">
            <span class="font-bold text-[#2e5435]">from €<?= number_format($f['price'], 0, ',', '.') ?></span>
            <span class="text-xs border border-[#2e5435] text-[#2e5435] rounded-lg px-3 py-1 hover:bg-[#2e5435] hover:text-white transition-colors">View →</span>
          </div>
        </div>
      </a>
      <?php endforeach; ?>

      <?php if (empty($flights)): ?>
      <p class="text-gray-400 col-span-4">Momenteel geen vluchten beschikbaar.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- The HighFlights Standard -->
<section class="bg-[#1e3b24] py-20 px-6">
  <div class="max-w-6xl mx-auto">
    <h2 class="text-2xl md:text-3xl font-bold text-white mb-2" style="font-family:'Kadwa',serif;">The HighFlights Standard</h2>
    <p class="text-[#a3c7a7] mb-12">We don't oversell. We don't overcomplicate.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

      <?php
      $standards = [
        ['icon' => '✈️', 'title' => 'Cannabis-friendly flights', 'desc' => 'Every flight we book allows in-flight cannabis consumption. Exhale before you even land.'],
        ['icon' => '🌿', 'title' => 'Pre-rolls included', 'desc' => 'Sourced from growers we know personally. Rolled the morning of departure. Matched to your destination.'],
        ['icon' => '🗺️', 'title' => 'Curated destinations', 'desc' => 'Slow cities, quiet coasts, retreats that don\'t require an itinerary. You pick the place, we handle the rest.'],
      ];

      foreach ($standards as $item) {
      ?>

      <div class="border-l-4 border-[#a3c7a7] pl-6">
        <div class="text-3xl mb-4"><?php echo $item['icon']; ?></div>
        <h3 class="text-white font-bold text-lg mb-2"><?php echo $item['title']; ?></h3>
        <p class="text-[#a3c7a7] text-sm leading-relaxed"><?php echo $item['desc']; ?></p>
      </div>

      <?php
      }
      ?>

    </div>
  </div>
</section>

<!-- Reviews -->
<section class="py-20 px-6">
  <div class="max-w-6xl mx-auto">
    <h2 class="text-2xl md:text-3xl font-bold text-[#2e5435] mb-2" style="font-family:'Kadwa',serif;">What travelers say</h2>
    <p class="text-gray-500 mb-10">Real experiences from real HighFlights customers.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php foreach ($reviews as $r): ?>
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col gap-3">
        <div class="flex items-start justify-between">
          <div>
            <div class="font-bold text-[#2e5435] text-sm"><?= htmlspecialchars($r['user_name']) ?></div>
            <div class="text-gray-400 text-xs"><?= date('M Y', strtotime($r['created_at'])) ?> · <?= htmlspecialchars($r['destination_name']) ?></div>
          </div>
          <div class="text-yellow-400 text-sm"><?= str_repeat('★', $r['rating']) . str_repeat('☆', 5 - $r['rating']) ?></div>
        </div>
        <p class="text-gray-600 text-sm leading-relaxed">"<?= htmlspecialchars($r['review_text']) ?>"</p>
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