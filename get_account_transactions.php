<?php
require_once __DIR__ . '/common.php';


log_action(__FILE__, ['params'=>$_GET]);

$type = preg_replace('/[^a-z_]/','',($_GET['from_type']??''));
$id   = filter_var($_GET['from_id']??0, FILTER_VALIDATE_INT);
if (!$type || !$id) {
    http_response_code(400);
    exit(json_encode(['error'=>'from_type & from_id required']));
}

$stmt = $pdo->prepare("
  SELECT * FROM account_transactions
  WHERE from_type = ? AND from_id = ?
");
$stmt->execute([$type,$id]);
$txns = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($txns);
