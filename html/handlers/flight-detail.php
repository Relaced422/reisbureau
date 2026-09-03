<?
require_once dirname(__DIR__) . '/config.php';
require_once __DIR__ . '/../db/auth.php';
$pdo = getDB();

$id = $_GET['id'] ?? 0;
// joins voor de naam en airline, HTML kan hetzelfde blijven door de aliases
$stmt = $pdo->prepare('SELECT flights.*, destinations.name AS destination_name, airlines.name AS airline FROM flights JOIN destinations ON flights.destination_id = destinations.id JOIN airlines ON flights.airline_id = airlines.id WHERE flights.id = ? AND flights.active = 1');
$stmt->execute([$id]);
$flight = $stmt->fetch();

if (!$flight) {
    header('Location: /destinations.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vlucht naar <?= htmlspecialchars($flight['destination_name']) ?> — HighFlights</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kadwa:wght@400;700&display=swap');
    </style>
</head>

<body class="bg-[#f8f5ef]">

    <? include __DIR__ . '/includes/header.php'; ?>

    <div class="bg-[#2e5435] py-12 px-6">
        <div class="max-w-3xl mx-auto">
            <a href="destinations.php" class="text-[#a3c7a7] text-sm hover:text-white mb-3 inline-block">← Terug naar vluchten</a>
            <h1 class="text-white text-3xl font-bold mb-1 [font-family:'Kadwa',_serif]">
                Vlucht naar <?= htmlspecialchars($flight['destination_name']) ?>
            </h1>
            <p class="text-[#a3c7a7]"><?= htmlspecialchars($flight['departure_name']) ?> > <?= htmlspecialchars($flight['destination_name']) ?></p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-6 py-10 flex flex-col gap-6">

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-bold text-[#2e5435] mb-3">Heenvlucht (<?= htmlspecialchars($flight['destination_name']) ?>)</h2>
            <p class="text-sm text-gray-500">Vertrek: <?= date('d-m-Y H:i', strtotime($flight['departure_date'])) ?></p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-bold text-[#2e5435] mb-3">Retourvlucht (<?= htmlspecialchars($flight['departure_name']) ?>)</h2>
            <p class="text-sm text-gray-500">Terug: <?= date('d-m-Y H:i', strtotime($flight['return_date'])) ?></p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-bold text-[#2e5435] mb-3">Vlucht info</h2>
            <p class="text-sm text-gray-500">Maatschappij: <?= htmlspecialchars($flight['airline']) ?></p>
            <p class="text-sm text-gray-500">Beschikbare stoelen: <?= $flight['seats'] ?></p>
            <p class="text-sm text-gray-500">Prijs per persoon: €<?= $flight['price'] ?></p>
        </div>

    </div>

    <? include __DIR__ . '/includes/footer.php'; ?>

</body>

</html>