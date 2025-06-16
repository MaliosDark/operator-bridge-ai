<?php
include 'config.php';

$email = $_GET['email'] ?? '';
if (!$email) {
    echo json_encode(["error" => "Missing email"]);
    exit;
}

$stmt = $pdo->prepare("SELECT id, name, email, created_at FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($user ?: ["error" => "User not found"]);
?>
