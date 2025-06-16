<?php
require_once 'config.php';
require_once 'auth.php';
require_once 'logger.php';

if (!isAuthorized()) {
    http_response_code(403);
    exit(json_encode(['error'=>'Unauthorized']));
}

$order_id = filter_var($_GET['order_id']??0, FILTER_VALIDATE_INT);
$reason   = filter_var($_GET['reason']??'', FILTER_SANITIZE_STRING);
if (!$order_id || !$reason) {
    http_response_code(400);
    exit(json_encode(['error'=>'order_id & reason required']));
}

$stmt = $pdo->prepare("
  UPDATE orders 
  SET order_status = 'canceled', cancellation_reason = ?, updated_at = NOW()
  WHERE id = ?
");
$success = $stmt->execute([$reason,$order_id]);

header('Content-Type: application/json');
echo json_encode(['success'=>(bool)$success]);
