<?php
require_once 'common.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse(405, 'method_not_allowed', 'POST required');
}
if (!isAuthorized()) {
    errorResponse(403, 'unauthorized', 'Invalid access key');
}

$body = json_decode(file_get_contents('php://input'), true);
$user_id = filter_var($body['user_id'] ?? 0, FILTER_VALIDATE_INT);
$item_id = filter_var($body['item_id'] ?? 0, FILTER_VALIDATE_INT);
$quantity= filter_var($body['quantity'] ?? 1, FILTER_VALIDATE_INT);

if (!$user_id || !$item_id) {
    errorResponse(400, 'invalid_parameters', 'user_id and item_id are required');
}

// fetch module_id from items table
$stmt = $pdo->prepare("SELECT module_id FROM items WHERE id = ?");
$stmt->execute([$item_id]);
$module_id = $stmt->fetchColumn();
if (!$module_id) {
    errorResponse(404, 'item_not_found', 'Item does not exist');
}

$ins = $pdo->prepare("
    INSERT INTO carts (user_id, module_id, item_id, quantity, created_at, updated_at)
    VALUES (?, ?, ?, ?, NOW(), NOW())
");
$ok = $ins->execute([$user_id, $module_id, $item_id, $quantity]);
log_action(__FILE__, ['user_id'=>$user_id,'item_id'=>$item_id,'quantity'=>$quantity,'result'=>$ok]);

if (!$ok) {
    errorResponse(500, 'db_error', 'Failed to add to cart');
}

successResponse();
