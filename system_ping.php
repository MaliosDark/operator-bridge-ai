<?php
require_once __DIR__ . '/common.php';


log_action(__FILE__, ['action' => 'ping']);

header('Content-Type: application/json');
echo json_encode([
    'status'    => 'online',
    'timestamp' => date("Y-m-d H:i:s")
]);
?>
