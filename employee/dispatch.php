<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_employee();

$pageTitle = 'Dispatch Orders - Arts Employee';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';

$db = get_db_connection();
$activePage = 'dispatch.php';
$employeeNav = [
    'index.php' => 'Dashboard',
    'orders.php' => 'Orders',
    'dispatch.php' => 'Dispatch',
    'delivery.php' => 'Delivery'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dispatch_order'])) {
    $orderId = (int) ($_POST['order_id'] ?? 0);
    if ($orderId > 0) {
        $db->prepare('UPDATE orders SET status = :status, dispatch_date = NOW() WHERE order_id = :order_id AND status IN ("pending", "confirmed") AND payment_status = "cleared"')->execute([
            ':status' => 'dispatched',
            ':order_id' => $orderId,
        ]);
    }
}

$orders = $db->query(
    'SELECT o.*, CONCAT(c.first_name, " ", c.last_name) AS customer_name FROM orders o INNER JOIN customers c ON c.customer_id = o.customer_id WHERE o.status IN ("pending", "confirmed") OR (o.status = "dispatched" AND o.payment_status = "cleared") ORDER BY o.order_id DESC'
)->fetchAll();
?>
<main class="customer-page employee-page">
    <div class="container">
        <div class="customer-layout">
            <aside class="customer-sidebar">
                <div class="customer-profile-brief" style="background: #2b6cb0; color: white;">
                    <div class="info"><strong style="color:white;">Employee Portal</strong></div>
                </div>
                <nav class="customer-nav">
                    <?php foreach ($employeeNav as $url => $label): ?>
                        <a href="<?= $url ?>" <?= $activePage === $url ? 'class="active"' : '' ?>><?= $label ?></a>
                    <?php endforeach; ?>
                    <a href="<?= $basePath ?>/auth/login.php" class="logout-link">Logout</a>
                </nav>
            </aside>
            <div class="customer-content">
                <h1 class="customer-page-title">Dispatch Management</h1>
                <div class="policy-notice" style="border-left-color:#dd6b20; background:#feebc8; color:#7b341e;">
                    <p><strong>Note:</strong> Credit Card/Cheque orders cannot be dispatched until payment is cleared.</p>
                </div>

                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Order #</th><th>Customer</th><th>Payment</th><th>Type</th><th>Status</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?= htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($order['customer_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><span class="status-badge <?= get_status_badge_class($order['payment_status'], 'payment') ?>"><?= ucfirst(htmlspecialchars($order['payment_status'], ENT_QUOTES, 'UTF-8')) ?></span></td>
                                    <td><?= ucfirst(htmlspecialchars($order['delivery_type'], ENT_QUOTES, 'UTF-8')) ?></td>
                                    <td><span class="status-badge <?= get_status_badge_class($order['status'], 'order') ?>"><?= ucfirst(htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8')) ?></span></td>
                                    <td>
                                        <?php if ($order['payment_status'] === 'cleared' && !in_array($order['status'], ['dispatched', 'delivered'], true)): ?>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="dispatch_order" value="1">
                                                <input type="hidden" name="order_id" value="<?= (int) $order['order_id'] ?>">
                                                <button type="submit" class="secondary-button" style="padding:4px 8px; font-size:0.8rem;">Dispatch Order</button>
                                            </form>
                                        <?php else: ?>
                                            <button class="secondary-button" style="padding:4px 8px; font-size:0.8rem; opacity:0.5; cursor:not-allowed;" disabled>Wait for Payment</button>
                                        <?php endif; ?>
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

