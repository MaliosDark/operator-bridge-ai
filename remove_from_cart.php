<?php
require_once __DIR__ . '/common.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse(405, 'method_not_allowed', 'POST required');
}
if (!isAuthorized()) {
    errorResponse(403, 'unauthorized', 'Invalid access key');
}

$body   = json_decode(file_get_contents('php://input'), true);
$cart_id= filter_var($body['cart_id'] ?? 0, FILTER_VALIDATE_INT);
if (!$cart_id) {
    errorResponse(400, 'invalid_parameters', 'cart_id is required');
}

$del = $pdo->prepare("DELETE FROM carts WHERE id = ?");
$ok  = $del->execute([$cart_id]);
log_action(__FILE__, ['cart_id'=>$cart_id,'result'=>$ok]);

if (!$ok) {
    errorResponse(500, 'db_error', 'Failed to remove from cart');
}

successResponse();
