<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_customer();

$pageTitle = 'My Orders - Arts';
$basePath = '/Shopping%20Cart';
$userId = current_user_id();
$customerId = get_customer_id_for_user((int) $userId);

if ($customerId === null) {
    redirect_to($basePath . '/auth/login.php');
}

$profile = get_customer_profile($customerId);
$orders = get_customer_order_history($customerId);
$orderSuccess = $_SESSION['order_success'] ?? null;
unset($_SESSION['order_success']);

// Handle order cancellation
$cancelMessage = '';
$cancelType = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order_id'])) {
    $orderIdToCancel = (int) ($_POST['cancel_order_id'] ?? 0);
    
    if ($orderIdToCancel > 0) {
        if (cancel_order($orderIdToCancel, $customerId)) {
            $cancelMessage = 'Order cancelled successfully.';
            $cancelType = 'success';
            // Refresh orders list
            $orders = get_customer_order_history($customerId);
        } else {
            $cancelMessage = 'Unable to cancel this order. It may have already been dispatched.';
            $cancelType = 'error';
        }
    }
}

// Presentational-only badge mapping (does not affect stored status values)
$badgeClassForStatus = [
    'pending'    => 'ca-badge--warning',
    'confirmed'  => 'ca-badge--info',
    'dispatched' => 'ca-badge--info',
    'delivered'  => 'ca-badge--success',
    'cancelled'  => 'ca-badge--danger',
];
$badgeClassForPayment = [
    'paid'        => 'ca-badge--success',
    'pending'     => 'ca-badge--warning',
    'refunded'    => 'ca-badge--neutral',
    'unpaid'      => 'ca-badge--warning',
    'failed'      => 'ca-badge--danger',
];

require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
?>


<main class="ca-page">
    <div class="container">

        <div class="ca-shell">

            <!-- Customer Navigation Sidebar -->
            <aside class="ca-sidebar">
                <div class="ca-profile">
                    <div class="ca-avatar"><?php if ($profile) { echo htmlspecialchars(strtoupper(substr($profile['first_name'], 0, 1) . substr($profile['last_name'], 0, 1)), ENT_QUOTES, 'UTF-8'); } else { echo 'U'; } ?></div>
                    <div class="ca-profile-info">
                        <strong><?php if ($profile) { echo htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name'], ENT_QUOTES, 'UTF-8'); } else { echo 'User'; } ?></strong>
                        <span><?php if ($profile) { echo htmlspecialchars($profile['email'], ENT_QUOTES, 'UTF-8'); } ?></span>
                    </div>
                </div>
                <nav class="ca-nav">
                    <a href="index.php">Dashboard</a>
                    <a href="orders.php" class="active">My Orders</a>
                    <a href="returns.php">Returns &amp; Replacements</a>
                    <a href="account.php">Account Details</a>
                    <a href="<?= $basePath ?>/auth/logout.php" class="logout-link">Logout</a>
                </nav>
            </aside>

            <!-- Main Content -->
            <div class="ca-content">
                <div class="ca-header">
                    <div>
                        <span class="ca-eyebrow">Order History</span>
                        <h1 class="ca-title">My Orders</h1>
                        <p class="ca-subtitle">Track current orders and review your past purchases.</p>
                    </div>
                </div>

                <?php if ($orderSuccess): ?>
                    <div class="ca-alert ca-alert-success"><?= htmlspecialchars($orderSuccess, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <?php if ($cancelMessage): ?>
                    <div class="ca-alert <?= $cancelType === 'success' ? 'ca-alert-success' : 'ca-alert-error' ?>">
                        <?= htmlspecialchars($cancelMessage, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <div class="ca-orders">
                    <?php if (empty($orders)): ?>
                        <div class="ca-empty">
                            <div class="icon">📦</div>
                            <p><strong>No orders yet</strong></p>
                            <p>Start shopping to place your first order.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <?php
                            $payment = str_replace('_', ' ', $order['payment_status']);
                            $status = str_replace('_', ' ', $order['status']);
                            $canCancel = in_array($order['status'], ['pending', 'confirmed'], true);
                            $paymentBadgeClass = $badgeClassForPayment[$order['payment_status']] ?? 'ca-badge--neutral';
                            $statusBadgeClass = $badgeClassForStatus[$order['status']] ?? 'ca-badge--neutral';
                            ?>
                            <div class="ca-order-card">
                                <div class="ca-order-head">
                                    <div class="ca-order-field">
                                        <span class="label">Order Number</span>
                                        <span class="value">#<?= htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8') ?></span>
                                    </div>
                                    <div class="ca-order-field">
                                        <span class="label">Date Placed</span>
                                        <span class="value"><?= date('d M Y', strtotime($order['order_date'])) ?></span>
                                    </div>
                                    <div class="ca-order-field">
                                        <span class="label">Total Amount</span>
                                        <span class="value">$<?= number_format((float) $order['total_amount'], 2) ?></span>
                                    </div>
                                </div>
                                <div class="ca-order-body">
                                    <div class="ca-order-badges">
                                        <span class="ca-badge <?= $paymentBadgeClass ?>">
                                            Payment: <?= htmlspecialchars(ucfirst($payment), ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                        <span class="ca-badge <?= $statusBadgeClass ?>">
                                            <?= htmlspecialchars(ucfirst($status), ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    </div>
                                    <div class="ca-order-actions">
                                        <button class="order-action-btn" type="button" onclick="alert('Order details view coming soon.');">View Details</button>
                                        <?php if ($canCancel): ?>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="cancel_order_id" value="<?= (int) $order['order_id'] ?>">
                                                <button type="submit" class="cancel-order-btn" onclick="return confirm('Are you sure you want to cancel this order?');">Cancel Order</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            </div>
        </div>

    </div>
</main>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>