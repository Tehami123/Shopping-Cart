<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

$pageTitle = 'Manage Employees - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
require_once dirname(__DIR__) . '/includes/admin-shell.php';

$activePage = 'employees.php';
$adminNav = [
    'index.php' => 'Dashboard', 'products.php' => 'Products', 'inventory.php' => 'Inventory',
    'orders.php' => 'Orders', 'customers.php' => 'Customers', 'employees.php' => 'Employees',
    'payments.php' => 'Payments', 'returns.php' => 'Returns', 'feedback.php' => 'Feedback', 'faq.php' => 'FAQ'
];
$db = get_db_connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $email = $_POST['email'] ?? '';
    
    if ($action === 'revoke' && $email) {
        $stmt = $db->prepare('UPDATE users SET status = "inactive" WHERE email = :email AND role = "employee"');
        $stmt->execute([':email' => $email]);
        header('Location: employees.php');
        exit;
    } elseif ($action === 'edit' && $email) {
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $hireDate = $_POST['hire_date'] ?? date('Y-m-d');
        $status = $_POST['status'] === 'inactive' ? 'inactive' : 'active';
        
        $db->beginTransaction();
        try {
            $stmt = $db->prepare('UPDATE users SET status = :status WHERE email = :email AND role = "employee"');
            $stmt->execute([':status' => $status, ':email' => $email]);
            
            $stmt = $db->prepare('SELECT user_id FROM users WHERE email = :email AND role = "employee"');
            $stmt->execute([':email' => $email]);
            $userId = $stmt->fetchColumn();
            
            if ($userId) {
                $stmt = $db->prepare('UPDATE employees SET first_name = :first_name, last_name = :last_name, hire_date = :hire_date WHERE user_id = :user_id');
                $stmt->execute([':first_name' => $firstName, ':last_name' => $lastName, ':hire_date' => $hireDate, ':user_id' => $userId]);
            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
        }
        header('Location: employees.php');
        exit;
    }
}

$employees = $db->query(
    'SELECT u.user_id, e.employee_id, u.email, u.status, e.first_name, e.last_name, e.hire_date, u.created_at
     FROM users u
     LEFT JOIN employees e ON e.user_id = u.user_id
     WHERE u.role = "employee"
     ORDER BY u.user_id DESC'
)->fetchAll();
?>
<main class="admin-app">
    <div class="admin-layout">
        <?php render_admin_sidebar($adminNav, $activePage, $basePath); ?>
        <section class="admin-main">
            <div class="admin-page-header admin-page-header-with-action">
                <div><span class="admin-eyebrow">People workspace</span><h1>Employees</h1><p>Control staff access, profile details, and account status.</p></div>
                <button class="primary-button" onclick="document.getElementById('addEmpModal').style.display='flex'">Create employee</button>
            </div>
                
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Employee</th><th>Email/Login</th><th>Hire Date</th><th>Status</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($employees as $employee): ?>
                                <tr>
                                    <td>
                                        <?php if ($employee['user_id']): ?>
                                            <a href="employee-details.php?id=<?= (int)$employee['user_id'] ?>" style="text-decoration:none; color:inherit;">
                                                <strong class="admin-primary-cell"><?= htmlspecialchars(trim(($employee['first_name'] ?? '') . ' ' . ($employee['last_name'] ?? '')) ?: 'Employee account', ENT_QUOTES, 'UTF-8') ?></strong>
                                            </a>
                                        <?php else: ?>
                                            <strong class="admin-primary-cell"><?= htmlspecialchars(trim(($employee['first_name'] ?? '') . ' ' . ($employee['last_name'] ?? '')) ?: 'Employee account', ENT_QUOTES, 'UTF-8') ?></strong>
                                        <?php endif; ?>
                                        <br><small class="admin-table-muted">Staff account</small>
                                    </td>
                                    <td><?= htmlspecialchars($employee['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= date('d M Y', strtotime($employee['hire_date'] ?? $employee['created_at'])) ?></td>
                                    <td><span class="status-badge <?= $employee['status'] === 'active' ? 'status-delivered' : 'status-cancelled' ?>"><?= ucfirst(htmlspecialchars($employee['status'], ENT_QUOTES, 'UTF-8')) ?></span></td>
                                    <td>
                                        <a href="employee-details.php?id=<?= (int)$employee['user_id'] ?>" class="text-button" style="text-decoration:none;">View</a> | 
                                        <button type="button" class="text-button" onclick="document.getElementById('editEmpModal_<?= md5($employee['email']) ?>').style.display='flex'">Edit</button> | 
                                        <form method="post" style="display:inline;" onsubmit="return confirm('Revoke this employee?');">
                                            <input type="hidden" name="action" value="revoke">
                                            <input type="hidden" name="email" value="<?= htmlspecialchars($employee['email'], ENT_QUOTES, 'UTF-8') ?>">
                                            <button type="submit" class="text-button danger">Revoke</button>
                                        </form>
                                    </td>
                                </tr>
                                <div id="editEmpModal_<?= md5($employee['email']) ?>" class="mock-modal" style="display:none;">
                                    <div class="mock-modal-content">
                                        <h3>Edit Employee</h3>
                                        <form method="post">
                                            <input type="hidden" name="action" value="edit">
                                            <input type="hidden" name="email" value="<?= htmlspecialchars($employee['email'], ENT_QUOTES, 'UTF-8') ?>">
                                            <div class="form-row">
                                                <div class="form-group"><label>First Name</label><input type="text" name="first_name" class="form-input" value="<?= htmlspecialchars($employee['first_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></div>
                                                <div class="form-group"><label>Last Name</label><input type="text" name="last_name" class="form-input" value="<?= htmlspecialchars($employee['last_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group"><label>Hire Date</label><input type="date" name="hire_date" class="form-input" value="<?= htmlspecialchars($employee['hire_date'] ?? date('Y-m-d', strtotime($employee['created_at'])), ENT_QUOTES, 'UTF-8') ?>"></div>
                                                <div class="form-group"><label>Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="active" <?= $employee['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                                        <option value="inactive" <?= $employee['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mock-modal-actions">
                                                <button type="submit" class="primary-button">Save</button>
                                                <button type="button" class="secondary-button" onclick="this.closest('.mock-modal').style.display='none'">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
        </section>
    </div>
</main>

<div id="addEmpModal" class="mock-modal" style="display:none;">
    <div class="mock-modal-content">
        <h3>Create Employee</h3>
        <form onsubmit="event.preventDefault(); alert('Employee Created!'); this.closest('.mock-modal').style.display='none';">
            <div class="form-row">
                <div class="form-group"><label>First Name</label><input type="text" class="form-input"></div>
                <div class="form-group"><label>Last Name</label><input type="text" class="form-input"></div>
            </div>
            <div class="form-group"><label>Email / Login ID</label><input type="email" class="form-input"></div>
            <div class="form-group"><label>Password</label><input type="password" class="form-input"></div>
            <div class="form-row">
                <div class="form-group"><label>Hire Date</label><input type="date" class="form-input"></div>
                <div class="form-group"><label>Status</label><select class="form-select"><option>Active</option><option>Suspended</option></select></div>
            </div>
            <div class="mock-modal-actions">
                <button type="submit" class="primary-button">Create</button>
                <button type="button" class="secondary-button" onclick="this.closest('.mock-modal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

