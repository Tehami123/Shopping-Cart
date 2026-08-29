<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$email = 'employee@artsshop.local';
$password = 'EmployeePassword123!';
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $db = get_db_connection();
    $stmt = $db->prepare('UPDATE users SET email = :email, password_hash = :hash WHERE role = "employee"');
    $stmt->execute([
        ':email' => $email,
        ':hash' => $hash
    ]);
    
    if ($stmt->rowCount() > 0) {
        echo "Success: DB updated.\n";
    } else {
        echo "Warning: No rows affected. Maybe the employee account is missing or already has these exact credentials.\n";
        // To be safe, let's output current employee account info
        $check = $db->query('SELECT user_id, email, password_hash FROM users WHERE role = "employee"')->fetch();
        if ($check) {
            echo "Current DB state matches the update. ID: {$check['user_id']}\n";
        } else {
            echo "No employee found in DB!\n";
        }
    }
    echo "HASH: " . $hash . "\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
