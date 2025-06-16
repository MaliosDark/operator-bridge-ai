<?php
require_once 'common.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse(405, 'method_not_allowed', 'POST required');
}
if (!isAuthorized()) {
    errorResponse(403, 'unauthorized', 'Invalid access key');
}

$data = json_decode(file_get_contents('php://input'), true);
log_action(__FILE__, ['body'=>$data]);

// required
foreach (['user_id','items','delivery_address','payment_method'] as $f) {
    if (empty($data[$f])) {
        errorResponse(400, 'invalid_parameters', "$f is required");
    }
}

$insertOrder = $pdo->prepare("
    INSERT INTO orders
      (user_id, delivery_address, payment_method, order_status, created_at, updated_at)
    VALUES (?, ?, ?, 'pending', NOW(), NOW())
");
$insertOrder->execute([
    $data['user_id'],
    $data['delivery_address'],
    $data['payment_method']
]);
$order_id = $pdo->lastInsertId();

$insertDetail = $pdo->prepare("
    INSERT INTO order_details
      (order_id, item_id, price, quantity, created_at, updated_at)
    VALUES (?, ?, ?, ?, NOW(), NOW())
");
foreach ($data['items'] as $it) {
    if (!isset($it['item_id'],$it['price'],$it['quantity'])) {
        errorResponse(400, 'invalid_parameters', 'Each item needs item_id, price & quantity');
    }
    $insertDetail->execute([
        $order_id,
        $it['item_id'],
        $it['price'],
        $it['quantity']
    ]);
}

successResponse(['order_id' => (int)$order_id]);
