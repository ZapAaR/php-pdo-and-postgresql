<?php 

declare(strict_types=1);
session_start();

define('DB_HOST', 'localhost');
define('DB_NAME', 'web_basic');
define('PG_PORT', '5432');
define('DB_USER', 'postgres');
define('DB_PASS', 'Test');

function pdo(): PDO{
    static $pdo = null;
    if ($pdo === null){
        $dsn = "pgsql:host=" . DB_HOST . ";port=" . PG_PORT . ";dbname=" . DB_NAME . ";";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (\PDOException $e) {
            exit('DB Failed' . $e->getMessage());
        }
    }
    return $pdo;
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validate_csrf($token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
}

function e($str){
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');

}
