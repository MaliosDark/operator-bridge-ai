<?php
require_once __DIR__ . '/common.php';
require_once 'auth.php';


if (!isAuthorized()) {
    http_response_code(403);
    exit(json_encode(['error'=>'Unauthorized']));
}

$data = json_decode(file_get_contents('php://input'), true);
log_action(__FILE__, ['body'=>$data]);

$id = filter_var($data['id'] ?? 0, FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit(json_encode(['error'=>'id required']));
}

// Only allow these updatable fields:
$allowed = ['name','description','price','status'];
$sets = []; $vals = [];
foreach ($allowed as $k) {
  if (isset($data[$k])) {
    $sets[] = "`$k` = ?";
    $vals[] = $data[$k];
  }
}
if (!$sets) {
    exit(json_encode(['error'=>'No valid fields']));
}
$vals[] = $id;

$sql = "UPDATE items SET ".implode(',',$sets)." WHERE id = ?";
$stmt = $pdo->prepare($sql);
$success = $stmt->execute($vals);

header('Content-Type: application/json');
echo json_encode(['success'=>(bool)$success]);
