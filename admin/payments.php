<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

$pageTitle = 'Payments - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';

$db = get_db_connection();
$activePage = 'payments.php';
$adminNav = [
    'index.php' => 'Dashboard', 'products.php' => 'Products', 'inventory.php' => 'Inventory',
    'orders.php' => 'Orders', 'customers.php' => 'Customers', 'employees.php' => 'Employees',
    'payments.php' => 'Payments', 'returns.php' => 'Returns', 'feedback.php' => 'Feedback', 'faq.php' => 'FAQ'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_action'])) {
    $orderId = (int) ($_POST['order_id'] ?? 0);
    $status = in_array($_POST['payment_status'] ?? '', ['pending', 'cleared', 'failed'], true) ? $_POST['payment_status'] : 'pending';
    if ($orderId > 0) {
        $db->prepare('UPDATE orders SET payment_status = :payment_status WHERE order_id = :order_id')->execute([
            ':payment_status' => $status,
            ':order_id' => $orderId,
        ]);
    }
}

$orders = $db->query(
    'SELECT o.*, CONCAT(c.first_name, " ", c.last_name) AS customer_name FROM orders o INNER JOIN customers c ON c.customer_id = o.customer_id ORDER BY o.order_id DESC'
)->fetchAll();
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
                <h1 class="customer-page-title">Payment Verification</h1>

                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Order #</th><th>Customer</th><th>Method</th><th>Amount</th><th>Status</th><th>Date</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?= htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($order['customer_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= ucfirst(str_replace('_', ' ', htmlspecialchars($order['payment_method'], ENT_QUOTES, 'UTF-8'))) ?></td>
                                    <td><?= format_currency((float) $order['total_amount']) ?></td>
                                    <td><span class="status-badge <?= get_status_badge_class($order['payment_status'], 'payment') ?>"><?= ucfirst(htmlspecialchars($order['payment_status'], ENT_QUOTES, 'UTF-8')) ?></span></td>
                                    <td><?= date('d M Y', strtotime($order['order_date'])) ?></td>
                                    <td class="admin-actions-cell">
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="order_id" value="<?= (int) $order['order_id'] ?>">
                                            <input type="hidden" name="payment_action" value="1">
                                            <input type="hidden" name="payment_status" value="cleared">
                                            <button type="submit" class="secondary-button" style="padding:4px 8px; font-size:0.8rem;">Mark as Cleared</button>
                                        </form>
                                        <form method="POST" style="display:inline; margin-left:6px;">
                                            <input type="hidden" name="order_id" value="<?= (int) $order['order_id'] ?>">
                                            <input type="hidden" name="payment_action" value="1">
                                            <input type="hidden" name="payment_status" value="failed">
                                            <button type="submit" class="secondary-button" style="padding:4px 8px; font-size:0.8rem;">Mark as Failed</button>
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

