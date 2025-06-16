<?php
require_once 'config.php';
require_once 'auth.php';
require_once 'logger.php';

if (!isAuthorized()) {
    http_response_code(403);
    echo json_encode(['error'=>'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
log_action(__FILE__, ['body'=>$data]);

$order_id = filter_var($data['order_id'] ?? 0, FILTER_VALIDATE_INT);
$status   = filter_var($data['status'] ?? '', FILTER_SANITIZE_STRING);

if (!$order_id || !$status) {
    http_response_code(400);
    echo json_encode(['error'=>'order_id and status required']);
    exit;
}

$stmt = $pdo->prepare("
  UPDATE orders 
  SET order_status = :status 
  WHERE id = :id
");
$stmt->execute(['status'=>$status, 'id'=>$order_id]);

header('Content-Type: application/json');
echo json_encode(['success'=>true]);
