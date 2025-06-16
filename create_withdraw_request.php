<?php
require_once __DIR__ . '/common.php';
require_once 'auth.php';


if (!isAuthorized()) {
    http_response_code(403);
    exit(json_encode(['error'=>'Unauthorized']));
}

$data = json_decode(file_get_contents('php://input'), true);
log_action(__FILE__, ['body'=>$data]);

$req = ['vendor_id','amount','withdrawal_method_id'];
foreach($req as $f){
  if(!isset($data[$f])){
    http_response_code(400);
    exit(json_encode(['error'=>"$f required"]));
  }
}

$stmt = $pdo->prepare("
  INSERT INTO withdraw_requests
    (vendor_id, amount, withdrawal_method_id, created_at, updated_at)
  VALUES (?, ?, ?, NOW(), NOW())
");
$success = $stmt->execute([
  $data['vendor_id'],
  $data['amount'],
  $data['withdrawal_method_id']
]);

header('Content-Type: application/json');
echo json_encode([
  'success'=>(bool)$success,
  'request_id'=>$pdo->lastInsertId()
]);
