<?php
require_once __DIR__ . '/common.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse(405, 'method_not_allowed', 'POST required');
}
if (!isAuthorized()) {
    errorResponse(403, 'unauthorized', 'Invalid access key');
}

$body    = json_decode(file_get_contents('php://input'), true);
$order_id= filter_var($body['order_id'] ?? 0, FILTER_VALIDATE_INT);
$reason  = trim($body['reason'] ?? '');

if (!$order_id || $reason === '') {
    errorResponse(400, 'invalid_parameters', 'order_id and reason are required');
}

$upd = $pdo->prepare("
    UPDATE orders
    SET order_status = 'canceled',
        cancellation_reason = ?,
        updated_at = NOW()
    WHERE id = ?
");
$ok  = $upd->execute([$reason, $order_id]);
log_action(__FILE__, ['order_id'=>$order_id,'reason'=>$reason,'result'=>$ok]);

if (!$ok) {
    errorResponse(500, 'db_error', 'Failed to cancel order');
}

successResponse();
