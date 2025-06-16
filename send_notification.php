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

foreach (['user_id','title','description'] as $f) {
    if (empty($data[$f])) {
        http_response_code(400);
        exit(json_encode(['error'=>"$f required"]));
    }
}

$stmt = $pdo->prepare("
  INSERT INTO user_notifications
    (user_id, data, status, created_at, updated_at)
  VALUES (?, ?, 1, NOW(), NOW())
");
$payload = json_encode([
  'title'=>$data['title'],
  'description'=>$data['description']
]);
$success = $stmt->execute([$data['user_id'], $payload]);

header('Content-Type: application/json');
echo json_encode(['success'=>(bool)$success]);
