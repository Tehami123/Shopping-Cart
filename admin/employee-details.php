<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

$userId = (int)($_GET['id'] ?? 0);
if ($userId <= 0) {
    header('Location: employees.php');
    exit;
}

$pageTitle = 'Employee Details - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
require_once dirname(__DIR__) . '/includes/admin-shell.php';

$db = get_db_connection();
$activePage = 'employees.php';
$adminNav = [
    'index.php' => 'Dashboard', 'products.php' => 'Products', 'inventory.php' => 'Inventory',
    'orders.php' => 'Orders', 'customers.php' => 'Customers', 'employees.php' => 'Employees',
    'payments.php' => 'Payments', 'returns.php' => 'Returns', 'feedback.php' => 'Feedback', 'faq.php' => 'FAQ'
];

$stmt = $db->prepare('
    SELECT u.user_id, u.email, u.role, u.status, u.created_at,
           e.employee_id, e.first_name, e.last_name, e.hire_date
    FROM users u
    LEFT JOIN employees e ON u.user_id = e.user_id
    WHERE u.user_id = :user_id AND u.role = "employee"
');
$stmt->execute([':user_id' => $userId]);
$employee = $stmt->fetch();

if (!$employee) {
    echo "<main class='admin-app'><div class='admin-layout'><section class='admin-main'><h2>Employee not found</h2><a href='employees.php'>Back to Employees</a></section></div></main>";
    require_once dirname(__DIR__) . '/includes/footer.php';
    exit;
}

?>
<main class="admin-app">
    <div class="admin-layout">
        <?php render_admin_sidebar($adminNav, $activePage, $basePath); ?>
        <section class="admin-main">
            <?php render_admin_page_header('Employee Details', 'Detailed view for ' . htmlspecialchars(trim(($employee['first_name'] ?? '') . ' ' . ($employee['last_name'] ?? '')) ?: 'Employee account', ENT_QUOTES, 'UTF-8'), 'Staff Overview'); ?>
            
            <div style="margin-bottom: 20px;">
                <a href="employees.php" class="secondary-button" style="text-decoration:none;">&larr; Back to Employees</a>
            </div>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div class="auth-card" style="margin:0; width:auto; max-width:100%;">
                    <h3 style="margin-top:0;">Account Information</h3>
                    <p><strong>Name:</strong> <?= htmlspecialchars(trim(($employee['first_name'] ?? '') . ' ' . ($employee['last_name'] ?? '')) ?: 'N/A', ENT_QUOTES, 'UTF-8') ?></p>
                    <p><strong>Email / Login:</strong> <a href="mailto:<?= htmlspecialchars($employee['email'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($employee['email'], ENT_QUOTES, 'UTF-8') ?></a></p>
                    <p><strong>Role:</strong> <?= ucfirst(htmlspecialchars($employee['role'], ENT_QUOTES, 'UTF-8')) ?></p>
                    <p>
                        <strong>Account Status:</strong> 
                        <span class="status-badge <?= $employee['status'] === 'active' ? 'status-delivered' : 'status-cancelled' ?>"><?= ucfirst(htmlspecialchars($employee['status'], ENT_QUOTES, 'UTF-8')) ?></span>
                    </p>
                    
                    <hr style="margin: 15px 0; border: 0; border-top: 1px solid #e2e8f0;">
                    
                    <h3 style="margin-top:0;">Employment Details</h3>
                    <p><strong>Employee ID:</strong> <?= $employee['employee_id'] ? htmlspecialchars($employee['employee_id'], ENT_QUOTES, 'UTF-8') : 'N/A' ?></p>
                    <p><strong>User ID:</strong> <?= htmlspecialchars($employee['user_id'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p><strong>Hire Date:</strong> <?= $employee['hire_date'] ? date('d M Y', strtotime($employee['hire_date'])) : 'N/A' ?></p>
                    <p><strong>System Registration:</strong> <?= date('d M Y, h:i A', strtotime($employee['created_at'])) ?></p>
                </div>
            </div>

        </section>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
