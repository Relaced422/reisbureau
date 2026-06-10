<?php
require_once __DIR__ . '/config.php';

function getDB(): PDO {
    static $pdo = null; // Onthoudt de connectie tussen meerdere aanroepen

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST
             . ';dbname='   . DB_NAME
             . ';charset='  . DB_CHARSET;

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Gooi een exception bij fouten
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Geef rijen terug als associatieve array
            PDO::ATTR_EMULATE_PREPARES   => false,                   // Gebruik echte prepared statements
        ];

        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    }

    return $pdo;
}