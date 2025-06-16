<?php
require_once __DIR__ . '/common.php';
log_action(__FILE__, ['action'=>'get_items','params'=>$_GET]);

$module_id = filter_var($_GET['module_id'] ?? null, FILTER_VALIDATE_INT);
$store_id  = filter_var($_GET['store_id']  ?? null, FILTER_VALIDATE_INT);

$page     = max(1, filter_var($_GET['page']     ?? 1, FILTER_VALIDATE_INT));
$per_page = max(1, filter_var($_GET['per_page'] ?? 20, FILTER_VALIDATE_INT));
$offset   = ($page - 1) * $per_page;

$where = []; $params = [];
if ($module_id) { $where[] = "module_id=?"; $params[] = $module_id; }
if ($store_id)  { $where[] = "store_id=?";  $params[] = $store_id; }
$where_sql = $where ? "WHERE " . implode(' AND ', $where) : "";

// total
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM items $where_sql");
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();

// page
$dataStmt = $pdo->prepare("
  SELECT id,name,price,status,store_id,created_at
  FROM items
  $where_sql
  ORDER BY created_at DESC
  LIMIT :limit OFFSET :offset
");
foreach ($params as $i=>$v) {
  $dataStmt->bindValue($i+1, $v, PDO::PARAM_INT);
}
$dataStmt->bindValue(':limit',  $per_page, PDO::PARAM_INT);
$dataStmt->bindValue(':offset', $offset,   PDO::PARAM_INT);
$dataStmt->execute();
$items = $dataStmt->fetchAll(PDO::FETCH_ASSOC);

successResponse([
  'data'       => $items,
  'pagination' => [
    'page'        => $page,
    'per_page'    => $per_page,
    'total'       => $total,
    'total_pages' => ceil($total / $per_page),
  ],
]);
