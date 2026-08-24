<?php
require_once __DIR__ . '/db/auth.php';
$pdo = getDB();

$destination = $_GET['destination'] ?? '';
$departure = $_GET['departure'] ?? '';
$return = $_GET['return'] ?? '';
$travelers = $_GET['travelers'] ?? 1;
$budget = $_GET['budget'] ?? 99999;

// zoeken gaat nu op destinations.name (de naam staat niet meer in flights)
$stmt = $pdo->prepare('SELECT flights.*, destinations.name AS destination_name, airlines.name AS airline FROM flights JOIN destinations ON flights.destination_id = destinations.id JOIN airlines ON flights.airline_id = airlines.id WHERE flights.active = 1 AND destinations.name LIKE ? AND flights.price <= ? ORDER BY flights.price ASC');
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
  <section class="py-8 px-4">
    <div class="max-w-6xl mx-auto">
      <h1 class="text-2xl font-bold text-[#2e5435] mb-4" style="font-family:'Kadwa',serif;">
        Flights to <?php echo htmlspecialchars($destination); ?>
      </h1>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        <?php
        if (empty($flights)) {
          echo '<p class="text-gray-400 col-span-3">No flights found for this search.</p>';
        }

        foreach ($flights as $f) {
          $total_price = $f['price'] * $travelers;
        ?>

          <div class="bg-white border border-gray-300 overflow-hidden flex flex-col">
            <div class="bg-[#2e5435] h-24 flex items-center justify-center text-4xl">✈️</div>
            <div class="p-4 flex flex-col flex-1">
              <div class="font-bold text-[#2e5435] mb-1">
                <?php echo htmlspecialchars($f['destination_name']); ?>
              </div>
              <div class="text-gray-400 text-xs mb-3">
                <?php echo htmlspecialchars($f['airline']); ?> ·
                <?php echo date('d M Y', strtotime($f['departure_date'])); ?>
              </div>
              <div class="flex items-center justify-between mt-auto">
                <span class="font-bold text-[#2e5435]">€<?php echo $total_price; ?></span>
                <a href="flight-detail.php?id=<?php echo $f['id']; ?>" class="text-xs bg-[#2e5435] text-white px-3 py-1 hover:bg-[#1e3b24]">Info →</a>
                <a href="booking.php?id=<?php echo $f['id']; ?>" class="text-xs bg-[#2e5435] text-white px-3 py-1 hover:bg-[#1e3b24]">Boek →</a>
              </div>
            </div>
          </div>

        <?php
        }
        ?>

      </div>
    </div>
  </section>

  <?php include __DIR__ . '/includes/footer.php'; ?>

</body>

</html>