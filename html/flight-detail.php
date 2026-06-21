<? require_once __DIR__ . '/db/auth.php';
$pdo = getDB();
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare('SELECT * FROM flights WHERE id = ? AND active = 1');
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
  <title>HighFlights — Your High (Quality) Journey</title>
  <link rel="stylesheet" href="style.css" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>@import url('https://fonts.googleapis.com/css2?family=Kadwa:wght@400;700&display=swap');</style>
</head>
<body class="bg-[#f8f5ef]"></body>


<body>
    
</body>
</html>