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

$store_id = filter_var($data['store_id'] ?? 0, FILTER_VALIDATE_INT);
$config   = $data['config'] ?? null;

if (!$store_id || !is_array($config)) {
    http_response_code(400);
    exit(json_encode(['error'=>'store_id and config array required']));
}

// Build SET clause dynamically, but only allow known keys
$allowed = ['footer_text','minimum_order','delivery','take_away','status'];
$sets = []; $vals = [];
foreach ($config as $k=>$v) {
  if (in_array($k,$allowed)) {
    $sets[] = "`$k` = ?";
    $vals[] = $v;
  }
}
if (!$sets) {
    exit(json_encode(['error'=>'No valid config fields']));
}
$vals[] = $store_id;

$sql = "UPDATE stores SET ".implode(',', $sets)." WHERE id = ?";
$stmt = $pdo->prepare($sql);
$success = $stmt->execute($vals);

header('Content-Type: application/json');
echo json_encode(['success'=>(bool)$success]);
