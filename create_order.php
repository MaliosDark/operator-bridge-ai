<?php
require_once __DIR__ . '/common.php';

// 1) Method & auth
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse(405, 'method_not_allowed', 'POST required');
}
if (!isAuthorized()) {
    errorResponse(403, 'unauthorized', 'Invalid access key');
}

// 2) Body parse & validation
$data = json_decode(file_get_contents('php://input'), true);
log_action(__FILE__, ['body' => $data]);

foreach (['user_id','items','delivery_address','payment_method'] as $f) {
    if (empty($data[$f])) {
        errorResponse(400, 'invalid_parameters', "$f is required");
    }
}

// 3) Insert into orders
$insertOrder = $pdo->prepare("
    INSERT INTO orders
      (user_id, delivery_address, payment_method, order_status, created_at, updated_at)
    VALUES (?, ?, ?, 'pending', NOW(), NOW())
");
if (!$insertOrder->execute([
    $data['user_id'],
    $data['delivery_address'],
    $data['payment_method']
])) {
    errorResponse(500, 'db_error', 'Failed to create order');
}
$order_id = (int)$pdo->lastInsertId();

// 4) Insert each line item
$insertDetail = $pdo->prepare("
    INSERT INTO order_details
      (order_id, item_id, price, quantity, created_at, updated_at)
    VALUES (?, ?, ?, ?, NOW(), NOW())
");
foreach ($data['items'] as $it) {
    if (!isset($it['item_id'], $it['price'], $it['quantity'])) {
        errorResponse(400, 'invalid_parameters', 'Each item needs item_id, price & quantity');
    }
    if (!$insertDetail->execute([
        $order_id,
        $it['item_id'],
        $it['price'],
        $it['quantity']
    ])) {
        errorResponse(500, 'db_error', 'Failed to insert order details');
    }
}

// 5) All good
successResponse(['order_id' => $order_id]);
