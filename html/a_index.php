<?
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db/auth.php';
requireAdmin();

$db = getDB();

// CRUD
// Gezien alle forms op 1 pagina staan request method gebruiken. Hoeft normaal niet maar had issues.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_flight') {
        // geen losse namen meer, gewoon de IDs uit de dropdowns. FK checkt of ze bestaan.
        $stmt = $db->prepare("INSERT INTO flights (destination_id, airline_id, departure_name, departure_date, return_date, price, seats, active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['destination_id'],
            $_POST['airline_id'],
            $_POST['departure_name'],
            $_POST['departure_date'],
            $_POST['return_date'],
            $_POST['price'],
            // inhoud anders 180
            $_POST['seats'] ?? 180,
            isset($_POST['active']) ? 1 : 0
        ]);
    }

    if ($action === 'edit_flight') {
        $stmt = $db->prepare("UPDATE flights SET departure_name=?, destination_id=?, airline_id=?, price=?, departure_date=?, return_date=?, seats=?, active=? WHERE id=?");
        $stmt->execute([
            $_POST['departure_name'],
            $_POST['destination_id'],
            $_POST['airline_id'],
            $_POST['price'],
            $_POST['departure_date'],
            $_POST['return_date'],
            $_POST['seats'],
            isset($_POST['active']) ? 1 : 0,
            $_POST['id']
        ]);
    }

    if ($action === 'delete_flight') {
        $stmt = $db->prepare("DELETE FROM flights WHERE id=?");
        $stmt->execute([$_POST['id']]);
    }

    if ($action === 'update_booking') {
        $stmt = $db->prepare("UPDATE bookings SET status=? WHERE id=?");
        $stmt->execute([$_POST['status'], $_POST['id']]);
    }

    header('Location: a_index.php');
    exit;
}

$flights = $db->query("SELECT flights.*, destinations.name AS destination_name, airlines.name AS airline FROM flights JOIN destinations ON flights.destination_id = destinations.id JOIN airlines ON flights.airline_id = airlines.id ORDER BY flights.departure_date ASC")->fetchAll();
$bookings = $db->query("SELECT bookings.*, users.first_name, users.last_name, destinations.name AS destination_name, flights.departure_date FROM bookings JOIN users ON bookings.user_id = users.id JOIN flights ON bookings.flight_id = flights.id JOIN destinations ON flights.destination_id = destinations.id ORDER BY bookings.created_at DESC")->fetchAll();

