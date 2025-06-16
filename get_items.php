<?php
require_once 'config.php';
require_once 'logger.php';

log_action(__FILE__, ['action'=>'get_items','params'=>$_GET]);

// Filters
$module_id = filter_var($_GET['module_id'] ?? 0, FILTER_VALIDATE_INT) ?: null;
$store_id  = filter_var($_GET['store_id']  ?? 0, FILTER_VALIDATE_INT) ?: null;

// Pagination
$page     = max(1,  filter_var($_GET['page']     ?? 1, FILTER_VALIDATE_INT));
$per_page = max(1,  filter_var($_GET['per_page'] ?? 20, FILTER_VALIDATE_INT));
$offset   = ($page - 1) * $per_page;

// Build WHERE
$where = [];
$params = [];
if ($module_id) {
    $where[]   = "module_id = ?";
    $params[]  = $module_id;
}
if ($store_id) {
    $where[]   = "store_id = ?";
    $params[]  = $store_id;
}
$where_sql = $where ? "WHERE " . implode(' AND ', $where) : "";

// Count total
$countSql = "SELECT COUNT(*) FROM items $where_sql";
$countStmt= $pdo->prepare($countSql);
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();

// Fetch page
$dataSql = "
    SELECT id, name, price, status, store_id, created_at
    FROM items
    $where_sql
    ORDER BY created_at DESC
    LIMIT :limit OFFSET :offset
";
$dataStmt = $pdo->prepare($dataSql);
// bind the dynamic params first
foreach ($params as $i => $val) {
    $dataStmt->bindValue($i+1, $val, PDO::PARAM_INT);
}
$dataStmt->bindValue(':limit',  $per_page, PDO::PARAM_INT);
$dataStmt->bindValue(':offset', $offset,   PDO::PARAM_INT);
$dataStmt->execute();
$items = $dataStmt->fetchAll(PDO::FETCH_ASSOC);

// Response
$response = [
    'success'    => true,
    'data'       => $items,
    'pagination' => [
        'page'        => $page,
        'per_page'    => $per_page,
        'total'       => $total,
        'total_pages' => ceil($total / $per_page),
    ],
];

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_SLASHES);
