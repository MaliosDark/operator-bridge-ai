<?php
require_once __DIR__ . '/common.php';


// CONFIG: your alert email
define('ALERT_EMAIL', 'you@yourdomain.com');

log_action(__FILE__, ['action' => 'health_check_start']);

$status = ['db' => 'ok', 'orders_table' => 'ok'];
$errors = [];

// 1) Test DB connection
try {
    $pdo->query('SELECT 1');
} catch (PDOException $e) {
    $status['db'] = 'fail';
    $errors[] = 'Database connection error';
}

// 2) Test critical table exists
try {
    $count = $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
} catch (PDOException $e) {
    $status['orders_table'] = 'fail';
    $errors[] = 'Orders table missing or inaccessible';
}

// 3) Log result
log_action(__FILE__, ['status' => $status]);

// 4) If any fail, send email alert
if (!empty($errors)) {
    $subject = 'ALERT: AI Bridge Health Check Failure';
    $body = "Health check detected errors:\n\n" . implode("\n", $errors)
         . "\n\nStatus:\n" . json_encode($status, JSON_PRETTY_PRINT);
    @mail(ALERT_EMAIL, $subject, $body);
}

header('Content-Type: application/json');
echo json_encode(['status' => $status, 'errors' => $errors]);
