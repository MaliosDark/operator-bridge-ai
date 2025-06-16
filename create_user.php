<?php
require_once 'config.php';
require_once 'logger.php';

log_action(__FILE__, ['params' => $_GET]);

$name     = filter_var($_GET['name'] ?? '', FILTER_SANITIZE_STRING);
$email    = filter_var($_GET['email'] ?? '', FILTER_VALIDATE_EMAIL);
$password = $_GET['password'] ?? '';

if (!$name || !$email || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'name, email and password required']);
    exit;
}

$hashed = password_hash($password, PASSWORD_BCRYPT);
$stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
$success = $stmt->execute([$name, $email, $hashed]);

header('Content-Type: application/json');
echo json_encode([
    'success' => (bool)$success,
    'user_id' => $pdo->lastInsertId()
]);
?>
