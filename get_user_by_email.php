<?php
require_once 'config.php';
require_once 'logger.php';

log_action(__FILE__, ['params' => $_GET]);

$email = filter_var($_GET['email'] ?? '', FILTER_VALIDATE_EMAIL);
if (!$email) {
    http_response_code(400);
    echo json_encode(['error' => 'Valid email required']);
    exit;
}

$stmt = $pdo->prepare("SELECT id, name, email, created_at FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($user ?: ['error' => 'User not found']);
?>
