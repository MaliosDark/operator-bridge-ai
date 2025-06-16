<?php
require_once __DIR__ . '/common.php';
log_action(__FILE__, ['action'=>'get_orders_today','params'=>$_GET]);

$page     = max(1, filter_var($_GET['page']     ?? 1, FILTER_VALIDATE_INT));
$per_page = max(1, filter_var($_GET['per_page'] ?? 20, FILTER_VALIDATE_INT));
$offset   = ($page - 1) * $per_page;

// total
$total = (int)$pdo
  ->query("SELECT COUNT(*) FROM orders WHERE DATE(created_at)=CURDATE()")
  ->fetchColumn();

// data
$stmt = $pdo->prepare("
  SELECT id,user_id,order_amount,payment_status,order_status,delivery_address,created_at
  FROM orders
  WHERE DATE(created_at)=CURDATE()
  ORDER BY created_at DESC
  LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit',  $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset,   PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

successResponse([
  'data'       => $orders,
  'pagination' => [
    'page'        => $page,
    'per_page'    => $per_page,
    'total'       => $total,
    'total_pages' => ceil($total / $per_page),
  ],
]);
