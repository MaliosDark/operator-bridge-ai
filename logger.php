<?php
// ------------------------------
// SIMPLE LOGGER
// ------------------------------
function log_action(string $action, array $details = []) {
    $logfile = __DIR__ . '/access.log';
    $entry = [
        'timestamp' => date("Y-m-d H:i:s"),
        'action'    => $action,
        'details'   => $details,
        'ip'        => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'agent'     => $_SERVER['HTTP_USER_AGENT'] ?? ''
    ];
    // Append JSON entry
    file_put_contents($logfile, json_encode($entry, JSON_UNESCAPED_SLASHES) . "\n", FILE_APPEND | LOCK_EX);
}
?>
