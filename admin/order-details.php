<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

$orderId = (int)($_GET['id'] ?? 0);
if ($orderId <= 0) {
    header('Location: orders.php');
    exit;
}

$pageTitle = 'Order Details - Arts';
$basePath = '/Shopping%20Cart';
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_order_status'])) {
    $status = $_POST['status'] ?? 'pending';
    if (in_array($status, ['pending', 'confirmed', 'processing', 'dispatched', 'delivered', 'cancelled'], true)) {
        $db->prepare('UPDATE orders SET status = :status WHERE order_id = :order_id')->execute([
            ':status' => $status,
            ':order_id' => $orderId,
        ]);
        header('Location: order-details.php?id=' . $orderId);
        exit;
    }
}

$stmt = $db->prepare('
    SELECT o.*, 
           c.first_name, c.last_name, c.phone, c.address, c.city, c.postal_code, c.country,
           u.email 
    FROM orders o 
    INNER JOIN customers c ON o.customer_id = c.customer_id
    INNER JOIN users u ON c.user_id = u.user_id
    WHERE o.order_id = :order_id
');
$stmt->execute([':order_id' => $orderId]);
$order = $stmt->fetch();

if (!$order) {
    echo "<main class='admin-app'><div class='admin-layout'><section class='admin-main'><h2>Order not found</h2><a href='orders.php'>Back to Orders</a></section></div></main>";
    require_once dirname(__DIR__) . '/includes/footer.php';
    exit;
}

$stmtItems = $db->prepare('
    SELECT oi.*, p.name as product_name, p.full_product_id, p.image_url
    FROM order_items oi
    INNER JOIN products p ON oi.product_id = p.product_id
    WHERE oi.order_id = :order_id
');
$stmtItems->execute([':order_id' => $orderId]);
$items = $stmtItems->fetchAll();

?>
<main class="admin-app">
    <div class="admin-layout">
        <?php render_admin_sidebar($adminNav, $activePage, $basePath); ?>
        <section class="admin-main">
            <?php render_admin_page_header('Order Details', 'Detailed view for Order #' . htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8'), 'Order Overview'); ?>
            
            <div style="margin-bottom: 20px;">
                <a href="orders.php" class="secondary-button" style="text-decoration:none;">&larr; Back to Orders</a>
            </div>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div class="auth-card" style="margin:0; width:auto; max-width:100%;">
                    <h3 style="margin-top:0;">Order Information</h3>
                    <p><strong>Order Number:</strong> <?= htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p><strong>Order Date:</strong> <?= date('d M Y, h:i A', strtotime($order['order_date'])) ?></p>
                    <p><strong>Total Amount:</strong> <?= format_currency((float) $order['total_amount']) ?></p>
                    <p>
                        <strong>Order Status:</strong> 
                        <span class="status-badge <?= get_status_badge_class($order['status'], 'order') ?>"><?= ucfirst(htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8')) ?></span>
                    </p>
                    
                    <hr style="margin: 15px 0; border: 0; border-top: 1px solid #e2e8f0;">
                    
                    <p><strong>Delivery Method:</strong> <?= ucfirst(htmlspecialchars($order['delivery_type'], ENT_QUOTES, 'UTF-8')) ?></p>
                    <p><strong>Dispatch Date:</strong> <?= $order['dispatch_date'] ? date('d M Y, h:i A', strtotime($order['dispatch_date'])) : 'Not dispatched yet' ?></p>
                    <p><strong>Delivery Date:</strong> <?= $order['delivery_date'] ? date('d M Y', strtotime($order['delivery_date'])) : 'Not delivered yet' ?></p>
                    <p><strong>Delivery Notes:</strong> <?= nl2br(htmlspecialchars((string)$order['notes'], ENT_QUOTES, 'UTF-8')) ?: 'None' ?></p>
                    
                    <hr style="margin: 15px 0; border: 0; border-top: 1px solid #e2e8f0;">
                    
                    <p><strong>Payment Method:</strong> <?= ucwords(str_replace('_', ' ', htmlspecialchars($order['payment_method'], ENT_QUOTES, 'UTF-8'))) ?></p>
                    <p>
                        <strong>Payment Status:</strong> 
                        <span class="status-badge <?= get_status_badge_class($order['payment_status'], 'payment') ?>"><?= ucfirst(htmlspecialchars($order['payment_status'], ENT_QUOTES, 'UTF-8')) ?></span>
                    </p>

                    <form method="POST" style="margin-top:20px; padding:15px; background:#f8fafc; border-radius:8px; border: 1px solid #e2e8f0;">
                        <h4 style="margin-top:0; margin-bottom:10px;">Update Order Status</h4>
                        <div style="display:flex; gap:10px; align-items:center;">
                            <input type="hidden" name="order_id" value="<?= (int) $order['order_id'] ?>">
                            <select name="status" class="form-select" style="min-width:150px; flex-grow:1;">
                                <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="confirmed" <?= $order['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                                <option value="dispatched" <?= $order['status'] === 'dispatched' ? 'selected' : '' ?>>Dispatched</option>
                                <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                            <button type="submit" name="admin_order_status" value="1" class="primary-button">Update Status</button>
                        </div>
                    </form>
                </div>

                <div class="auth-card" style="margin:0; width:auto; max-width:100%;">
                    <h3 style="margin-top:0;">Customer Information</h3>
                    <p><strong>Name:</strong> <?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($order['email'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($order['email'], ENT_QUOTES, 'UTF-8') ?></a></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone'], ENT_QUOTES, 'UTF-8') ?></p>
                    
                    <hr style="margin: 15px 0; border: 0; border-top: 1px solid #e2e8f0;">
                    
                    <h4 style="margin-top:0;">Shipping Address</h4>
                    <address style="font-style:normal; line-height:1.6;">
                        <?= htmlspecialchars($order['address'], ENT_QUOTES, 'UTF-8') ?><br>
                        <?= htmlspecialchars($order['city'], ENT_QUOTES, 'UTF-8') ?><br>
                        <?= htmlspecialchars($order['postal_code'], ENT_QUOTES, 'UTF-8') ?><br>
                        <?= htmlspecialchars($order['country'], ENT_QUOTES, 'UTF-8') ?>
                    </address>
                </div>
            </div>

            <h3 style="margin-top:30px; margin-bottom:15px;">Order Items</h3>
            <div class="table-responsive" style="background:white; border-radius:8px; border:1px solid #e2e8f0;">
                <table class="admin-table" style="margin:0; border:none;">
                    <thead>
                        <tr>
                            <th style="border-top:none; border-left:none;">Product</th>
                            <th style="border-top:none;">SKU</th>
                            <th style="border-top:none; text-align:right;">Unit Price</th>
                            <th style="border-top:none; text-align:center;">Quantity</th>
                            <th style="border-top:none; border-right:none; text-align:right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td style="border-left:none;">
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <?php if ($item['image_url']): ?>
                                            <img src="<?= htmlspecialchars($item['image_url'], ENT_QUOTES, 'UTF-8') ?>" alt="" style="width:48px; height:48px; object-fit:cover; border-radius:6px; border:1px solid #e2e8f0;">
                                        <?php endif; ?>
                                        <strong style="color:#1e293b;"><?= htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8') ?></strong>
                                    </div>
                                </td>
                                <td><span style="background:#f1f5f9; padding:2px 6px; border-radius:4px; font-size:0.85rem; color:#475569;"><?= htmlspecialchars($item['full_product_id'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                <td style="text-align:right; color:#475569;"><?= format_currency((float) $item['unit_price']) ?></td>
                                <td style="text-align:center; font-weight:600; color:#1e293b;"><?= (int) $item['quantity'] ?></td>
                                <td style="border-right:none; text-align:right;"><strong style="color:#1e293b;"><?= format_currency((float) $item['subtotal']) ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr style="background:#f8fafc;">
                            <td colspan="4" style="text-align:right; border-left:none; border-bottom:none;"><strong>Total:</strong></td>
                            <td style="border-right:none; border-bottom:none; text-align:right;"><strong style="font-size:1.15rem; color:#0f172a;"><?= format_currency((float) $order['total_amount']) ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </section>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
