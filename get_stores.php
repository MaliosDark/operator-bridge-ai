<?php
require_once __DIR__ . '/common.php';


log_action(__FILE__, ['action'=>'get_stores']);

$stmt = $pdo->query("
  SELECT id, name, phone, email, status, zone_id, created_at 
  FROM stores
");
$stores = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($stores);
