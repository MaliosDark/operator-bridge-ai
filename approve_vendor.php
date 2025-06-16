<?php
include 'config.php';

$vendor_id = $_GET['vendor_id'] ?? '';
if (!$vendor_id) {
    echo json_encode(["error" => "Missing vendor_id"]);
    exit;
}

$stmt = $pdo->prepare("UPDATE stores SET status = 'approved' WHERE id = ?");
$success = $stmt->execute([$vendor_id]);

echo json_encode(["success" => $success]);
?>
