<?php
require_once 'config.php';
require_once 'logger.php';

// Only accept POST + correct key
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_GET['key'] ?? '') !== ACCESS_KEY) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

$payload = json_decode(file_get_contents('php://input'), true);
log_action(__FILE__, ['webhook_payload' => $payload]);

// Example: if Laravel notifies "order_created", call an AI routine or queue
if (!empty($payload['event']) && $payload['event'] === 'order_created') {
    // e.g. trigger your AI client via shell or HTTP call
    // shell_exec("php /path/to/your_ai_client.php new_order ".$payload['data']['order_id']);
}

header('Content-Type: application/json');
echo json_encode(['received' => true]);
