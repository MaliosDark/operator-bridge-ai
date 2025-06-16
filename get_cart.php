<?php
require_once __DIR__ . '/common.php';


log_action(__FILE__, ['params'=>$_GET]);

$user_id = filter_var($_GET['user_id'] ?? 0, FILTER_VALIDATE_INT);
if (!$user_id) {
    http_response_code(400);
    exit(json_encode(['error'=>'user_id required']));
}

$stmt = $pdo->prepare("
  SELECT * FROM carts WHERE user_id = ?
");
$stmt->execute([$user_id]);
$cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($cart);
