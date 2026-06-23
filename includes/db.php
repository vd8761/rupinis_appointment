<?php
// includes/db.php
require_once __DIR__ . '/env.php';

date_default_timezone_set('Asia/Singapore');
$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3306';
$db   = getenv('DB_NAME') ?: 'db_name';
$user = getenv('DB_USER') ?: 'user_name';
$pass = getenv('DB_PASS') ?: ''; // Default XAMPP password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // If the error is 1049 (Unknown database), provide a helpful message
    if ($e->getCode() == 1049) {
        die("Database '" . htmlspecialchars($db) . "' does not exist in MySQL/XAMPP. Please check the DB_NAME in your .env file!");
    }
    // Generic connection error
    die("Database connection failed: " . $e->getMessage());
}
?>