// voor de dropdowns in de formulieren
$destinations = $db->query("SELECT * FROM destinations ORDER BY name ASC")->fetchAll();
$airlines = $db->query("SELECT * FROM airlines ORDER BY name ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HighFlights — Admin</title>
    <link rel="stylesheet" href="style.css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kadwa:wght@400;700&display=swap');
    </style>
</head>

<body class="bg-[#F8FAF5]">

    <header>
        <?include('includes/header.php')?>
    </header>

    <div class="flex justify-center py-10">
        <div class="w-[90%] max-w-[1100px] flex flex-col gap-12">
            <!-- VLUCHTEN -->
            <section>
                <h2 class="text-xl font-bold mb-4 text-[#2e5435]">✈️ Vluchten</h2>
                <div class="flex flex-col gap-3">
                    <?
                    foreach ($flights as $f) {
                        $statusClass = 'status-cancelled';
                        if ($f['active']) {
                            $statusClass = 'status-confirmed';
                        }

                        $statusLabel = 'Inactief';
                        if ($f['active']) {
                            $statusLabel = 'Actief';
                        }

                        $activeChecked = '';
                        if ($f['active']) {
                            $activeChecked = 'checked';
                        }
                        ?>
                        <div class="bg-white border border-[#C8DFC9] rounded-xl overflow-hidden">

                            <div class="grid grid-cols-4 items-center gap-4 p-5">
                                <div>
                                    <div class="font-bold text-[#2e5435]"><?= htmlspecialchars($f['departure_name']) ?> →
                                        <?= htmlspecialchars($f['destination_name']) ?>
                                    </div>
                                    <div class="text-xs text-[#6B7F6D]"><?= htmlspecialchars($f['airline']) ?> ·
                                        <?= date('d-m-Y', strtotime($f['departure_date'])) ?>
                                    </div>
                                </div>
                                <div class="text-sm">€<?= $f['price'] ?> p.p.</div>
                                <div class="text-sm"><?= $f['seats'] ?> stoelen</div>
                                <div>
                                    <span class="booking-status <?= $statusClass ?>">
                                        <?= $statusLabel ?>
                                    </span>
                                </div>
                            </div>

                            <form method="post" class="border-t border-[#C8DFC9] bg-[#EEF4CD] p-5 flex flex-col gap-3">
                                <input type="hidden" name="action" value="edit_flight" />
                                <input type="hidden" name="id" value="<?= $f['id'] ?>" />

                                <div class="grid grid-cols-3 gap-3">
                                    <div class="form-group">
                                        <label>Vertrek</label>
                                        <input type="text" name="departure_name"
                                            value="<?= htmlspecialchars($f['departure_name']) ?>"
                                            class="bg-white text-center" />
                                    </div>
                                    <div class="form-group">
                                        <label>Bestemming</label>
                                        <select name="destination_id" class="bg-white text-center">
                                            <? foreach ($destinations as $d) { ?>
                                                <option value="<?= $d['id'] ?>" <?= $d['id'] == $f['destination_id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($d['name']) ?>
                                                </option>
                                            <? } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Luchtvaartmaatschappij</label>
                                        <select name="airline_id" class="bg-white text-center">
                                            <? foreach ($airlines as $a) { ?>
                                                <option value="<?= $a['id'] ?>" <?= $a['id'] == $f['airline_id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($a['name']) ?>
                                                </option>
                                            <? } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Prijs p.p. (€)</label>
                                        <input type="number" name="price" step="0.01" value="<?= $f['price'] ?>"
                                            class="bg-white text-center" />
                                    </div>
                                    <div class="form-group">
                                        <label>Vertrekdatum heen</label>
                                        <input type="datetime-local" name="departure_date"
                                            value="<?= date('Y-m-d\TH:i', strtotime($f['departure_date'])) ?>"
                                            class="bg-white text-center" />
                                    </div>
                                    <div class="form-group">
                                        <label>Retourdatum</label>
                                        <input type="datetime-local" name="return_date"
                                            value="<?= date('Y-m-d\TH:i', strtotime($f['return_date'])) ?>"
                                            class="bg-white text-center" />
                                    </div>
                                    <div class="form-group">
                                        <label>Stoelen</label>
                                        <input type="number" name="seats" value="<?= $f['seats'] ?>"
                                            class="bg-white text-center" />
                                    </div>
                                </div>

                                <div class="flex items-center gap-4">
                                    <label class="flex items-center gap-2 text-sm">
                                        <input type="checkbox" name="active" value="1" <?= $activeChecked ?> /> Actief
                                    </label>
                                    <button type="submit" class="btn btn-primary bg-green-500">💾 Opslaan</button>
                                </div>
                            </form>

                            <form method="post" class="border-t border-[#C8DFC9] p-3 flex justify-end">
                                <input type="hidden" name="action" value="delete_flight" />
                                <input type="hidden" name="id" value="<?= $f['id'] ?>" />
                                <button type="submit" class="cancel-btn bg-[#FF7459] rounded-xl p-2">Vlucht
                                    verwijderen</button>
                            </form>

                        </div>
                        <?
                    }
                    ?>

                </div>
            </section>


            <!-- VLUCHT TOEVOEGEN -->
            <section>
                <h2 class="text-xl font-bold mb-4 text-[#2e5435]">+ Vlucht toevoegen</h2>

                <div class="bg-white border border-[#C8DFC9] rounded-xl p-7 max-w-[680px]">
                    <form method="post" class="flex flex-col gap-3">
                        <input type="hidden" name="action" value="add_flight" />

                        <div class="grid grid-cols-2 gap-3">
                            <div class="form-group">
                                <label>Vertrek</label>
                                <input type="text" name="departure_name" placeholder="Amsterdam" required
                                    class="bg-gray-200 text-center" />
                            </div>
                            <div class="form-group">
                                <label>Bestemming</label>
                                <select name="destination_id" required class="bg-gray-200 text-center">
                                    <? foreach ($destinations as $d) { ?>
                                        <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
                                    <? } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Luchtvaartmaatschappij</label>
                                <select name="airline_id" required class="bg-gray-200 text-center">
                                    <? foreach ($airlines as $a) { ?>
                                        <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option>
                                    <? } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Vertrekdatum heen</label>
                                <input type="datetime-local" name="departure_date" required
                                    class="bg-gray-200 text-center" />
                            </div>
                            <div class="form-group">
                                <label>Returdatum</label>
                                <input type="datetime-local" name="return_date" required
                                    class="bg-gray-200 text-center" />
                            </div>
                            <div class="form-group">
                                <label>Prijs p.p. (€)</label>
                                <input type="number" name="price" step="0.01" min="0" placeholder="634.00" required
                                    class="bg-gray-200 text-center" />
                            </div>
                            <div class="form-group">
                                <label>Stoelen</label>
                                <input type="number" name="seats" min="1" value="180" class="bg-gray-200 text-center" />
                            </div>
                        </div>

                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="active" value="1" checked /> Meteen actief
                        </label>

                        <div>
                            <button type="submit" class="btn btn-primary bg-[#C8DFC9] rounded-xl p-2">+
                                Toevoegen</button>
                        </div>
                    </form>
                </div>
            </section>


            <!-- BOEKINGEN -->
            <section>
                <h2 class="text-xl font-bold mb-4 text-[#2e5435]">📋 Boekingen</h2>

                <div class="bg-white border border-[#C8DFC9] rounded-xl overflow-hidden">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-[#EEF4CD]">
                                <th class="p-3 text-left text-xs uppercase text-[#6B7F6D]">Referentie</th>
                                <th class="p-3 text-left text-xs uppercase text-[#6B7F6D]">Klant</th>
                                <th class="p-3 text-left text-xs uppercase text-[#6B7F6D]">Bestemming</th>
                                <th class="p-3 text-left text-xs uppercase text-[#6B7F6D]">Vertrek</th>
                                <th class="p-3 text-left text-xs uppercase text-[#6B7F6D]">Reizigers</th>
                                <th class="p-3 text-left text-xs uppercase text-[#6B7F6D]">Prijs</th>
                                <th class="p-3 text-left text-xs uppercase text-[#6B7F6D]">Status</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?
                            foreach ($bookings as $b) {
                                $pendingSelected = '';
                                if ($b['status'] === 'pending') {
                                    $pendingSelected = 'selected';
                                }

                                $confirmedSelected = '';
                                if ($b['status'] === 'confirmed') {
                                    $confirmedSelected = 'selected';
                                }

                                $cancelledSelected = '';
                                if ($b['status'] === 'cancelled') {
                                    $cancelledSelected = 'selected';
                                }
                                ?>
                                <tr class="border-t border-[#C8DFC9]">
                                    <td class="p-3 font-bold text-[#2e5435]"><?= htmlspecialchars($b['reference']) ?></td>
                                    <td class="p-3"><?= htmlspecialchars($b['first_name'] . ' ' . $b['last_name']) ?></td>
                                    <td class="p-3"><?= htmlspecialchars($b['destination_name']) ?></td>
                                    <td class="p-3"><?= date('d-m-Y', strtotime($b['departure_date'])) ?></td>
                                    <td class="p-3 text-center"><?= $b['travelers'] ?></td>
                                    <td class="p-3 font-bold">€<?= $b['total_price'] ?></td>
                                    <td class="p-3">
                                        <form method="post" class="flex gap-2">
                                            <input type="hidden" name="action" value="update_booking" />
                                            <input type="hidden" name="id" value="<?= $b['id'] ?>" />
                                            <select name="status"
                                                class="border border-[#C8DFC9] rounded-md px-2 py-1 text-xs font-[Kadwa] bg-[#F8FAF5]">
                                                <option value="pending" <?= $pendingSelected ?>>In
                                                    afwachting</option>
                                                <option value="confirmed" <?= $confirmedSelected ?>>Bevestigd</option>
                                                <option value="cancelled" <?= $cancelledSelected ?>>Geannuleerd</option>
                                            </select>
                                            <button type="submit" class="btn btn-primary px-3 py-1 text-xs">✓</button>
                                        </form>
                                    </td>
                                </tr>
                                <?
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </section>


        </div>
    </div>

</body>

</html>