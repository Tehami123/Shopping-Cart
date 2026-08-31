<?php
require 'config/database.php';
$db = get_db_connection();
$stmt = $db->query("UPDATE orders SET delivery_date = CURDATE() WHERE status = 'delivered' AND delivery_date IS NULL");
echo "Updated " . $stmt->rowCount() . " orders.";
?>
