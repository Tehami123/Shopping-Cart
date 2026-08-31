<?php
require 'config/database.php';
$db = get_db_connection();

$updates = [
    ['01', '00001', '/Shopping-Cart/assets/images/product-3789e30f292d96f2a7525aa4.jpg'],
    ['01', '00002', '/Shopping-Cart/assets/images/product-16bfa1fac694b71eebcf7695.jpg'],
    ['01', '00003', '/Shopping-Cart/assets/images/product-446aba718b97e4b5fad564c0.jpg'],
    ['02', '00001', '/Shopping-Cart/assets/images/product-5a4786c45fd31b8bde895dea.jpg'],
    ['02', '00002', '/Shopping-Cart/assets/images/product-6fd3e727840a72eff1a97f4b.jpg'],
    ['03', '00001', '/Shopping-Cart/assets/images/product-b39f59d48d334cb1923065fb.jpg'],
    ['03', '00002', '/Shopping-Cart/assets/images/product-b8bc10dbff7bbcf6099e381e.jpg'],
    ['04', '00001', '/Shopping-Cart/assets/images/product-ee53c1fce06d914239ba0277.jpg'],
    ['04', '00002', '/Shopping-Cart/assets/images/product-cc7084dcd190e075f535dba3.jpg'],
    ['05', '00001', '/Shopping-Cart/assets/images/product-bdfbdbf67585ba11050a7901.jpg'],
    ['05', '00002', '/Shopping-Cart/assets/images/product-da20d0cded54ffb92e2ed33b.jpg'],
    ['06', '00001', '/Shopping-Cart/assets/images/product-f956f9860aa7b096c1b3cdf6.jpg'],
    ['07', '00001', '/Shopping-Cart/assets/images/product-f25ca1d73637faaa98361835.jpg'],
    ['08', '00001', '/Shopping-Cart/assets/images/product-f649bba8e3d2f8f86ed8ac41.jpg'],
];

$stmt = $db->prepare("UPDATE products SET image_url = :image_url WHERE product_code = :product_code AND product_number = :product_number");

$count = 0;
foreach ($updates as $update) {
    $stmt->execute([
        ':product_code' => $update[0],
        ':product_number' => $update[1],
        ':image_url' => $update[2]
    ]);
    $count += $stmt->rowCount();
}

echo "Updated $count products successfully.";
?>
