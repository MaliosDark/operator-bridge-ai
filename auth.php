<?php
require_once('config.php');

function isAuthorized() {
    $headers = apache_request_headers();
    return isset($headers['X-Access-Key']) && $headers['X-Access-Key'] === ACCESS_KEY;
}
?>
