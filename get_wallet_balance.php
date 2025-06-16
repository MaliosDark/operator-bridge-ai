<?php
require_once __DIR__ . '/common.php';


log_action(__FILE__, ['params'=>$_GET]);

$user_id = filter_var($_GET['user_id']??0, FILTER_VALIDATE_INT);
if (!$user_id) {
    http_response_code(400);
    exit(json_encode(['error'=>'user_id required']));
}

// Wallet balance is users.wallet_balance
$stmt = $pdo->prepare("SELECT wallet_balance FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$bal = $stmt->fetchColumn();

header('Content-Type: application/json');
echo json_encode([
  'user_id'=>$user_id,
  'wallet_balance'=>$bal
]);
