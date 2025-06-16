<?php
// SECURE ACCESS CONFIGURATION
$ACCESS_KEY = "your_super_secret_key_here"; // CHANGE THIS!

if (!isset($_GET['key']) || $_GET['key'] !== $ACCESS_KEY) {
    http_response_code(403);
    echo json_encode(["error" => "Access denied"]);
    exit;
}

// DATABASE CONNECTION
$pdo = new PDO('mysql:host=localhost;dbname=your_database', 'your_user', 'your_password');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
