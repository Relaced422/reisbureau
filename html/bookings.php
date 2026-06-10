<?php
require_once '../includes/auth.php';
requireAdmin();

$pdo = getDB();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $allowed = ['pending', 'confirmed', 'cancelled'];
    $status  = in_array($_POST['status'], $allowed) ? $_POST['status'] : 'pending';
    $pdo->prepare('UPDATE bookings SET status = ? WHERE id = ?')->execute([$status, (int)$_POST['id']]);
    $msg = 'Status bijgewerkt.';
}

$filterStatus = $_GET['status'] ?? '';
$where  = $filterStatus ? 'WHERE status = ?' : '';
$params = $filterStatus ? [$filterStatus] : [];

$stmt = $pdo->prepare("SELECT * FROM bookings $where ORDER BY created_at DESC");
$stmt->execute($params);
$bookings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boekingen — Admin HighFlights</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="../style.css">
    <style>@import url('https://fonts.googleapis.com/css2?family=Kadwa:wght@400;700&display=swap');</style>
</head>
<body>
<?php include('../includes/header.php'); ?>

<div class="flex min-h-screen" style="background:var(--off-white);">
    <?php include('_sidebar.php'); ?>

    <main class="flex-1 p-8">
        <h1 class="text-2xl font-bold mb-6" style="color:var(--dark-green);">Boekingen</h1>

        <?php if ($msg): ?>
        <div class="alert alert-info" style="margin-bottom:20px;"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <div style="display:flex;gap:8px;margin-bottom:20px;">
            <?php foreach (['' => 'Alle', 'confirmed' => 'Bevestigd', 'pending' => 'In afwachting', 'cancelled' => 'Geannuleerd'] as $val => $label): ?>
            <a href="?status=<?= $val ?>" class="sort-btn <?= $filterStatus===$val?'active':'' ?>"><?= $label ?></a>
            <?php endforeach; ?>
        </div>

        <div style="background:var(--white);border:1.5px solid var(--border);border-radius:var(--radius);overflow:hidden;">
            <table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
                <thead>
                    <tr style="background:var(--accent);">
                        <th style="padding:10px 14px;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Referentie</th>
                        <th style="padding:10px 14px;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Klant</th>
                        <th style="padding:10px 14px;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Bestemming</th>
                        <th style="padding:10px 14px;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Vertrek</th>
                        <th style="padding:10px 14px;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Reizigers</th>
                        <th style="padding:10px 14px;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Prijs</th>
                        <th style="padding:10px 14px;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Status</th>
                        <th style="padding:10px 14px;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Bijwerken</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $b): ?>
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:10px 14px;font-weight:700;color:var(--dark-green);"><?= htmlspecialchars($b['reference']) ?></td>
                        <td style="padding:10px 14px;"><?= htmlspecialchars($b['user_name']) ?></td>
                        <td style="padding:10px 14px;"><?= htmlspecialchars($b['destination_name']) ?></td>
                        <td style="padding:10px 14px;"><?= date('d-m-Y', strtotime($b['departure_date'])) ?></td>
                        <td style="padding:10px 14px;text-align:center;"><?= $b['travelers'] ?></td>
                        <td style="padding:10px 14px;font-weight:700;">€<?= number_format($b['total_price'],2,',','.') ?></td>
                        <td style="padding:10px 14px;">
                            <?php
                            $cls = ['confirmed'=>'status-confirmed','pending'=>'status-pending','cancelled'=>'status-cancelled'];
                            $lbl = ['confirmed'=>'Bevestigd','pending'=>'In afwachting','cancelled'=>'Geannuleerd'];
                            ?>
                            <span class="booking-status <?= $cls[$b['status']] ?? '' ?>"><?= $lbl[$b['status']] ?? $b['status'] ?></span>
                        </td>
                        <td style="padding:10px 14px;">
                            <form method="post" style="display:flex;gap:6px;align-items:center;">
                                <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                <select name="status" style="padding:4px 8px;border:1.5px solid var(--border);border-radius:6px;font-size:0.82rem;background:var(--off-white);">
                                    <option value="pending"   <?= $b['status']==='pending'   ?'selected':'' ?>>In afwachting</option>
                                    <option value="confirmed" <?= $b['status']==='confirmed' ?'selected':'' ?>>Bevestigd</option>
                                    <option value="cancelled" <?= $b['status']==='cancelled' ?'selected':'' ?>>Geannuleerd</option>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-primary" style="padding:5px 12px;font-size:0.78rem;">Sla op</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($bookings)): ?>
                    <tr><td colspan="8" style="padding:20px;text-align:center;color:var(--muted);">Geen boekingen gevonden.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>