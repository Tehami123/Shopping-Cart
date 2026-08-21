<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_employee();

$pageTitle = 'Deliveries - Arts Employee';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
require_once dirname(__DIR__) . '/includes/employee-shell.php';

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
<main class="employee-app">
    <div class="employee-layout">
        <?php render_employee_sidebar($employeeNav, $activePage, $basePath); ?>
        <section class="employee-main">
            <?php render_employee_page_header('Delivery', 'Keep dispatched orders current and mark completed deliveries as they arrive.', 'Delivery workspace'); ?>

                <?php if (empty($orders)): ?>
                    <div class="employee-empty-state"><span class="employee-empty-mark">&gt;</span><h2>No delivery updates</h2><p>Dispatched orders will appear here when they are ready to be delivered.</p><a href="dispatch.php" class="secondary-button">Open dispatch</a></div>
                <?php else: ?>
                <div class="employee-workflow-list">
                    <?php foreach ($orders as $order): ?>
                        <article class="employee-workflow-card <?= $order['status'] === 'dispatched' ? 'is-actionable' : 'is-complete' ?>">
                            <div class="employee-workflow-main"><span class="employee-workflow-label">Order</span><strong><?= htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8') ?></strong><span><?= htmlspecialchars($order['customer_name'], ENT_QUOTES, 'UTF-8') ?></span></div>
                            <div class="employee-workflow-meta"><span><small>Dispatched</small><?= $order['dispatch_date'] ? date('d M Y', strtotime($order['dispatch_date'])) : 'Not recorded' ?></span><span><small>Delivery</small><?= ucfirst(htmlspecialchars($order['delivery_type'], ENT_QUOTES, 'UTF-8')) ?></span><span><small>Status</small><span class="status-badge <?= get_status_badge_class($order['status'], 'order') ?>"><?= ucfirst(htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8')) ?></span></span></div>
                            <div class="employee-workflow-action">
                                <?php if ($order['status'] === 'dispatched'): ?>
                                    <form method="POST"><input type="hidden" name="deliver_order" value="1"><input type="hidden" name="order_id" value="<?= (int) $order['order_id'] ?>"><button type="submit" class="primary-button">Mark delivered</button></form>
                                <?php else: ?>
                                    <span class="employee-complete-label">Completed</span>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
        </section>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

