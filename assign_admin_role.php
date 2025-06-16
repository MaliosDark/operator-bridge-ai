<?php
require_once 'common.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse(405, 'method_not_allowed', 'POST required');
}
if (!isAuthorized()) {
    errorResponse(403, 'unauthorized', 'Invalid access key');
}

$body     = json_decode(file_get_contents('php://input'), true);
$admin_id = filter_var($body['admin_id'] ?? 0, FILTER_VALIDATE_INT);
$role_id  = filter_var($body['role_id']  ?? 0, FILTER_VALIDATE_INT);

if (!$admin_id || !$role_id) {
    errorResponse(400, 'invalid_parameters', 'admin_id and role_id are required');
}

$upd = $pdo->prepare("UPDATE admins SET role_id = ? WHERE id = ?");
$ok  = $upd->execute([$role_id, $admin_id]);
log_action(__FILE__, ['admin_id'=>$admin_id,'role_id'=>$role_id,'result'=>$ok]);

if (!$ok) {
    errorResponse(500, 'db_error', 'Failed to assign role');
}

successResponse();
