<?php
include 'config.php';

$order_id = $_GET['order_id'] ?? '';
$status = $_GET['status'] ?? '';

if (!$order_id || !$status) {
    echo json_encode(["error" => "Missing order_id or status"]);
    exit;
}

$stmt = $pdo->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
$success = $stmt->execute([$status, $order_id]);

echo json_encode(["success" => $success]);
?>
