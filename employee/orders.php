<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_employee();

$pageTitle = 'Orders - Arts Employee';
$basePath = '/Shopping-Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
require_once dirname(__DIR__) . '/includes/employee-shell.php';

$db = get_db_connection();
$activePage = 'orders.php';
$employeeNav = [
    'index.php' => 'Dashboard',
    'orders.php' => 'Orders',
    'dispatch.php' => 'Dispatch',
    'delivery.php' => 'Delivery'
];

$stmt = $db->query('SELECT o.*, CONCAT(c.first_name, " ", c.last_name) AS customer_name FROM orders o INNER JOIN customers c ON c.customer_id = o.customer_id ORDER BY o.order_id DESC');
$orders = $stmt->fetchAll();
?>
<main class="employee-app">
    <div class="employee-layout">
        <?php render_employee_sidebar($employeeNav, $activePage, $basePath); ?>
        <section class="employee-main">
            <?php render_employee_page_header('Orders', 'The complete order queue for the operations team, with status and payment context at a glance.', 'Order workspace'); ?>

                <?php if (empty($orders)): ?>
                    <div class="employee-empty-state"><span class="employee-empty-mark">O</span><h2>No orders in the queue</h2><p>Orders will appear here when customers complete checkout.</p></div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Order</th><th>Customer</th><th>Date</th><th>Payment</th><th>Status</th><th>Delivery</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><a href="order.php?id=<?= (int) $order['order_id'] ?>" style="text-decoration:none;"><strong class="employee-primary-cell" style="color:var(--c-primary);"><?= htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8') ?></strong><small class="employee-table-muted">Order <?= (int) $order['order_id'] ?></small></a></td>
                                    <td><strong><?= htmlspecialchars($order['customer_name'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                                    <td><?= date('d M Y', strtotime($order['order_date'])) ?></td>
                                    <td><span class="status-badge <?= get_status_badge_class($order['payment_status'], 'payment') ?>"><?= ucfirst(htmlspecialchars($order['payment_status'], ENT_QUOTES, 'UTF-8')) ?></span></td>
                                    <td><span class="status-badge <?= get_status_badge_class($order['status'], 'order') ?>"><?= ucfirst(htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8')) ?></span></td>
                                    <td><?= ucfirst(htmlspecialchars($order['delivery_type'], ENT_QUOTES, 'UTF-8')) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
        </section>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

