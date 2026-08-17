<?php
/**
 * Database connection for Arts Online Shopping Cart.
 *
 * Include this file once per request:
 *   require_once __DIR__ . '/config/database.php';           (from project root)
 *   require_once dirname(__DIR__) . '/config/database.php';  (from subfolders)
 *
 * After include, use the shared PDO instance in $conn.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 3600,
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    ini_set('session.gc_maxlifetime', '3600');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
}

$db_host = 'localhost';
$db_name = 'arts_shop';
$db_user = 'root';
$db_pass = '';

$pdo_options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $conn = new PDO(
        "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4",
        $db_user,
        $db_pass,
        $pdo_options
    );
} catch (PDOException $e) {
    http_response_code(500);
    die('Database connection failed.');
}

/**
 * Return the shared PDO connection (for use in helper functions).
 */
function get_db_connection(): PDO
{
    global $conn;
    return $conn;
}
