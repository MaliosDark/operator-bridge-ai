<?php
require_once 'config.php';
require_once 'logger.php';

log_action(__FILE__, ['action' => 'get_orders_today']);

// Fetch orders created today
$stmt = $pdo->prepare("SELECT id, user_id, total_amount, order_status, created_at FROM orders WHERE DATE(created_at) = CURDATE()");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($orders);
?>
