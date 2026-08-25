<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_employee();

$db = get_db_connection();
$orderId = (int) ($_GET['id'] ?? 0);
if ($orderId <= 0) {
    header('Location: orders.php');
    exit;
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'process') {
        $db->prepare('UPDATE orders SET status = "processing" WHERE order_id = ? AND status = "confirmed"')->execute([$orderId]);
    } elseif ($action === 'dispatch') {
        // Can be dispatched if processing, and payment is cleared or it's COD
        $db->prepare('UPDATE orders SET status = "dispatched", dispatch_date = NOW() WHERE order_id = ? AND status = "processing" AND (payment_status = "cleared" OR payment_method = "pay_on_delivery")')->execute([$orderId]);
    } elseif ($action === 'deliver') {
        $notes = trim($_POST['notes'] ?? '');
        $db->prepare('UPDATE orders SET status = "delivered", delivery_date = CURDATE(), notes = ? WHERE order_id = ? AND status = "dispatched"')->execute([$notes, $orderId]);
    }
    header('Location: order.php?id=' . $orderId);
    exit;
}

// Fetch order
$stmt = $db->prepare('SELECT o.*, c.first_name, c.last_name, u.email, c.phone, c.address, c.city, c.postal_code, c.country 
    FROM orders o 
    INNER JOIN customers c ON c.customer_id = o.customer_id 
    INNER JOIN users u ON u.user_id = c.user_id
    WHERE o.order_id = ?');
$stmt->execute([$orderId]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: orders.php');
    exit;
}

// Fetch items
$itemsStmt = $db->prepare('SELECT oi.*, p.name, p.product_code, p.product_number, p.image_url 
    FROM order_items oi 
    INNER JOIN products p ON p.product_id = oi.product_id 
    WHERE oi.order_id = ?');
$itemsStmt->execute([$orderId]);
$items = $itemsStmt->fetchAll();

$pageTitle = 'Order ' . htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8') . ' - Arts Employee';
$basePath = '/Shopping-Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
require_once dirname(__DIR__) . '/includes/employee-shell.php';

$activePage = 'orders.php';
$employeeNav = [
    'index.php' => 'Dashboard',
    'orders.php' => 'Orders',
    'dispatch.php' => 'Dispatch',
    'delivery.php' => 'Delivery'
];
?>
<main class="employee-app">
    <div class="employee-layout">
        <?php render_employee_sidebar($employeeNav, $activePage, $basePath); ?>
        <section class="employee-main">
            <header class="employee-page-header">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; width: 100%;">
                    <div>
                        <a href="orders.php" class="employee-eyebrow">&larr; Back to Orders</a>
                        <h1>Order <?= htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8') ?></h1>
                        <p>Placed on <?= date('d M Y', strtotime($order['order_date'])) ?></p>
                    </div>
                    <div>
                        <span class="status-badge <?= get_status_badge_class($order['status'], 'order') ?>" style="font-size: 1.1rem; padding: 0.5rem 1rem;"><?= ucfirst(htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8')) ?></span>
                    </div>
                </div>
            </header>

            <section class="employee-work-grid" style="grid-template-columns: 2fr 1fr; margin-bottom: 2rem;">
                <article class="employee-panel">
                    <div class="employee-panel-heading"><h2>Order Items</h2></div>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="admin-product-identity">
                                                <img src="<?= htmlspecialchars($item['image_url'] ?: '/Shopping%20Cart/assets/images/stationery.svg', ENT_QUOTES, 'UTF-8') ?>" alt="">
                                                <span>
                                                    <strong><?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?></strong>
                                                    <small><?= htmlspecialchars($item['product_code'] . $item['product_number'], ENT_QUOTES, 'UTF-8') ?></small>
                                                </span>
                                            </div>
                                        </td>
                                        <td><?= (int) $item['quantity'] ?></td>
                                        <td>$<?= number_format($item['unit_price'], 2) ?></td>
                                        <td>$<?= number_format($item['subtotal'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                                    <td><strong>$<?= number_format($order['total_amount'], 2) ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </article>

                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <!-- Action Panel -->
                    <article class="employee-panel">
                        <div class="employee-panel-heading"><h2>Workflow Actions</h2></div>
                        <div style="padding: 1.5rem;">
                            <?php if ($order['status'] === 'confirmed'): ?>
                                <form method="POST">
                                    <input type="hidden" name="action" value="process">
                                    <p style="margin-bottom: 1rem; color: #4b5563; font-size: 0.9rem;">Order is confirmed and ready to be processed.</p>
                                    <button type="submit" class="primary-button" style="width: 100%;">Process Order</button>
                                </form>
                            <?php elseif ($order['status'] === 'processing'): ?>
                                <?php if ($order['payment_status'] === 'cleared' || $order['payment_method'] === 'pay_on_delivery'): ?>
                                    <form method="POST">
                                        <input type="hidden" name="action" value="dispatch">
                                        <p style="margin-bottom: 1rem; color: #4b5563; font-size: 0.9rem;">Order has been picked and is ready for dispatch.</p>
                                        <button type="submit" class="primary-button" style="width: 100%;">Dispatch Order</button>
                                    </form>
                                <?php else: ?>
                                    <p style="color: #c53030; font-size: 0.9rem;">Cannot dispatch until payment is cleared.</p>
                                    <button class="secondary-button" style="width: 100%;" disabled>Awaiting Payment</button>
                                <?php endif; ?>
                            <?php elseif ($order['status'] === 'dispatched'): ?>
                                <form method="POST">
                                    <input type="hidden" name="action" value="deliver">
                                    <p style="margin-bottom: 1rem; color: #4b5563; font-size: 0.9rem;">Mark order as delivered and record any notes.</p>
                                    <div class="form-group" style="margin-bottom: 1rem;">
                                        <label for="notes">Delivery Notes / Report</label>
                                        <textarea name="notes" id="notes" class="form-input" rows="3" placeholder="e.g. Left at front door..."></textarea>
                                    </div>
                                    <button type="submit" class="primary-button" style="width: 100%;">Mark Delivered</button>
                                </form>
                            <?php else: ?>
                                <p style="color: #4b5563; font-size: 0.9rem;">No actions available for current status.</p>
                            <?php endif; ?>
                        </div>
                    </article>

                    <!-- Details Panel -->
                    <article class="employee-panel">
                        <div class="employee-panel-heading"><h2>Customer & Delivery</h2></div>
                        <div style="padding: 1.5rem; font-size: 0.95rem; line-height: 1.6;">
                            <p><strong>Customer:</strong> <?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($order['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                            <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                            <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 1rem 0;">
                            <p><strong>Address:</strong><br>
                                <?= htmlspecialchars($order['address'], ENT_QUOTES, 'UTF-8') ?><br>
                                <?= htmlspecialchars($order['city'], ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars($order['postal_code'], ENT_QUOTES, 'UTF-8') ?><br>
                                <?= htmlspecialchars($order['country'], ENT_QUOTES, 'UTF-8') ?>
                            </p>
                            <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 1rem 0;">
                            <p><strong>Delivery Type:</strong> <?= ucfirst(htmlspecialchars($order['delivery_type'], ENT_QUOTES, 'UTF-8')) ?></p>
                            <p><strong>Payment Method:</strong> <?= ucfirst(str_replace('_', ' ', htmlspecialchars($order['payment_method'], ENT_QUOTES, 'UTF-8'))) ?></p>
                            <p><strong>Payment Status:</strong> <span class="status-badge <?= get_status_badge_class($order['payment_status'], 'payment') ?>" style="display:inline-block; padding: 0.2rem 0.5rem;"><?= ucfirst(htmlspecialchars($order['payment_status'], ENT_QUOTES, 'UTF-8')) ?></span></p>
                            
                            <?php if ($order['notes']): ?>
                                <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 1rem 0;">
                                <p><strong>Delivery Notes:</strong><br>
                                <?= nl2br(htmlspecialchars($order['notes'], ENT_QUOTES, 'UTF-8')) ?></p>
                            <?php endif; ?>
                        </div>
                    </article>
                </div>
            </section>
        </section>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
