<?php
require_once 'db/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$pdo = getDB();
$msg = '';
$tab = $_GET['tab'] ?? 'flights';

// ════════════════════════════════════════════════════════════
//  ACTIONS
// ════════════════════════════════════════════════════════════

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ── Vlucht opslaan (create/update) ──────────────────────
    if (isset($_POST['save_flight'])) {
        $fields = [
            $_POST['destination_id'],
            $_POST['departure_airport'],
            $_POST['departure_datetime'],
            $_POST['arrival_datetime'],
            $_POST['return_departure_datetime'],
            $_POST['return_arrival_datetime'],
            (int)$_POST['stops'],
            $_POST['airline'],
            (float)$_POST['price_per_person'],
            (int)$_POST['available_seats'],
            isset($_POST['is_active']) ? 1 : 0,
        ];
        if (!empty($_POST['id'])) {
            $pdo->prepare('UPDATE flights SET destination_id=?,departure_airport=?,departure_datetime=?,arrival_datetime=?,return_departure_datetime=?,return_arrival_datetime=?,stops=?,airline=?,price_per_person=?,available_seats=?,is_active=? WHERE id=?')
                ->execute([...$fields, (int)$_POST['id']]);
            $msg = 'Vlucht bijgewerkt.';
        } else {
            $pdo->prepare('INSERT INTO flights(destination_id,departure_airport,departure_datetime,arrival_datetime,return_departure_datetime,return_arrival_datetime,stops,airline,price_per_person,available_seats,is_active) VALUES(?,?,?,?,?,?,?,?,?,?,?)')
                ->execute($fields);
            $msg = 'Vlucht aangemaakt.';
        }
        $tab = 'flights';
    }

    // ── Vlucht verwijderen ───────────────────────────────────
    if (isset($_POST['delete_flight'])) {
        $pdo->prepare('DELETE FROM flights WHERE id=?')->execute([(int)$_POST['id']]);
        $msg = 'Vlucht verwijderd.';
        $tab = 'flights';
    }

    // ── Boeking status ───────────────────────────────────────
    if (isset($_POST['update_status'])) {
        $allowed = ['pending','confirmed','cancelled'];
        $status  = in_array($_POST['status'], $allowed) ? $_POST['status'] : 'pending';
        $pdo->prepare('UPDATE bookings SET status=? WHERE id=?')->execute([$status, (int)$_POST['id']]);
        $msg = 'Status bijgewerkt.';
        $tab = 'bookings';
    }

    // ── Review valideren / verwijderen ───────────────────────
    if (isset($_POST['validate_review'])) {
        $pdo->prepare('UPDATE reviews SET is_validated=1 WHERE id=?')->execute([(int)$_POST['id']]);
        $msg = 'Review goedgekeurd.';
        $tab = 'reviews';
    }
    if (isset($_POST['delete_review'])) {
        $pdo->prepare('DELETE FROM reviews WHERE id=?')->execute([(int)$_POST['id']]);
        $msg = 'Review verwijderd.';
        $tab = 'reviews';
    }
}

// ── Edit vlucht ophalen ──────────────────────────────────────
$editFlight = null;
if (isset($_GET['edit_flight'])) {
    $s = $pdo->prepare('SELECT * FROM flights WHERE id=?');
    $s->execute([(int)$_GET['edit_flight']]);
    $editFlight = $s->fetch();
    $tab = 'flights';
}

