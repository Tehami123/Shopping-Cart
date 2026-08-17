<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_employee();

$pageTitle = 'Deliveries - Arts Employee';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';

$db = get_db_connection();
$activePage = 'delivery.php';
$employeeNav = [
    'index.php' => 'Dashboard',
    'orders.php' => 'Orders',
    'dispatch.php' => 'Dispatch',
    'delivery.php' => 'Delivery'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deliver_order'])) {
    $orderId = (int) ($_POST['order_id'] ?? 0);
    if ($orderId > 0) {
        $db->prepare('UPDATE orders SET status = :status, delivery_date = CURDATE() WHERE order_id = :order_id AND status = "dispatched"')->execute([
            ':status' => 'delivered',
            ':order_id' => $orderId,
        ]);
    }
}

$orders = $db->query('SELECT o.*, CONCAT(c.first_name, " ", c.last_name) AS customer_name FROM orders o INNER JOIN customers c ON c.customer_id = o.customer_id WHERE o.status IN ("dispatched", "delivered") ORDER BY o.order_id DESC')->fetchAll();
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
                <h1 class="customer-page-title">Delivery Updates</h1>

                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Order #</th><th>Customer</th><th>Dispatch Date</th><th>Type</th><th>Delivery Status</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?= htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($order['customer_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= $order['dispatch_date'] ? date('d M Y', strtotime($order['dispatch_date'])) : '—' ?></td>
                                    <td><?= ucfirst(htmlspecialchars($order['delivery_type'], ENT_QUOTES, 'UTF-8')) ?></td>
                                    <td><span class="status-badge <?= get_status_badge_class($order['status'], 'order') ?>"><?= ucfirst(htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8')) ?></span></td>
                                    <td>
                                        <?php if ($order['status'] === 'dispatched'): ?>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="deliver_order" value="1">
                                                <input type="hidden" name="order_id" value="<?= (int) $order['order_id'] ?>">
                                                <button type="submit" class="primary-button" style="padding:4px 12px; font-size:0.8rem; height:auto;">Mark Delivered</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="status-badge status-delivered">Delivered</span>
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

