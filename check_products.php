<?php
require 'config/database.php';
$db = get_db_connection();
$stmt = $db->query("SELECT product_code, product_number, image_url FROM products");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
