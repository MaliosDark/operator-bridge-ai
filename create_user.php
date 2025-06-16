<?php
require_once 'config.php';
require_once 'logger.php';

log_action(__FILE__, ['params'=>$_GET]);

// Expect first & last name separately
$f_name  = filter_var($_GET['f_name'] ?? '', FILTER_SANITIZE_STRING);
$l_name  = filter_var($_GET['l_name'] ?? '', FILTER_SANITIZE_STRING);
$phone   = filter_var($_GET['phone'] ?? '', FILTER_SANITIZE_STRING);
$email   = filter_var($_GET['email'] ?? '', FILTER_VALIDATE_EMAIL);
$password= $_GET['password'] ?? '';

if (!$f_name || !$l_name || !$email || !$password) {
    http_response_code(400);
    echo json_encode(['error'=>'f_name, l_name, email & password required']);
    exit;
}

$hashed = password_hash($password, PASSWORD_BCRYPT);
$stmt = $pdo->prepare("
  INSERT INTO users 
    (f_name, l_name, phone, email, password, created_at, updated_at) 
  VALUES 
    (?, ?, ?, ?, ?, NOW(), NOW())
");
$success = $stmt->execute([$f_name, $l_name, $phone, $email, $hashed]);

header('Content-Type: application/json');
echo json_encode([
  'success' => (bool)$success,
  'user_id' => $pdo->lastInsertId()
]);
