<?php
require_once __DIR__ . '/common.php';
require_once 'auth.php';


if (!isAuthorized()) {
    http_response_code(403);
    exit(json_encode(['error'=>'Unauthorized']));
}

$data = json_decode(file_get_contents('php://input'), true);
log_action(__FILE__, ['body'=>$data]);

$req = ['from_type','from_id','amount','method','type'];
foreach($req as $f){
  if(!isset($data[$f])){
    http_response_code(400);
    exit(json_encode(['error'=>"$f required"]));
  }
}

$stmt = $pdo->prepare("
  INSERT INTO account_transactions
    (from_type, from_id, current_balance, amount, method, type, ref, created_at, updated_at)
  VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
");
// Ideally fetch current_balance first...
$current = $pdo->query(
  "SELECT current_balance FROM account_transactions 
   WHERE from_type='{$data['from_type']}' AND from_id={$data['from_id']}
   ORDER BY id DESC LIMIT 1"
)->fetchColumn() ?: 0;

$success = $stmt->execute([
  $data['from_type'],
  $data['from_id'],
  $current + $data['amount'],
  $data['amount'],
  $data['method'],
  $data['type'],
  $data['ref'] ?? null
]);

header('Content-Type: application/json');
echo json_encode([
  'success'=>(bool)$success,
  'transaction_id'=>$pdo->lastInsertId()
]);
