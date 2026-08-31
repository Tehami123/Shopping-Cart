<?php
require 'config/database.php';
$db = get_db_connection();
$stmt = $db->query("SELECT * FROM orders WHERE status = 'delivered' ORDER BY order_id DESC LIMIT 1");
$order = $stmt->fetch();
echo "Order Status: " . $order['status'] . "\n";
echo "Delivery Date: " . $order['delivery_date'] . "\n";

function is_within_return_window_test(?string $deliveryDate)
{
    if ($deliveryDate === null || trim($deliveryDate) === '') {
        return false;
    }

    $delivered = strtotime(date('Y-m-d', strtotime($deliveryDate)));
    $today = strtotime(date('Y-m-d'));
    if ($delivered === false || $today === false) {
        return false;
    }

    $daysSinceDelivery = (int) floor(($today - $delivered) / 86400);
    echo "Days since delivery: " . $daysSinceDelivery . "\n";
    return $daysSinceDelivery >= 0 && $daysSinceDelivery <= 7;
}
$isEligible = is_within_return_window_test($order['delivery_date']);
echo "Eligible? " . ($isEligible ? 'Yes' : 'No') . "\n";
?>
