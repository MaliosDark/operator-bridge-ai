<?php
require_once __DIR__ . '/common.php';


log_action(__FILE__, ['action'=>'get_categories']);

$stmt = $pdo->query("
  SELECT id, name, slug, status 
  FROM categories
");
$cats = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($cats);
