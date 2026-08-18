<?php
/**
 * Test script for Backend Milestone 4
 * Tests customer account, orders, and returns functionality
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

echo "=== Backend Milestone 4 Test Script ===\n\n";

try {
    $db = get_db_connection();
    
    // Test 1: Database connection
    echo "[Test 1] Database Connection\n";
    $stmt = $db->query('SELECT COUNT(*) as cnt FROM customers');
    $row = $stmt->fetch();
    echo "✓ Database connected. Customer count: " . $row['cnt'] . "\n\n";
    
    // Test 2: Get customer profile function
    echo "[Test 2] Get Customer Profile Function\n";
    $stmt = $db->query('SELECT customer_id FROM customers LIMIT 1');
    $customer = $stmt->fetch();
    
    if ($customer) {
        $cid = (int) $customer['customer_id'];
        $profile = get_customer_profile($cid);
        if ($profile) {
            echo "✓ Customer profile retrieved for ID $cid\n";
            echo "  - Name: " . $profile['first_name'] . " " . $profile['last_name'] . "\n";
            echo "  - Email: " . $profile['email'] . "\n";
            echo "  - City: " . $profile['city'] . "\n\n";
        } else {
            echo "✗ Failed to retrieve profile\n\n";
        }
    } else {
        echo "! No customers in database\n\n";
    }
    
    // Test 3: Order history function
    echo "[Test 3] Get Customer Order History\n";
    if ($customer) {
        $cid = (int) $customer['customer_id'];
        $orders = get_customer_order_history($cid);
        echo "✓ Retrieved " . count($orders) . " orders\n";
        if (!empty($orders)) {
            echo "  Sample order: Order #" . $orders[0]['order_number'] . ", Status: " . $orders[0]['status'] . "\n";
        }
        echo "\n";
    }
    
    // Test 4: Can cancel order function
    echo "[Test 4] Can Cancel Order Function\n";
    $stmt = $db->query('SELECT order_id, customer_id, status FROM orders WHERE status IN ("pending", "confirmed") LIMIT 1');
    $order = $stmt->fetch();
    if ($order) {
        $can_cancel = can_cancel_order((int) $order['order_id'], (int) $order['customer_id']);
        echo "✓ Order #" . $order['order_id'] . " (status: " . $order['status'] . ") can_cancel: " . ($can_cancel ? "yes" : "no") . "\n\n";
    } else {
        echo "! No pending/confirmed orders to test\n\n";
    }
    
    // Test 5: Return eligible items function
    echo "[Test 5] Get Customer Eligible Return Items\n";
    if ($customer) {
        $cid = (int) $customer['customer_id'];
        $items = get_customer_eligible_return_items($cid);
        echo "✓ Found " . count($items) . " eligible items for return\n";
        if (!empty($items)) {
            echo "  Sample: " . $items[0]['product_name'] . "\n";
        }
        echo "\n";
    }
    
    // Test 6: Validate functions exist and are callable
    echo "[Test 6] Validate All Required Functions\n";
    $required_functions = [
        'get_customer_profile',
        'update_customer_profile',
        'update_customer_password',
        'can_cancel_order',
        'cancel_order',
        'get_order_by_id_for_customer',
        'get_customer_order_history',
        'get_customer_eligible_return_items',
        'get_customer_return_requests',
        'validate_email',
        'validate_password'
    ];
    
    $missing = [];
    foreach ($required_functions as $func) {
        if (!function_exists($func)) {
            $missing[] = $func;
        }
    }
    
    if (empty($missing)) {
        echo "✓ All required functions are defined\n\n";
    } else {
        echo "✗ Missing functions: " . implode(", ", $missing) . "\n\n";
    }
    
    echo "=== Tests Complete ===\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack: " . $e->getTraceAsString() . "\n";
}
?>
