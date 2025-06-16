<?php
require_once 'config.php';
require_once 'logger.php';

log_action(__FILE__, ['action' => 'ping']);

header('Content-Type: application/json');
echo json_encode([
    'status'    => 'online',
    'timestamp' => date("Y-m-d H:i:s")
]);
?>
