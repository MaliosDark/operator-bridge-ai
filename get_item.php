<?php
require_once 'config.php';
require_once 'logger.php';

log_action(__FILE__, ['params'=>$_GET]);

$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit(json_encode(['error'=>'id required']));
}

$stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($item ?: ['error'=>'Item not found']);
