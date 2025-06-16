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

// Required fields
$fields = ['name','price','category_id','store_id','module_id'];
foreach($fields as $f) {
  if (!isset($data[$f])) {
    http_response_code(400);
    exit(json_encode(['error'=>"$f required"]));
  }
}

$stmt = $pdo->prepare("
  INSERT INTO items 
    (name, description, price, category_id, store_id, module_id, created_at, updated_at)
  VALUES 
    (?, ?, ?, ?, ?, ?, NOW(), NOW())
");
$success = $stmt->execute([
  $data['name'],
  $data['description'] ?? '',
  $data['price'],
  $data['category_id'],
  $data['store_id'],
  $data['module_id']
]);

header('Content-Type: application/json');
echo json_encode([
  'success'=>(bool)$success,
  'item_id'=>$pdo->lastInsertId()
]);