// ── Data ophalen ─────────────────────────────────────────────
$flights = $pdo->query('SELECT f.*,d.name AS destination_name FROM flights f JOIN destinations d ON d.id=f.destination_id ORDER BY f.departure_datetime DESC')->fetchAll();
$destinations = $pdo->query('SELECT id,name FROM destinations WHERE is_active=1 ORDER BY name')->fetchAll();
$bookings = $pdo->query('SELECT b.*,u.first_name,u.last_name,d.name AS destination FROM bookings b JOIN users u ON u.id=b.user_id JOIN flights f ON f.id=b.flight_id JOIN destinations d ON d.id=f.destination_id ORDER BY b.created_at DESC')->fetchAll();
$reviews  = $pdo->query('SELECT r.*,u.first_name,u.last_name,d.name AS destination FROM reviews r JOIN users u ON u.id=r.user_id JOIN destinations d ON d.id=r.destination_id ORDER BY r.is_validated ASC, r.created_at DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — HighFlights</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="style.css">
    <style>@import url('https://fonts.googleapis.com/css2?family=Kadwa:wght@400;700&display=swap');</style>
</head>
<body>
<?php include('includes/header.php'); ?>

<div class="flex justify-center mt-10 pb-16">
    <div style="width:90%;max-width:1200px;">

        <!-- Flash message -->
        <?php if ($msg): ?>
        <div class="alert alert-info" style="margin-bottom:20px;"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <!-- Tabs -->
        <div style="display:flex;gap:8px;margin-bottom:24px;border-bottom:2px solid var(--border);padding-bottom:0;">
            <?php foreach(['flights'=>'✈️ Vluchten','bookings'=>'📋 Boekingen','reviews'=>'⭐ Reviews'] as $t => $label): ?>
            <a href="?tab=<?= $t ?>" style="padding:10px 20px;font-weight:700;font-size:0.9rem;border-radius:8px 8px 0 0;text-decoration:none;background:<?= $tab===$t?'var(--white)':'transparent' ?>;color:<?= $tab===$t?'var(--dark-green)':'var(--muted)' ?>;border:<?= $tab===$t?'1.5px solid var(--border)':'1.5px solid transparent' ?>;border-bottom:<?= $tab===$t?'2px solid var(--white)':'none' ?>;margin-bottom:-2px;">
                <?= $label ?>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- ════ TAB: VLUCHTEN ════ -->
        <?php if ($tab === 'flights'): ?>
        <div style="display:grid;grid-template-columns:1fr 400px;gap:28px;align-items:start;">

            <!-- Tabel -->
            <div style="background:var(--white);border:1.5px solid var(--border);border-radius:var(--radius);overflow:hidden;">
                <table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
                    <thead>
                        <tr style="background:var(--accent);">
                            <th style="padding:10px 14px;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Bestemming</th>
                            <th style="padding:10px 14px;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Vertrek</th>
                            <th style="padding:10px 14px;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Prijs</th>
                            <th style="padding:10px 14px;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Actief</th>
                            <th style="padding:10px 14px;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($flights as $f): ?>
                        <tr style="border-bottom:1px solid var(--border);">
                            <td style="padding:10px 14px;font-weight:700;color:var(--dark-green);"><?= htmlspecialchars($f['destination_name']) ?></td>
                            <td style="padding:10px 14px;"><?= date('d-m-Y', strtotime($f['departure_datetime'])) ?></td>
                            <td style="padding:10px 14px;">€<?= number_format($f['price_per_person'],2,',','.') ?></td>
                            <td style="padding:10px 14px;"><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:<?= $f['is_active']?'#2E5435':'#C0392B' ?>;"></span></td>
                            <td style="padding:10px 14px;display:flex;gap:8px;">
                                <a href="?tab=flights&edit_flight=<?= $f['id'] ?>" class="btn btn-outline" style="padding:5px 12px;font-size:0.78rem;">Bewerk</a>
                                <form method="post" onsubmit="return confirm('Verwijderen?');">
                                    <input type="hidden" name="id" value="<?= $f['id'] ?>">
                                    <button name="delete_flight" class="cancel-btn" style="padding:5px 12px;font-size:0.78rem;">Verwijder</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($flights)): ?>
                        <tr><td colspan="5" style="padding:20px;text-align:center;color:var(--muted);">Geen vluchten.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Formulier -->
            <div style="background:var(--white);border:1.5px solid var(--border);border-radius:var(--radius);padding:24px;position:sticky;top:88px;">
                <h2 style="font-size:1rem;font-weight:700;color:var(--dark-green);margin-bottom:18px;"><?= $editFlight ? 'Bewerken' : 'Nieuwe vlucht' ?></h2>
                <form method="post">
                    <?php if ($editFlight): ?><input type="hidden" name="id" value="<?= $editFlight['id'] ?>"><?php endif; ?>

                    <div class="form-group" style="margin-bottom:12px;">
                        <label>Bestemming</label>
                        <select name="destination_id" required>
                            <?php foreach ($destinations as $d): ?>
                            <option value="<?= $d['id'] ?>" <?= ($editFlight && $editFlight['destination_id']==$d['id'])?'selected':'' ?>><?= htmlspecialchars($d['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom:12px;">
                        <label>Luchtvaartmaatschappij</label>
                        <input type="text" name="airline" required placeholder="GreenAir" value="<?= htmlspecialchars($editFlight['airline'] ?? '') ?>">
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;">
                        <div class="form-group">
                            <label>Van (IATA)</label>
                            <input type="text" name="departure_airport" maxlength="3" placeholder="AMS" required value="<?= htmlspecialchars($editFlight['departure_airport'] ?? 'AMS') ?>">
                        </div>
                        <div class="form-group">
                            <label>Stops</label>
                            <input type="number" name="stops" min="0" max="5" value="<?= $editFlight['stops'] ?? 1 ?>">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:12px;">
                        <label>Vertrek heen</label>
                        <input type="datetime-local" name="departure_datetime" required value="<?= $editFlight ? date('Y-m-d\TH:i', strtotime($editFlight['departure_datetime'])) : '' ?>">
                    </div>
                    <div class="form-group" style="margin-bottom:12px;">
                        <label>Aankomst heen</label>
                        <input type="datetime-local" name="arrival_datetime" required value="<?= $editFlight ? date('Y-m-d\TH:i', strtotime($editFlight['arrival_datetime'])) : '' ?>">
                    </div>
                    <div class="form-group" style="margin-bottom:12px;">
                        <label>Vertrek terug</label>
                        <input type="datetime-local" name="return_departure_datetime" required value="<?= $editFlight ? date('Y-m-d\TH:i', strtotime($editFlight['return_departure_datetime'])) : '' ?>">
                    </div>
                    <div class="form-group" style="margin-bottom:12px;">
                        <label>Aankomst terug</label>
                        <input type="datetime-local" name="return_arrival_datetime" required value="<?= $editFlight ? date('Y-m-d\TH:i', strtotime($editFlight['return_arrival_datetime'])) : '' ?>">
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;">
                        <div class="form-group">
                            <label>Prijs p.p. (€)</label>
                            <input type="number" name="price_per_person" step="0.01" min="0" required value="<?= htmlspecialchars($editFlight['price_per_person'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Stoelen</label>
                            <input type="number" name="available_seats" min="1" value="<?= $editFlight['available_seats'] ?? 180 ?>">
                        </div>
                    </div>

                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
                        <input type="checkbox" name="is_active" id="is_active" value="1" <?= (!$editFlight || $editFlight['is_active'])?'checked':'' ?>>
                        <label for="is_active" style="margin:0;font-size:0.88rem;">Actief zichtbaar</label>
                    </div>

                    <button name="save_flight" class="btn btn-primary" style="width:100%;justify-content:center;">
                        <?= $editFlight ? '💾 Opslaan' : '+ Aanmaken' ?>
                    </button>
                    <?php if ($editFlight): ?>
                    <a href="?tab=flights" class="btn btn-ghost" style="width:100%;justify-content:center;margin-top:8px;">Annuleer</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- ════ TAB: BOEKINGEN ════ -->
        <?php elseif ($tab === 'bookings'): ?>
        <div style="background:var(--white);border:1.5px solid var(--border);border-radius:var(--radius);overflow:hidden;">
            <table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
                <thead>
                    <tr style="background:var(--accent);">
                        <th style="padding:10px 14px;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Referentie</th>
                        <th style="padding:10px 14px;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Klant</th>
                        <th style="padding:10px 14px;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Bestemming</th>
                        <th style="padding:10px 14px;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Prijs</th>
                        <th style="padding:10px 14px;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Status</th>
                        <th style="padding:10px 14px;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Bijwerken</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $b): ?>
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:10px 14px;font-weight:700;color:var(--dark-green);"><?= htmlspecialchars($b['booking_reference']) ?></td>
                        <td style="padding:10px 14px;"><?= htmlspecialchars($b['first_name'].' '.$b['last_name']) ?></td>
                        <td style="padding:10px 14px;"><?= htmlspecialchars($b['destination']) ?></td>
                        <td style="padding:10px 14px;font-weight:700;">€<?= number_format($b['total_price'],2,',','.') ?></td>
                        <td style="padding:10px 14px;">
                            <?php $cls=['confirmed'=>'status-confirmed','pending'=>'status-pending','cancelled'=>'status-cancelled']; $lbl=['confirmed'=>'Bevestigd','pending'=>'In afwachting','cancelled'=>'Geannuleerd']; ?>
                            <span class="booking-status <?= $cls[$b['status']]??'' ?>"><?= $lbl[$b['status']]??$b['status'] ?></span>
                        </td>
                        <td style="padding:10px 14px;">
                            <form method="post" style="display:flex;gap:6px;">
                                <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                <select name="status" style="padding:4px 8px;border:1.5px solid var(--border);border-radius:6px;font-size:0.82rem;font-family:inherit;background:var(--off-white);">
                                    <option value="pending"   <?= $b['status']==='pending'  ?'selected':'' ?>>In afwachting</option>
                                    <option value="confirmed" <?= $b['status']==='confirmed'?'selected':'' ?>>Bevestigd</option>
                                    <option value="cancelled" <?= $b['status']==='cancelled'?'selected':'' ?>>Geannuleerd</option>
                                </select>
                                <button name="update_status" class="btn btn-primary" style="padding:5px 12px;font-size:0.78rem;">Sla op</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($bookings)): ?><tr><td colspan="6" style="padding:20px;text-align:center;color:var(--muted);">Geen boekingen.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- ════ TAB: REVIEWS ════ -->
        <?php elseif ($tab === 'reviews'): ?>
        <div style="display:flex;flex-direction:column;gap:14px;">
            <?php foreach ($reviews as $r): ?>
            <div style="background:var(--white);border:1.5px solid var(--border);border-radius:var(--radius);padding:20px 24px;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;">
                    <div>
                        <span style="font-weight:700;color:var(--dark-green);"><?= htmlspecialchars($r['first_name'].' '.$r['last_name']) ?></span>
                        <span style="color:var(--muted);font-size:0.85rem;"> over <?= htmlspecialchars($r['destination']) ?></span>
                        <div style="color:#F4A623;"><?= str_repeat('★',$r['rating']).str_repeat('☆',5-$r['rating']) ?></div>
                    </div>
                    <span style="padding:4px 12px;border-radius:50px;font-size:0.78rem;font-weight:700;background:<?= $r['is_validated']?'var(--logo-bg)':'#FFF9C4' ?>;color:<?= $r['is_validated']?'var(--dark-green)':'#7B6A00' ?>;">
                        <?= $r['is_validated']?'✅ Goedgekeurd':'⏳ In afwachting' ?>
                    </span>
                </div>
                <p style="color:var(--text);font-size:0.9rem;line-height:1.7;margin-bottom:14px;"><?= nl2br(htmlspecialchars($r['review_text'])) ?></p>
                <div style="display:flex;gap:8px;">
                    <?php if(!$r['is_validated']): ?>
                    <form method="post">
                        <input type="hidden" name="id" value="<?= $r['id'] ?>">
                        <button name="validate_review" class="btn btn-primary" style="padding:7px 16px;font-size:0.82rem;">✅ Goedkeuren</button>
                    </form>
                    <?php endif; ?>
                    <form method="post" onsubmit="return confirm('Review verwijderen?');">
                        <input type="hidden" name="id" value="<?= $r['id'] ?>">
                        <button name="delete_review" class="cancel-btn" style="padding:7px 16px;font-size:0.82rem;">✕ Verwijder</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if(empty($reviews)): ?><div style="text-align:center;padding:40px;color:var(--muted);">Geen reviews.</div><?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</div>
</body>
</html>