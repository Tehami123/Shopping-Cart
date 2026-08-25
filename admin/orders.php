<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

$pageTitle = 'Manage Orders - Arts';
$basePath = '/Shopping-Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
require_once dirname(__DIR__) . '/includes/admin-shell.php';

$db = get_db_connection();
$activePage = 'orders.php';
$adminNav = [
    'index.php' => 'Dashboard', 'products.php' => 'Products', 'inventory.php' => 'Inventory',
    'orders.php' => 'Orders', 'customers.php' => 'Customers', 'employees.php' => 'Employees',
    'payments.php' => 'Payments', 'returns.php' => 'Returns', 'feedback.php' => 'Feedback', 'faq.php' => 'FAQ'
];

$statusFilter = strtolower(trim((string) ($_GET['status'] ?? 'all')));
$deliveryFilter = strtolower(trim((string) ($_GET['delivery'] ?? 'all')));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_order_status'])) {
    $orderId = (int) ($_POST['order_id'] ?? 0);
    $status = $_POST['status'] ?? 'pending';
    if ($orderId > 0 && in_array($status, ['pending', 'confirmed', 'dispatched', 'delivered', 'cancelled'], true)) {
        $db->prepare('UPDATE orders SET status = :status WHERE order_id = :order_id')->execute([
            ':status' => $status,
            ':order_id' => $orderId,
        ]);
    }
}

$sql = 'SELECT o.*, CONCAT(c.first_name, " ", c.last_name) AS customer_name FROM orders o INNER JOIN customers c ON c.customer_id = o.customer_id';
$params = [];
if ($statusFilter !== '' && $statusFilter !== 'all') { $sql .= ' WHERE o.status = :status'; $params[':status'] = $statusFilter; }
if ($deliveryFilter !== '' && $deliveryFilter !== 'all') { $sql .= (empty($params) ? ' WHERE' : ' AND') . ' o.delivery_type = :delivery_type'; $params[':delivery_type'] = $deliveryFilter; }
$sql .= ' ORDER BY o.order_id DESC';
$stmt = $db->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll();
?>
<main class="admin-app">
    <div class="admin-layout">
        <?php render_admin_sidebar($adminNav, $activePage, $basePath); ?>
        <section class="admin-main">
            <?php render_admin_page_header('Orders', 'Track fulfillment, payment readiness, and delivery progress from one workspace.', 'Fulfillment workspace'); ?>

                <div class="admin-filter-bar">
                    <form method="GET" style="display:flex; gap:16px; align-items:end; flex-wrap:wrap;">
                        <div class="form-group" style="margin:0;">
                            <label>Filter Status</label>
                            <select name="status" class="form-select">
                                <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>All</option>
                                <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="confirmed" <?= $statusFilter === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                <option value="dispatched" <?= $statusFilter === 'dispatched' ? 'selected' : '' ?>>Dispatched</option>
                                <option value="delivered" <?= $statusFilter === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="cancelled" <?= $statusFilter === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label>Delivery Type</label>
                            <select name="delivery" class="form-select">
                                <option value="all" <?= $deliveryFilter === 'all' ? 'selected' : '' ?>>All</option>
                                <option value="standard" <?= $deliveryFilter === 'standard' ? 'selected' : '' ?>>Standard</option>
                                <option value="express" <?= $deliveryFilter === 'express' ? 'selected' : '' ?>>Express</option>
                                <option value="pickup" <?= $deliveryFilter === 'pickup' ? 'selected' : '' ?>>Pickup</option>
                            </select>
                        </div>
                        <button type="submit" class="primary-button">Apply Filters</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Order #</th><th>Customer</th><th>Date</th><th>Total</th><th>Payment Status</th><th>Order Status</th><th>Delivery</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><a href="order-details.php?id=<?= (int) $order['order_id'] ?>" style="text-decoration:none; color:inherit;"><strong class="admin-primary-cell"><?= htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8') ?></strong></a><br><small class="admin-table-muted">Order <?= (int) $order['order_id'] ?></small></td>
                                    <td><strong><?= htmlspecialchars($order['customer_name'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                                    <td><?= date('d M Y', strtotime($order['order_date'])) ?></td>
                                    <td><?= format_currency((float) $order['total_amount']) ?></td>
                                    <td><span class="status-badge <?= get_status_badge_class($order['payment_status'], 'payment') ?>"><?= ucfirst(htmlspecialchars($order['payment_status'], ENT_QUOTES, 'UTF-8')) ?></span></td>
                                    <td><span class="status-badge <?= get_status_badge_class($order['status'], 'order') ?>"><?= ucfirst(htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8')) ?></span></td>
                                    <td><?= ucfirst(htmlspecialchars($order['delivery_type'], ENT_QUOTES, 'UTF-8')) ?></td>
                                    <td>
                                        <form method="POST" style="display:flex; gap:8px;">
                                            <input type="hidden" name="order_id" value="<?= (int) $order['order_id'] ?>">
                                            <select name="status" class="form-select" style="min-width:120px;">
                                                <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                <option value="confirmed" <?= $order['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                                <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                                                <option value="dispatched" <?= $order['status'] === 'dispatched' ? 'selected' : '' ?>>Dispatched</option>
                                                <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                                <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                            </select>
                                            <button type="submit" name="admin_order_status" value="1" class="secondary-button" style="padding:4px 8px; font-size:0.8rem;">Update</button>
                                            <a href="order-details.php?id=<?= (int) $order['order_id'] ?>" class="primary-button" style="padding:4px 8px; font-size:0.8rem; text-decoration:none;">View Details</a>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
        </section>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

