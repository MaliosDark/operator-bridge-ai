<?php
require_once 'config.php';
require_once 'auth.php';
require_once 'logger.php';

if (!isAuthorized()) {
    http_response_code(403);
    exit(json_encode(['error'=>'Unauthorized']));
}

$cart_id = filter_var($_GET['cart_id'] ?? 0, FILTER_VALIDATE_INT);
if (!$cart_id) {
    http_response_code(400);
    exit(json_encode(['error'=>'cart_id required']));
}

$stmt = $pdo->prepare("DELETE FROM carts WHERE id = ?");
$success = $stmt->execute([$cart_id]);

header('Content-Type: application/json');
echo json_encode(['success'=>(bool)$success]);
