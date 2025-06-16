<?php
// Simple token-based auth if you prefer header over GET param
function isAuthorized() {
    // Optionally accept key via header:
    $headers = getallheaders();
    if (
        (isset($_GET['key']) && $_GET['key'] === ACCESS_KEY) ||
        (isset($headers['X-Access-Key']) && $headers['X-Access-Key'] === ACCESS_KEY)
    ) {
        return true;
    }
    return false;
}
?>
