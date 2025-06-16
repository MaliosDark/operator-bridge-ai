<?php
include 'config.php';

echo json_encode([
    "status" => "online",
    "timestamp" => date("Y-m-d H:i:s")
]);
?>
