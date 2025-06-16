<?php
include 'config.php';

$stmt = $pdo->prepare("SELECT * FROM orders WHERE DATE(created_at) = CURDATE()");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($orders);
?>
