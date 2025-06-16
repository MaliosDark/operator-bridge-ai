<?php
require_once __DIR__ . '/common.php';


log_action(__FILE__, ['params' => $_GET]);

$email = filter_var($_GET['email'] ?? '', FILTER_VALIDATE_EMAIL);
if (!$email) {
    http_response_code(400);
    echo json_encode(['error' => 'Valid email required']);
    exit;
}

$stmt = $pdo->prepare("SELECT id, name, email, created_at FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($user ?: ['error' => 'User not found']);
?>
<!-- <?php
require_once __DIR__ . '/common.php';


log_action(__FILE__, ['params'=>$_GET]);

$email = filter_var($_GET['email'] ?? '', FILTER_VALIDATE_EMAIL);
if (!$email) {
    http_response_code(400);
    echo json_encode(['error'=>'Valid email required']);
    exit;
}

// Select only the columns you want
$stmt = $pdo->prepare("
  SELECT 
    id,
    f_name,
    l_name,
    phone,
    email,
    image,
    is_phone_verified,
    email_verified_at,
    status,
    order_count,
    login_medium,
    social_id,
    zone_id,
    wallet_balance,
    loyalty_point,
    ref_code,
    ref_by,
    module_ids,
    created_at,
    updated_at
  FROM users
  WHERE email = ?
  LIMIT 1
");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode(
    $user 
    ?: ['error'=>'User not found']
);
 -->