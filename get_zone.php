<?php
require_once __DIR__ . '/common.php';


log_action(__FILE__, ['params'=>$_GET]);

$id = filter_var($_GET['id']??0, FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit(json_encode(['error'=>'id required']));
}

$stmt = $pdo->prepare("SELECT * FROM zones WHERE id = ?");
$stmt->execute([$id]);
$zone = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($zone ?: ['error'=>'Zone not found']);
