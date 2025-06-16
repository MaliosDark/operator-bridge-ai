<?php
require_once 'config.php';
require_once 'logger.php';

log_action(__FILE__, ['params' => $_GET]);

$order_id = filter_var($_GET['order_id'] ?? '', FILTER_VALIDATE_INT);
$status   = filter_var($_GET['status'] ?? '', FILTER_SANITIZE_STRING);

if (!$order_id || !$status) {
    http_response_code(400);
    echo json_encode(['error' => 'order_id and status required']);
    exit;
}

$stmt = $pdo->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
$success = $stmt->execute([$status, $order_id]);

header('Content-Type: application/json');
echo json_encode(['success' => (bool)$success]);
?>
