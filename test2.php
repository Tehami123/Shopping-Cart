<?php
require 'config/database.php';
$db = get_db_connection();
$stmt = $db->query("SELECT order_id, status, delivery_date FROM orders");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
