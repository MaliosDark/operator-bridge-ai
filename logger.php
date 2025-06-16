<?php
function log_action($action, $details = '') {
    $logfile = __DIR__ . '/access.log';
    $entry = "[" . date("Y-m-d H:i:s") . "] $action - $details\n";
    file_put_contents($logfile, $entry, FILE_APPEND);
}
