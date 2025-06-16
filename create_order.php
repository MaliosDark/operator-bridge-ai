<?php
require_once 'config.php';
require_once 'auth.php';
require_once 'logger.php';

if (!isAuthorized()) {
    http_response_code(403);
    exit(json_encode(['error'=>'Unauthorized']));
}

$data = json_decode(file_get_contents('php://input'), true);
log_action(__FILE__, ['body'=>$data]);

// Minimal required fields
$req = ['user_id','items','delivery_address','payment_method'];
foreach ($req as $f) {
    if (empty($data[$f])) {
        http_response_code(400);
        exit(json_encode(['error'=>"$f required"]));
    }
}

// 1) Insert basic order
$stmt = $pdo->prepare("
  INSERT INTO orders 
    (user_id, delivery_address, payment_method, order_status, created_at, updated_at)
  VALUES (?, ?, ?, 'pending', NOW(), NOW())
");
$stmt->execute([
  $data['user_id'],
  $data['delivery_address'],
  $data['payment_method']
]);
$order_id = $pdo->lastInsertId();

// 2) Insert order_details for each item
$detailStmt = $pdo->prepare("
  INSERT INTO order_details 
    (order_id, item_id, price, quantity, created_at, updated_at)
  VALUES (?, ?, ?, ?, NOW(), NOW())
");
foreach ($data['items'] as $it) {
    $detailStmt->execute([
      $order_id,
      $it['item_id'],
      $it['price'],
      $it['quantity']
    ]);
}

header('Content-Type: application/json');
echo json_encode(['success'=>true,'order_id'=>$order_id]);
