<?
require_once __DIR__ . '/db/auth.php';
requireLogin();
$pdo = getDB();

$userid = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$userid]);
$user = $stmt->fetchAll();

var_dump($user['first_name'])
#TODO: User data weergeven via database output
?>