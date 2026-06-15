<?php
// includes/db.php
$host = '127.0.0.1';
$db   = 'rupos_billingapp';
$user = 'root';
$pass = ''; // Default XAMPP password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
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
        die("Database 'rupos_billingapp' does not exist in XAMPP.");
    }
    // Generic connection error
    die("Database connection failed: " . $e->getMessage());
}
?>
