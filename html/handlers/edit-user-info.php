<?php
require_once dirname(__DIR__) . '/config.php';
require_once __DIR__ . '/db/auth.php';

requireLogin();

if (updateUser([
    'first_name' => $_POST['first_name'],
    'last_name'  => $_POST['last_name'],
    'email'      => $_POST['email'],
])) {
    header('Location: /account.php?success=1');
    exit;
}

header('Location: /account.php?success=0');
exit;
