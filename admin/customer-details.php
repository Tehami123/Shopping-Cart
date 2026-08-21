<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

$customerId = (int)($_GET['id'] ?? 0);
if ($customerId <= 0) {
    header('Location: customers.php');
    exit;
}

$pageTitle = 'Customer Details - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
require_once dirname(__DIR__) . '/includes/admin-shell.php';

$db = get_db_connection();
$activePage = 'customers.php';
$adminNav = [
    'index.php' => 'Dashboard', 'products.php' => 'Products', 'inventory.php' => 'Inventory',
    'orders.php' => 'Orders', 'customers.php' => 'Customers', 'employees.php' => 'Employees',
    'payments.php' => 'Payments', 'returns.php' => 'Returns', 'feedback.php' => 'Feedback', 'faq.php' => 'FAQ'
];

$stmt = $db->prepare('
    SELECT c.*, u.email, u.status, u.created_at as user_created_at
    FROM customers c
    INNER JOIN users u ON u.user_id = c.user_id
    WHERE c.customer_id = :customer_id
');
$stmt->execute([':customer_id' => $customerId]);
$customer = $stmt->fetch();

if (!$customer) {
    echo "<main class='admin-app'><div class='admin-layout'><section class='admin-main'><h2>Customer not found</h2><a href='customers.php'>Back to Customers</a></section></div></main>";
    require_once dirname(__DIR__) . '/includes/footer.php';
    exit;
}

// Fetch order history
$stmtOrders = $db->prepare('
    SELECT order_id, order_number, order_date, total_amount, status, payment_status, delivery_type
    FROM orders
    WHERE customer_id = :customer_id
    ORDER BY order_date DESC
');
$stmtOrders->execute([':customer_id' => $customerId]);
$orders = $stmtOrders->fetchAll();

$orderCount = count($orders);
$totalSpent = array_reduce($orders, fn($sum, $o) => $sum + $o['total_amount'], 0);
?>
<main class="admin-app">
    <div class="admin-layout">
        <?php render_admin_sidebar($adminNav, $activePage, $basePath); ?>
        <section class="admin-main">
            <?php render_admin_page_header('Customer Details', 'Detailed view for ' . htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name'], ENT_QUOTES, 'UTF-8'), 'Customer Overview'); ?>
            
            <div style="margin-bottom: 20px;">
                <a href="customers.php" class="secondary-button" style="text-decoration:none;">&larr; Back to Customers</a>
            </div>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div class="auth-card" style="margin:0; width:auto; max-width:100%;">
                    <h3 style="margin-top:0;">Account Information</h3>
                    <p><strong>Name:</strong> <?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($customer['email'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($customer['email'], ENT_QUOTES, 'UTF-8') ?></a></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($customer['phone'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p>
                        <strong>Account Status:</strong> 
                        <span class="status-badge <?= $customer['status'] === 'active' ? 'status-delivered' : 'status-cancelled' ?>"><?= ucfirst(htmlspecialchars($customer['status'], ENT_QUOTES, 'UTF-8')) ?></span>
                    </p>
                    <p><strong>Registered Date:</strong> <?= date('d M Y, h:i A', strtotime($customer['user_created_at'])) ?></p>
                </div>

                <div class="auth-card" style="margin:0; width:auto; max-width:100%;">
                    <h3 style="margin-top:0;">Billing / Shipping Address</h3>
                    <address style="font-style:normal; line-height:1.6;">
                        <?= htmlspecialchars($customer['address'], ENT_QUOTES, 'UTF-8') ?><br>
                        <?= htmlspecialchars($customer['city'], ENT_QUOTES, 'UTF-8') ?><br>
                        <?= htmlspecialchars($customer['postal_code'], ENT_QUOTES, 'UTF-8') ?><br>
                        <?= htmlspecialchars($customer['country'], ENT_QUOTES, 'UTF-8') ?>
                    </address>

                    <hr style="margin: 15px 0; border: 0; border-top: 1px solid #e2e8f0;">

                    <h3 style="margin-top:0;">Order Summary</h3>
                    <p><strong>Total Orders:</strong> <?= $orderCount ?></p>
                    <p><strong>Total Spent:</strong> <?= format_currency((float)$totalSpent) ?></p>
                </div>
            </div>

            <h3 style="margin-top:30px; margin-bottom:15px;">Order History</h3>
            <?php if ($orderCount > 0): ?>
            <div class="table-responsive" style="background:white; border-radius:8px; border:1px solid #e2e8f0;">
                <table class="admin-table" style="margin:0; border:none;">
                    <thead>
                        <tr>
                            <th style="border-top:none; border-left:none;">Order #</th>
                            <th style="border-top:none;">Date</th>
                            <th style="border-top:none;">Total</th>
                            <th style="border-top:none;">Payment</th>
                            <th style="border-top:none;">Status</th>
                            <th style="border-top:none;">Delivery</th>
                            <th style="border-top:none; border-right:none; text-align:right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td style="border-left:none;"><a href="order-details.php?id=<?= (int)$order['order_id'] ?>" style="text-decoration:none; color:inherit;"><strong class="admin-primary-cell"><?= htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8') ?></strong></a></td>
                                <td><?= date('d M Y', strtotime($order['order_date'])) ?></td>
                                <td><?= format_currency((float) $order['total_amount']) ?></td>
                                <td><span class="status-badge <?= get_status_badge_class($order['payment_status'], 'payment') ?>"><?= ucfirst(htmlspecialchars($order['payment_status'], ENT_QUOTES, 'UTF-8')) ?></span></td>
                                <td><span class="status-badge <?= get_status_badge_class($order['status'], 'order') ?>"><?= ucfirst(htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8')) ?></span></td>
                                <td><?= ucfirst(htmlspecialchars($order['delivery_type'], ENT_QUOTES, 'UTF-8')) ?></td>
                                <td style="border-right:none; text-align:right;"><a href="order-details.php?id=<?= (int)$order['order_id'] ?>" class="text-button" style="text-decoration:none;">View</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <p>This customer has not placed any orders yet.</p>
            <?php endif; ?>

        </section>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
