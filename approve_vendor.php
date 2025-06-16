<?php
require_once 'config.php';
require_once 'logger.php';

log_action(__FILE__, ['params'=>$_GET]);

$vendor_id = filter_var($_GET['vendor_id'] ?? '', FILTER_VALIDATE_INT);
if (!$vendor_id) {
    http_response_code(400);
    echo json_encode(['error'=>'vendor_id required']);
    exit;
}

$stmt = $pdo->prepare("
  UPDATE stores 
  SET status = 1 
  WHERE id = ?
");
$success = $stmt->execute([$vendor_id]);

header('Content-Type: application/json');
echo json_encode(['success'=>(bool)$success]);
