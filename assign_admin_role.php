<?php
require_once 'config.php';
require_once 'auth.php';
require_once 'logger.php';

if (!isAuthorized()) {
    http_response_code(403);
    exit(json_encode(['error'=>'Unauthorized']));
}

$admin_id = filter_var($_GET['admin_id']??0, FILTER_VALIDATE_INT);
$role_id  = filter_var($_GET['role_id'] ??0, FILTER_VALIDATE_INT);
if (!$admin_id || !$role_id) {
    http_response_code(400);
    exit(json_encode(['error'=>'admin_id & role_id required']));
}

$stmt = $pdo->prepare("
  UPDATE admins 
  SET role_id = ? 
  WHERE id = ?
");
$success = $stmt->execute([$role_id,$admin_id]);

header('Content-Type: application/json');
echo json_encode(['success'=>(bool)$success]);
