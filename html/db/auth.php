<?php

require_once __DIR__ . '/db.php';

// Start sessie als dat nog niet gebeurd is (AI suggestie)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// (AI suggestie over dat het beter een functie kan zijn)
function login(string $email, string $password): bool { 
    $pdo  = getDB();
    // Nog geen tijd gehad voor password hashing
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? AND password = ? LIMIT 1');
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['first_name'];
        $_SESSION['user_role'] = $user['role'];
        return true;
    }

    return false;
}

function logout(): void {
    session_destroy();
    header('Location: /login.php');
    // Directe exit voor mogelijke script errors
    exit;
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function isAdmin(): bool {
    return ($_SESSION['user_role'] === 'admin');
    // Dubbele true
}

// User handler - terug sturen
function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: /login.php');
        exit;
    }
}

// User handler - terug sturen
function requireAdmin(): void {
    if (!isAdmin()) {
        header('Location: /index.php');
        exit;
    }
}

function register(array $data): bool {
    $pdo = getDB();

    $check = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $check->execute([$data['email']]);
    if ($check->fetch()) {
        return false;
    }

    $stmt = $pdo->prepare('INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)');

    return $stmt->execute([
        $data['first_name'],
        $data['last_name'],
        $data['email'],
        $data['password'],
    ]);
}