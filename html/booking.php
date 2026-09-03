<?
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db/auth.php';
requireLogin();
$pdo = getDB();

$flight = null;
$success = false;

$id = $_GET['id'];

$stmt = $pdo->prepare('SELECT flights.*, destinations.name AS destination_name, airlines.name AS airline FROM flights JOIN destinations ON flights.destination_id = destinations.id JOIN airlines ON flights.airline_id = airlines.id WHERE flights.id = ? AND flights.active = 1');
$stmt->execute([$id]);
$flight = $stmt->fetch();

if ($_POST) {
    $travelers = $_POST['travelers'];
    $total = $flight['price'] * $travelers;
    $reference = 'HF-' . uniqid();
    // alleen nog de IDs opslaan, naam/bestemming/datum halen we op via joins
    $stmt = $pdo->prepare('INSERT INTO bookings (user_id, flight_id, reference, travelers, total_price, status) VALUES (?, ?, ?, ?, ?, \'pending\')');
    $stmt->execute([$_SESSION['user_id'], $flight['id'], $reference, $travelers, $total]);
    $success = true;
}

// destination_name en departure_date staan niet meer in bookings, dus via flights erbij joinen
$bookings = $pdo->prepare('SELECT bookings.*, destinations.name AS destination_name, flights.departure_date FROM bookings JOIN flights ON bookings.flight_id = flights.id JOIN destinations ON flights.destination_id = destinations.id WHERE bookings.user_id = ? ORDER BY bookings.created_at DESC');
$bookings->execute([$_SESSION['user_id']]);
$bookings = $bookings->fetchAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8" />
    <title>Boeken — HighFlights</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100">

<? include __DIR__ . '/includes/header.php'; ?>

<div class="max-w-lg mx-auto mt-8 px-4">

    <? if ($flight) { ?>
        <h1 class="text-xl font-bold text-[#2e5435] mb-2"><?= $flight['destination_name'] ?></h1>
        <p class="text-sm mb-1"><?= $flight['airline'] ?> — <?= $flight['departure_date'] ?> t/m <?= $flight['return_date'] ?></p>
        <p class="text-sm font-bold mb-4">€<?= $flight['price'] ?> p.p.</p>

        <? if ($success) { ?>
            <p class="text-green-700 border border-green-300 bg-green-50 px-3 py-2 text-sm mb-4">Boeking geplaatst!</p>
        <? } else { ?>
            <form method="post" class="flex flex-col gap-3 text-sm mb-6">
                <label class="font-semibold">Reizigers
                    <select name="travelers" id="travelers" class="ml-2 border border-gray-300 px-2 py-1">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                    </select>
                </label>
                <p>Totaal: <strong id="total">€<?= $flight['price'] ?></strong></p>
                <button type="submit" class="bg-[#2e5435] text-white px-4 py-2 w-fit">Boek</button>
            </form>
        <? } ?>
    <? } ?>

    <hr class="mb-4">

    <h2 class="text-lg font-bold mb-3">Mijn boekingen</h2>
    <? if (empty($bookings)) { ?>
        <p class="text-sm">Nog geen boekingen.</p>
    <? } ?>
    <? foreach ($bookings as $b) { ?>
        <div class="border border-gray-300 bg-white p-3 text-sm mb-2">
            <p class="font-bold text-[#2e5435]"><?= $b['destination_name'] ?></p>
            <p><?= $b['departure_date'] ?> · <?= $b['travelers'] ?> reiziger(s)</p>
            <p>€<?= $b['total_price'] ?> · <?= $b['status'] ?> · <?= $b['reference'] ?></p>
        </div>
    <? } ?>

</div>

<script>
    // Pak het HTML element met ID 'travelers' & 'total' 
    const travelers = document.getElementById('travelers');
    const total = document.getElementById('total');
    const price = <?= $flight ? $flight['price'] : 0 ?>;
    
    if (travelers) {
        // LOGICA AI SUGGESTIE! Refresh wanneer ingevoerd
        travelers.addEventListener('change', () => {
            total.textContent = '€' + (travelers.value * price).toFixed(2);
        });
    }
</script>

<? include __DIR__ . '/includes/footer.php'; ?>

</body>
</html>