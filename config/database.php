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
    die('DB Error: ' . $e->getMessage());
}

/**
 * Return the shared PDO connection (for use in helper functions).
 */
function get_db_connection(): PDO
{
    global $conn;
    return $conn;
}
