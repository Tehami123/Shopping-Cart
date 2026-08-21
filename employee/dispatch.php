<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_employee();

$pageTitle = 'Dispatch Orders - Arts Employee';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
require_once dirname(__DIR__) . '/includes/employee-shell.php';

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
        $db->prepare('UPDATE orders SET status = :status, dispatch_date = NOW() WHERE order_id = :order_id AND status = "processing" AND (payment_status = "cleared" OR payment_method = "pay_on_delivery")')->execute([
            ':status' => 'dispatched',
            ':order_id' => $orderId,
        ]);
    }
}

$orders = $db->query(
    'SELECT o.*, CONCAT(c.first_name, " ", c.last_name) AS customer_name FROM orders o INNER JOIN customers c ON c.customer_id = o.customer_id WHERE o.status = "processing" OR (o.status = "dispatched" AND (o.payment_status = "cleared" OR o.payment_method = "pay_on_delivery")) ORDER BY o.order_id DESC'
)->fetchAll();
?>
<main class="employee-app">
    <div class="employee-layout">
        <?php render_employee_sidebar($employeeNav, $activePage, $basePath); ?>
        <section class="employee-main">
            <?php render_employee_page_header('Dispatch', 'Confirm payment readiness, then move eligible orders into delivery.', 'Fulfillment workspace'); ?>
                <div class="policy-notice" style="border-left-color:#dd6b20; background:#feebc8; color:#7b341e;">
                    <p><strong>Note:</strong> Credit Card/Cheque orders cannot be dispatched until payment is cleared. Pay on Delivery is exempt.</p>
                </div>

                <?php if (empty($orders)): ?>
                    <div class="employee-empty-state"><span class="employee-empty-mark">D</span><h2>Dispatch queue is clear</h2><p>There are currently no orders waiting for dispatch.</p><a href="orders.php" class="secondary-button">View all orders</a></div>
                <?php else: ?>
                <div class="employee-workflow-list">
                    <?php foreach ($orders as $order): ?>
                        <article class="employee-workflow-card">
                            <div class="employee-workflow-main"><span class="employee-workflow-label">Order</span><strong><?= htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8') ?></strong><span><?= htmlspecialchars($order['customer_name'], ENT_QUOTES, 'UTF-8') ?></span></div>
                            <div class="employee-workflow-meta"><span><small>Delivery</small><?= ucfirst(htmlspecialchars($order['delivery_type'], ENT_QUOTES, 'UTF-8')) ?></span><span><small>Payment</small><span class="status-badge <?= get_status_badge_class($order['payment_status'], 'payment') ?>"><?= ucfirst(htmlspecialchars($order['payment_status'], ENT_QUOTES, 'UTF-8')) ?></span></span><span><small>Status</small><span class="status-badge <?= get_status_badge_class($order['status'], 'order') ?>"><?= ucfirst(htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8')) ?></span></span></div>
                            <div class="employee-workflow-action">
                                <?php if (($order['payment_status'] === 'cleared' || $order['payment_method'] === 'pay_on_delivery') && $order['status'] === 'processing'): ?>
                                    <form method="POST"><input type="hidden" name="dispatch_order" value="1"><input type="hidden" name="order_id" value="<?= (int) $order['order_id'] ?>"><button type="submit" class="primary-button">Dispatch order</button></form>
                                <?php else: ?>
                                    <a href="order.php?id=<?= (int) $order['order_id'] ?>" class="secondary-button">View Details</a>
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

