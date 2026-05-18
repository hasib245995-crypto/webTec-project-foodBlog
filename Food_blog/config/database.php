<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'food_blog');
define('DB_USER', 'root');
define('DB_PASS', '');

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {<?php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'food_blog');
define('DB_USER', 'root');
define('DB_PASS', '');

function getDB(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }

    return $pdo;
}
?>
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER, DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }
    return $pdo;
}
