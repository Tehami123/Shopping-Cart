<?php
require_once __DIR__ . '/../config/database.php';

function session_start_secure(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 3600,
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        session_start();
    }
}

function login_user(string $email, string $password): ?array
{
    $email = trim($email);
    if ($email === '' || $password === '') {
        return null;
    }

    $db = get_db_connection();
    $stmt = $db->prepare(
        'SELECT user_id, email, password_hash, role, status FROM users WHERE email = :email LIMIT 1'
    );
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        return null;
    }

    if ($user['status'] !== 'active') {
        return null;
    }

    session_start_secure();
    $_SESSION['user_id'] = (int) $user['user_id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['login_time'] = time();

    return $user;
}

function logout_user(): void
{
    session_start_secure();
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}

function is_logged_in(): bool
{
    session_start_secure();
    return !empty($_SESSION['user_id']) && !empty($_SESSION['user_role']);
}

function current_user_id(): ?int
{
    if (!is_logged_in()) {
        return null;
    }

    return (int) $_SESSION['user_id'];
}

function current_user_role(): ?string
{
    if (!is_logged_in()) {
        return null;
    }

    return $_SESSION['user_role'];
}

function current_user(): ?array
{
    $userId = current_user_id();
    if ($userId === null) {
        return null;
    }

    $db = get_db_connection();
    $stmt = $db->prepare(
        'SELECT user_id, email, role, status, created_at FROM users WHERE user_id = :user_id LIMIT 1'
    );
    $stmt->execute([':user_id' => $userId]);
    $user = $stmt->fetch();

    return $user ?: null;
}

function require_login(string $redirectTo = '/Shopping%20Cart/auth/login.php'): void
{
    session_start_secure();
    if (!is_logged_in()) {
        header('Location: ' . $redirectTo);
        exit;
    }
}

function require_role($roles, string $redirectTo = '/Shopping%20Cart/index.php'): void
{
    require_login();

    $allowedRoles = is_array($roles) ? $roles : [$roles];
    $currentRole = current_user_role();

    if ($currentRole === null || !in_array($currentRole, $allowedRoles, true)) {
        http_response_code(403);
        header('Location: ' . $redirectTo);
        exit;
    }
}

function require_admin(string $redirectTo = '/Shopping%20Cart/index.php'): void
{
    require_role('admin', $redirectTo);
}

function require_employee(string $redirectTo = '/Shopping%20Cart/index.php'): void
{
    require_role(['employee', 'admin'], $redirectTo);
}

function require_customer(string $redirectTo = '/Shopping%20Cart/auth/login.php'): void
{
    require_role('customer', $redirectTo);
}

function e(string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
