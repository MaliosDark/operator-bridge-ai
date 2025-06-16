<?php
require_once 'config.php';
require_once 'logger.php';

log_action(__FILE__, ['params'=>$_GET]);

$module_id = filter_var($_GET['module_id'] ?? 0, FILTER_VALIDATE_INT);
$store_id  = filter_var($_GET['store_id']  ?? 0, FILTER_VALIDATE_INT);

$where = [];
$params = [];
if ($module_id) { $where[]="module_id=?"; $params[]=$module_id; }
if ($store_id)  { $where[]="store_id=?";  $params[]=$store_id; }
$clause = $where ? "WHERE ".implode(' AND ',$where) : "";

$stmt = $pdo->prepare("
  SELECT id, name, price, status, store_id, created_at
  FROM items
  $clause
");
$stmt->execute($params);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($items);
