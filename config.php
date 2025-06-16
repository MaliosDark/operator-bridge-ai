<?php
// ------------------------------
// SECURE ACCESS CONFIGURATION
// ------------------------------
define('ACCESS_KEY', 'your_super_secret_key_here'); // CHANGE THIS TO A STRONG SECRET
define('DB_HOST',    'localhost');                  // Your DB host
define('DB_NAME',    'your_database');              // Your DB name
define('DB_USER',    'your_user');                  // Your DB user
define('DB_PASS',    'your_password');              // Your DB password

// Force HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    http_response_code(400);
    echo json_encode(['error' => 'HTTPS is required']);
    exit;
}

// Validate access key
if (!isset($_GET['key']) || $_GET['key'] !== ACCESS_KEY) {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied']);
    exit;
}

// Database connection
try {
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}
?>
