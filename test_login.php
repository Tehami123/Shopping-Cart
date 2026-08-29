<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$email = 'employee@artsshop.local';
$password = 'EmployeePassword123!';

$db = get_db_connection();
$stmt = $db->prepare('SELECT user_id, password_hash, role, status FROM users WHERE email = :email');
$stmt->execute([':email' => $email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
    if ($user['role'] === 'employee') {
        echo "PASS";
    } else {
        echo "FAIL - wrong role";
    }
} else {
    echo "FAIL - wrong credentials";
}
