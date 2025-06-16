<?php
include 'config.php';

$name = $_GET['name'] ?? '';
$email = $_GET['email'] ?? '';
$password = $_GET['password'] ?? '';

if (!$name || !$email || !$password) {
    echo json_encode(["error" => "Missing fields"]);
    exit;
}

$hashed_password = password_hash($password, PASSWORD_BCRYPT);
$stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
$success = $stmt->execute([$name, $email, $hashed_password]);

echo json_encode(["success" => $success, "user_id" => $pdo->lastInsertId()]);
?>
