<?php
require_once 'config.php';
require_once 'logger.php';

log_action(__FILE__, ['action'=>'get_automated_messages']);

$stmt = $pdo->query("
  SELECT id, message, status, created_at 
  FROM automated_messages
");
$msgs = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($msgs);
