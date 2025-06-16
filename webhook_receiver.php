<?php
require_once 'config.php';
require_once 'logger.php';

// Only POST + valid key
if ($_SERVER['REQUEST_METHOD']!=='POST' || ($_GET['key']??'')!==ACCESS_KEY) {
    http_response_code(403);
    exit(json_encode(['error'=>'Forbidden']));
}

$payload = json_decode(file_get_contents('php://input'), true);
log_action(__FILE__, ['webhook_payload'=>$payload]);

// Example: Laravel emits order_created
if (!empty($payload['event']) && $payload['event']==='order_created') {
    $orderId = intval($payload['data']['order_id'] ?? 0);
    if ($orderId) {
        // e.g. notify AI client:
        // shell_exec("php /path/to/ai_client.php new_order $orderId");
    }
}

header('Content-Type: application/json');
echo json_encode(['received'=>true]);
