<?php
// common.php
// =======================================================
// Shared bootstrap + helpers for all endpoints
// =======================================================

declare(strict_types=1);

// ────────────────────────────────────────────────────────
// 1) CORE BOOTSTRAP
//    - Enforce HTTPS
//    - Load config (PDO + key check)
//    - Load auth, logger
// ────────────────────────────────────────────────────────

if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => ['code'=>'https_required','message'=>'HTTPS is required']], JSON_UNESCAPED_SLASHES);
    exit;
}

require_once __DIR__ . '/config.php';   // defines ACCESS_KEY, DB_*, instantiates $pdo
require_once __DIR__ . '/auth.php';     // defines isAuthorized()
require_once __DIR__ . '/logger.php';   // defines log_action()

// ────────────────────────────────────────────────────────
// 2) GLOBAL EXCEPTION & ERROR HANDLER
//    Converts any uncaught exception / PHP error into JSON
// ────────────────────────────────────────────────────────

set_exception_handler(function (\Throwable $e) {
    errorResponse(500, 'internal_error', $e->getMessage());
});

set_error_handler(function (int $errno, string $errstr) {
    errorResponse(500, 'internal_error', $errstr);
});

// ────────────────────────────────────────────────────────
// 3) CORS (optional — uncomment to enable for cross-origin)
// ────────────────────────────────────────────────────────
//
// header('Access-Control-Allow-Origin: https://yourdomain.com');
// header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
// header('Access-Control-Allow-Headers: Content-Type, X-Access-Key');
// if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
//     http_response_code(204);
//     exit;
// }

// ────────────────────────────────────────────────────────
// 4) JSON REQUEST PARSING
//    Returns decoded array, or sends 400 if invalid/missing
// ────────────────────────────────────────────────────────

/**
 * Decode JSON body into associative array.
 * @return array
 */
function parseJsonInput(): array {
    $raw = file_get_contents('php://input');
    if (empty($raw)) {
        return [];
    }
    $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
    if (!is_array($data)) {
        errorResponse(400, 'invalid_json', 'Request body must be valid JSON object');
    }
    return $data;
}

// ────────────────────────────────────────────────────────
// 5) PAGINATION PARAMS HELPER
//    Reads ?page & ?per_page, enforces sane defaults
// ────────────────────────────────────────────────────────

/**
 * Get pagination parameters from $_GET.
 *
 * @return array{page:int, per_page:int, offset:int}
 */
function getPaginationParams(): array {
    $page     = isset($_GET['page'])     ? (int)$_GET['page']     : 1;
    $perPage  = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
    $page     = max(1, $page);
    $perPage  = max(1, min(100, $perPage));  // cap at 100
    $offset   = ($page - 1) * $perPage;
    return ['page'=>$page, 'per_page'=>$perPage, 'offset'=>$offset];
}

// ────────────────────────────────────────────────────────
// 6) RESPONSE HELPERS
//    Standard JSON envelope for errors & success
// ────────────────────────────────────────────────────────

/**
 * Send a JSON error and exit.
 *
 * @param int    $httpCode  HTTP status code
 * @param string $code      Machine-readable error code
 * @param string $message   Human-readable error message
 */
function errorResponse(int $httpCode, string $code, string $message): void {
    http_response_code($httpCode);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error'   => [
            'code'    => $code,
            'message' => $message
        ]
    ], JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Send a JSON success envelope and exit.
 *
 * @param array $data  Any additional data to merge in
 */
function successResponse(array $data = []): void {
    header('Content-Type: application/json');
    echo json_encode(array_merge(['success' => true], $data), JSON_UNESCAPED_SLASHES);
    exit;
}

// ────────────────────────────────────────────────────────
// 7) AUTHORIZATION GUARD
//    Call in endpoints to enforce ACCESS_KEY
// ────────────────────────────────────────────────────────

if (!isAuthorized()) {
    errorResponse(403, 'unauthorized', 'Invalid or missing access key');
}

// ────────────────────────────────────────────────────────
// 8) EXPOSE COMMON OBJECTS
//    $pdo, log_action(), parseJsonInput(), getPaginationParams()
// ────────────────────────────────────────────────────────

// All set — your endpoint script can now:
// - call parseJsonInput() to get JSON body
// - call getPaginationParams() for page/per_page/offset
// - use $pdo for queries
// - log via log_action(__FILE__, [...])
// - on error: errorResponse(...)
// - on success: successResponse([...])
