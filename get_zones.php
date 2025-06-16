<?php
require_once __DIR__ . '/common.php';


log_action(__FILE__, ['action'=>'get_zones']);

$stmt = $pdo->query("SELECT id, name, status FROM zones");
$zones = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($zones);
