<?
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db/auth.php';
requireLogin();
$pdo = getDB();
$userid = $_SESSION['user_id'];

$usertstmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$usertstmt->execute([$userid]);
$user = $usertstmt->fetch();

$bookingstmt = $pdo->prepare('SELECT * FROM bookings WHERE user_id = ?');
$bookingstmt->execute([$userid]);
// Array
$bookings = $bookingstmt->fetchall();
?>


<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HighFlights — Your High (Quality) Journey</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kadwa:wght@400;700&display=swap');
    </style>
</head>

<body>
    <header><? include('includes/header.php') ?></header>
    <main>
        <div class="flex flex-wrap">
            <div class="flex">
                <!-- Profiel container -->
                <div class="bg-green-200 m-5 border-black border-2 p-5 rounded-xl">
                    <div class="flex flex-col items-center">
                        <div class="flex justify-center items-center max-w-[20vw] max-h-[20vw] min-w-[15vw] min-h-[15vw] text-9xl rounded-full bg-green-400 mb-5 text-center
">
                            <?= substr($user['first_name'], 0, 1) ?>
                        </div>
                        <form class="flex flex-col" method="POST" action="edit-user-info.php">
                            <label for="">First name</label>
                            <input name="first_name" class="text-2xl bg-green-100 px-10 py-2 rounded-xl my-2"
                                type="text" value="<?= $user['first_name'] ?>">
                            <label for="">Last name</label>
                            <input name="last_name" class="text-2xl bg-green-100 px-10 py-2 rounded-xl my-2" type="text"
                                value="<?= $user['last_name'] ?>">
                            <label for="">Email</label>
                            <input name="email" class="text-2xl bg-green-100 px-10 py-2 rounded-xl my-2" type="text"
                                value="<?= $user['email'] ?>">
                            <label for="">ID</label>
                            <div class="text-2xl bg-green-100 px-10 py-2 rounded-xl my-2"><?= $user['id'] ?></div>
                            <label for="">Role</label>
                            <div class="text-2xl bg-green-100 px-10 py-2 rounded-xl my-2"><? if ($user['role'] === "customer")
                                echo "traveler";
                            else
                                echo $user['role']; ?>
                            </div>
                            <input class="bg-green-400 m-5 p-3 rounded-xl" type="submit" value="Save Changes">
                            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                                <div class="bg-green-500 text-white p-3 rounded-xl mb-4">
                                    User information updated successfully.
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                    <div class="flex flex-col"></div>
                </div>
            </div>

            <!-- Booking container -->
            <div class="border-2 p-5 m-5 rounded-xl">
                <div class="text-5xl mb-5">Bookings:</div>
                <div class="flex rounded-xl w-[60vw] flex-wrap">
                    <?php foreach ($bookings as $b): ?>

                        <?php
                        $bgClass =
                            $b['status'] === 'confirmed' ? 'bg-green-200' :
                            ($b['status'] === 'pending' ? 'bg-yellow-200' : 'bg-red-200');
                        ?>

                        <div class="<?= $bgClass ?> m-2 w-[14vw] h-[15vw] rounded-xl p-3">
                            <div class="text-xl">Booking id: <span class="text-base"><?= $b['id'] ?></span></div>
                            <div class="text-xl">Flight reference: <span class="text-base"><?= $b['reference'] ?></span>
                            </div>
                            <div class="text-xl">Travelers: <span class="text-base"><?= $b['travelers'] ?></span></div>
                            <div class="text-xl">Price total: <span class="text-base"><?= $b['total_price'] ?></span></div>
                            <div class="text-xl">Status: <span class="text-base"><?= $b['status'] ?></span></div>
                            <div class="text-xl">Booked at: <span class="text-base"><?= $b['created_at'] ?></span></div>
                            <div class="flex justify-around items-center mt-2"><a
                                    href="/handlers/flight-detail.php?id=<?= $b['flight_id'] ?>" class="px-3 py-1">Meer info</a><a
                                    href="">Annuleer</a></div>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
<!-- var_dump($user['first_name']); -->