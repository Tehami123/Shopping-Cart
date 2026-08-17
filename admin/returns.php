<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_admin();

$pageTitle = 'Manage Returns - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';

$db = get_db_connection();
$activePage = 'returns.php';
$adminNav = [
    'index.php' => 'Dashboard', 'products.php' => 'Products', 'inventory.php' => 'Inventory',
    'orders.php' => 'Orders', 'customers.php' => 'Customers', 'employees.php' => 'Employees',
    'payments.php' => 'Payments', 'returns.php' => 'Returns', 'feedback.php' => 'Feedback', 'faq.php' => 'FAQ'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_return'])) {
    $returnId = (int) ($_POST['return_id'] ?? 0);
    $status = in_array($_POST['status'] ?? '', ['requested', 'approved', 'rejected', 'completed'], true) ? $_POST['status'] : 'requested';
    if ($returnId > 0) {
        $stmt = $db->prepare('UPDATE returns SET status = :status, approved_by = :approved_by, approval_date = NOW() WHERE return_id = :return_id');
        $stmt->execute([
            ':status' => $status,
            ':approved_by' => current_user_id(),
            ':return_id' => $returnId,
        ]);
    }
}

$returns = get_all_returns_for_admin();
?>
<main class="customer-page admin-page">
    <div class="container">
        <div class="customer-layout">
            <aside class="customer-sidebar">
                <div class="customer-profile-brief" style="background: var(--brand-primary-dark); color: white;">
                    <div class="info"><strong style="color:white;">Admin Portal</strong></div>
                </div>
                <nav class="customer-nav">
                    <?php foreach ($adminNav as $url => $label): ?>
                        <a href="<?= $url ?>" <?= $activePage === $url ? 'class="active"' : '' ?>><?= $label ?></a>
                    <?php endforeach; ?>
                    <a href="<?= $basePath ?>/auth/login.php" class="logout-link">Logout</a>
                </nav>
            </aside>
            <div class="customer-content">
                <h1 class="customer-page-title">Manage Returns</h1>

                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Return ID</th><th>Order #</th><th>Customer</th><th>Product</th><th>Type</th><th>Reason</th><th>Date</th><th>Status</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($returns as $return): ?>
                                <tr>
                                    <td>RT-<?= (int) $return['return_id'] ?></td>
                                    <td><?= htmlspecialchars($return['order_number'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($return['customer_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($return['product_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= ucfirst(htmlspecialchars($return['return_type'], ENT_QUOTES, 'UTF-8')) ?></td>
                                    <td><?= htmlspecialchars($return['reason'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= date('d M Y', strtotime($return['request_date'])) ?></td>
                                    <td><span class="status-badge <?= get_status_badge_class($return['status'], 'return') ?>"><?= ucfirst(htmlspecialchars($return['status'], ENT_QUOTES, 'UTF-8')) ?></span></td>
                                    <td class="admin-actions-cell">
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="return_id" value="<?= (int) $return['return_id'] ?>">
                                            <input type="hidden" name="update_return" value="1">
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="secondary-button" style="padding:4px 8px; font-size:0.8rem;">Approve</button>
                                        </form>
                                        <form method="POST" style="display:inline; margin-left:4px;">
                                            <input type="hidden" name="return_id" value="<?= (int) $return['return_id'] ?>">
                                            <input type="hidden" name="update_return" value="1">
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="secondary-button" style="padding:4px 8px; font-size:0.8rem;">Reject</button>
                                        </form>
                                        <form method="POST" style="display:inline; margin-left:4px;">
                                            <input type="hidden" name="return_id" value="<?= (int) $return['return_id'] ?>">
                                            <input type="hidden" name="update_return" value="1">
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="secondary-button" style="padding:4px 8px; font-size:0.8rem;">Complete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

