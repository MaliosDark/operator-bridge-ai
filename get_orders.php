<?php
require_once 'config.php';
require_once 'logger.php';

log_action(__FILE__, ['action'=>'get_orders_today','params'=>$_GET]);

// Pagination parameters
$page     = max(1,  filter_var($_GET['page']     ?? 1, FILTER_VALIDATE_INT));
$per_page = max(1,  filter_var($_GET['per_page'] ?? 20, FILTER_VALIDATE_INT));
$offset   = ($page - 1) * $per_page;

// Count total
$countStmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM orders 
    WHERE DATE(created_at) = CURDATE()
");
$countStmt->execute();
$total = (int)$countStmt->fetchColumn();

// Fetch paginated page
$dataStmt = $pdo->prepare("
    SELECT 
      id,
      user_id,
      order_amount,
      payment_status,
      order_status,
      delivery_address,
      created_at
    FROM orders 
    WHERE DATE(created_at) = CURDATE()
    ORDER BY created_at DESC
    LIMIT :limit OFFSET :offset
");
$dataStmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$dataStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$dataStmt->execute();
$orders = $dataStmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare response
$response = [
    'success'    => true,
    'data'       => $orders,
    'pagination' => [
        'page'       => $page,
        'per_page'   => $per_page,
        'total'      => $total,
        'total_pages'=> ceil($total / $per_page),
    ],
];

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_SLASHES);
