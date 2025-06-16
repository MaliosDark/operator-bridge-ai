<?php
require_once 'config.php';
require_once 'logger.php';

log_action(__FILE__, ['action'=>'get_admin_roles']);

$stmt = $pdo->query("
  SELECT id, name, modules, status 
  FROM admin_roles
");
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($roles);
