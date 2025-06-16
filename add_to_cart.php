<?php
require_once 'config.php';
require_once 'auth.php';
require_once 'logger.php';

if (!isAuthorized()) {
    http_response_code(403);
    exit(json_encode(['error'=>'Unauthorized']));
}

$user_id = filter_var($_GET['user_id'] ?? 0, FILTER_VALIDATE_INT);
$item_id = filter_var($_GET['item_id'] ?? 0, FILTER_VALIDATE_INT);
$qty     = filter_var($_GET['quantity'] ?? 1, FILTER_VALIDATE_INT);

if (!$user_id || !$item_id) {
    http_response_code(400);
    exit(json_encode(['error'=>'user_id & item_id required']));
}

$stmt = $pdo->prepare("
  INSERT INTO carts (user_id, module_id, item_id, quantity, created_at, updated_at)
  VALUES (?, ?, ?, ?, NOW(), NOW())
");
// You may need to fetch module_id from items table first:
$mod = $pdo->query("SELECT module_id FROM items WHERE id=$item_id")->fetchColumn();
$success = $stmt->execute([$user_id, $mod, $item_id, $qty]);

header('Content-Type: application/json');
echo json_encode(['success'=>(bool)$success]);
