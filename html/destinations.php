<?php
require_once __DIR__ . '/db/auth.php';
$pdo = getDB();

$destination = $_GET['destination'] ?? '';
$departure = $_GET['departure'] ?? '';
$return = $_GET['return'] ?? '';
$travelers = $_GET['travelers'] ?? 1;
$budget = $_GET['budget'] ?? 99999;

$stmt = $pdo->prepare('SELECT * FROM flights WHERE active = 1 AND destination_name LIKE ? AND price <= ? ORDER BY price ASC');
$stmt->execute(['%' . $destination . '%', $budget]);
$flights = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HighFlights — Destinations</title>
  <link rel="stylesheet" href="style.css" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Kadwa:wght@400;700&display=swap');
  </style>
</head>

<body class="bg-[#f8f5ef]">

  <?php include __DIR__ . '/includes/header.php'; ?>

  <?php include __DIR__ . '/includes/searchbar.php'; ?>
  <section class="py-16 px-6">
    <div class="max-w-6xl mx-auto">
      <h1 class="text-2xl md:text-3xl font-bold text-[#2e5435] mb-2" style="font-family:'Kadwa',serif;">
        Flights to <?php echo htmlspecialchars($destination); ?>
      </h1>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <?php
        if (empty($flights)) {
          echo '<p class="text-gray-400 col-span-3">No flights found for this search.</p>';
        }

        foreach ($flights as $f) {
          $total_price = $f['price'] * $travelers;
        ?>

          <a href="flight-detail.html?id=<?php echo $f['id']; ?>"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden flex flex-col no-underline">
            <div class="bg-[#2e5435] h-32 flex items-center justify-center text-5xl">✈️</div>
            <div class="p-5 flex flex-col flex-1">
              <div class="font-bold text-[#2e5435] text-lg mb-1">
                <?php echo htmlspecialchars($f['destination_name']); ?>
              </div>
              <div class="text-gray-400 text-xs mb-4">
                <?php echo htmlspecialchars($f['airline']); ?> ·
                <?php echo date('d M Y', strtotime($f['departure_date'])); ?>
              </div>
              <div class="flex items-center justify-between mt-auto">
                <span class="font-bold text-[#2e5435]">€<?php echo $total_price; ?></span>
                <span
                  class="text-xs border border-[#2e5435] text-[#2e5435] rounded-lg px-3 py-1 hover:bg-[#2e5435] hover:text-white transition-colors">
                  View →
                </span>
              </div>
            </div>
          </a>

        <?php
        }
        ?>

      </div>
    </div>
  </section>

  <?php include __DIR__ . '/includes/footer.php'; ?>

</body>

</html>