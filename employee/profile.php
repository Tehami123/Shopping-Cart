<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_employee();

$db = get_db_connection();
$userId = (int) $_SESSION['user_id'];

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $error = 'Please fill in all fields.';
    } elseif ($newPassword !== $confirmPassword) {
        $error = 'New passwords do not match.';
    } elseif (strlen($newPassword) < 8) {
        $error = 'New password must be at least 8 characters long.';
    } else {
        $stmt = $db->prepare('SELECT password_hash FROM users WHERE user_id = ?');
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if ($user && password_verify($currentPassword, $user['password_hash'])) {
            $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
            $update = $db->prepare('UPDATE users SET password_hash = ? WHERE user_id = ?');
            $update->execute([$newHash, $userId]);
            $success = 'Your password has been changed successfully.';
        } else {
            $error = 'Current password is incorrect.';
        }
    }
}

$pageTitle = 'Profile - Arts Employee';
$basePath = '/Shopping-Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
require_once dirname(__DIR__) . '/includes/employee-shell.php';

$activePage = 'profile.php';
$employeeNav = [
    'index.php' => 'Dashboard',
    'orders.php' => 'Orders',
    'dispatch.php' => 'Dispatch',
    'delivery.php' => 'Delivery'
];
?>
<main class="employee-app">
    <div class="employee-layout">
        <?php render_employee_sidebar($employeeNav, $activePage, $basePath); ?>
        <section class="employee-main">
            <?php render_employee_page_header('Profile', 'Manage your account security and operations access.', 'Account Settings'); ?>

            <section class="employee-work-grid">
                <article class="employee-panel" style="max-width: 500px;">
                    <div class="employee-panel-heading"><h2>Change Password</h2></div>
                    <div style="padding: 1.5rem;">
                        <?php if ($error): ?>
                            <div class="auth-error" style="background:#fff5f5; color:#c53030; padding:1rem; margin-bottom:1rem; border-left:4px solid #c53030;">
                                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="auth-success" style="background:#f0fff4; color:#2f855a; padding:1rem; margin-bottom:1rem; border-left:4px solid #2f855a;">
                                <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label for="current_password">Current Password</label>
                                <input type="password" id="current_password" name="current_password" class="form-input" required>
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label for="new_password">New Password</label>
                                <input type="password" id="new_password" name="new_password" class="form-input" required>
                            </div>
                            <div class="form-group" style="margin-bottom: 1.5rem;">
                                <label for="confirm_password">Confirm New Password</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-input" required>
                            </div>
                            <button type="submit" class="primary-button" style="width: 100%;">Update Password</button>
                        </form>
                    </div>
                </article>
            </section>
        </section>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
