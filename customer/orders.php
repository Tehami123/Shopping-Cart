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

require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap');

/* Premium Aesthetics for Customer Dashboard */
.customer-page {
    font-family: 'Outfit', sans-serif;
    background: #fdfcff;
    position: relative;
    overflow-x: hidden;
    padding: 40px 0 80px;
    min-height: calc(100vh - 200px);
}

.customer-page::before {
    content: '';
    position: absolute;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(95,51,168,0.06) 0%, transparent 70%);
    top: -100px;
    left: -100px;
    border-radius: 50%;
    filter: blur(60px);
    z-index: 0;
    pointer-events: none;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.customer-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 40px;
    position: relative;
    z-index: 1;
}

/* Sidebar */
.customer-sidebar {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,1);
    box-shadow: 0 15px 35px rgba(0,0,0,0.03);
    padding: 30px 20px;
    align-self: start;
    animation: fadeInUp 0.6s ease-out both;
}

.customer-profile-brief {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.customer-profile-brief .avatar {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, var(--brand-primary), #7344be);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    font-weight: 700;
    box-shadow: 0 8px 16px rgba(95, 51, 168, 0.2);
}

.customer-profile-brief .info strong {
    display: block;
    font-size: 1.15rem;
    color: #1a1a1a;
    font-weight: 600;
}

.customer-profile-brief .info span {
    font-family: 'Inter', sans-serif;
    font-size: 0.9rem;
    color: var(--text-soft);
}

.customer-nav {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.customer-nav a {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    border-radius: 12px;
    color: var(--text-soft);
    font-weight: 500;
    font-family: 'Inter', sans-serif;
    text-decoration: none;
    transition: all 0.3s ease;
}

.customer-nav a:hover {
    background: rgba(95, 51, 168, 0.04);
    color: var(--brand-primary);
}

.customer-nav a.active {
    background: var(--brand-primary);
    color: #fff;
    box-shadow: 0 4px 12px rgba(95, 51, 168, 0.2);
}

.customer-nav a.logout-link {
    color: #e53935;
    margin-top: 20px;
}

.customer-nav a.logout-link:hover {
    background: rgba(229, 57, 53, 0.1);
}

/* Main Content */
.customer-content {
    animation: fadeInUp 0.6s ease-out 0.1s both;
}

.customer-page-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 30px;
    letter-spacing: -0.02em;
}

/* Orders specific styles */
.orders-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.order-card {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,1);
    box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.order-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(95, 51, 168, 0.08);
}

.order-card-header {
    background: rgba(95, 51, 168, 0.03);
    padding: 20px 24px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.order-info-group {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.order-info-group .label {
    font-family: 'Inter', sans-serif;
    font-size: 0.85rem;
    color: var(--text-soft);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 600;
}

.order-info-group .value {
    font-weight: 600;
    color: var(--text);
    font-size: 1.05rem;
}

.order-card-body {
    padding: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.order-status-group {
    display: flex;
    gap: 12px;
}

.status-badge {
    padding: 6px 14px;
    border-radius: 999px;
    font-family: 'Inter', sans-serif;
    font-size: 0.85rem;
    font-weight: 600;
}

.payment-pending { background: #fff3e0; color: #e65100; }
.payment-paid { background: #e8f5e9; color: #2e7d32; }
.payment-refunded { background: #f3e5f5; color: #7b1fa2; }

.status-processing { background: #e3f2fd; color: #1565c0; }
.status-delivered { background: #e8f5e9; color: #2e7d32; }
.status-cancelled { background: #ffebee; color: #c62828; }

.order-actions {
    display: flex;
    gap: 12px;
    align-items: center;
}

.order-action-btn {
    padding: 10px 20px;
    background: #fff;
    border: 1px solid var(--brand-primary);
    color: var(--brand-primary);
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.order-action-btn:hover {
    background: var(--brand-primary);
    color: #fff;
}

.cancel-order-btn {
    background: none;
    border: none;
    color: #e53935;
    font-family: 'Inter', sans-serif;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    text-decoration: underline;
}

.cancel-order-btn:hover {
    color: #b71c1c;
}

@media (max-width: 900px) {
    .customer-layout { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
    .order-card-header { grid-template-columns: 1fr; gap: 12px; }
    .order-card-body { flex-direction: column; align-items: flex-start; gap: 20px; }
    .order-actions { width: 100%; justify-content: space-between; }
}
</style>

<main class="customer-page">
    <div class="container">
        
        <div class="customer-layout">
            
            <!-- Customer Navigation Sidebar -->
            <aside class="customer-sidebar">
                <div class="customer-profile-brief">
                    <div class="avatar"><?php if ($profile) { echo htmlspecialchars(strtoupper(substr($profile['first_name'], 0, 1) . substr($profile['last_name'], 0, 1)), ENT_QUOTES, 'UTF-8'); } else { echo 'U'; } ?></div>
                    <div class="info">
                        <strong><?php if ($profile) { echo htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name'], ENT_QUOTES, 'UTF-8'); } else { echo 'User'; } ?></strong>
                        <span><?php if ($profile) { echo htmlspecialchars($profile['email'], ENT_QUOTES, 'UTF-8'); } ?></span>
                    </div>
                </div>
                <nav class="customer-nav">
                    <a href="index.php">Dashboard</a>
                    <a href="orders.php" class="active">My Orders</a>
                    <a href="returns.php">Returns & Replacements</a>
                    <a href="account.php">Account Details</a>
                    <a href="<?= $basePath ?>/auth/logout.php" class="logout-link">Logout</a>
                </nav>
            </aside>
            
            <!-- Main Content -->
            <div class="customer-content">
                <h1 class="customer-page-title">My Orders</h1>

                <?php if ($orderSuccess): ?>
                    <div class="alert-box"><?= htmlspecialchars($orderSuccess, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <?php if ($cancelMessage): ?>
                    <div class="alert-box" style="<?php if ($cancelType === 'success') { echo 'background: rgba(76, 175, 80, 0.1); color: #2e7d32;'; } else { echo 'background: rgba(229, 57, 53, 0.1); color: #c62828;'; } ?>">
                        <?= htmlspecialchars($cancelMessage, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>
                
                <div class="orders-list">
                    <?php if (empty($orders)): ?>
                        <div class="empty-state">No orders yet. Start shopping to place your first order.</div>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <?php $payment = str_replace('_', ' ', $order['payment_status']); $status = str_replace('_', ' ', $order['status']); $canCancel = in_array($order['status'], ['pending', 'confirmed'], true); ?>
                            <div class="order-card">
                                <div class="order-card-header">
                                    <div class="order-info-group">
                                        <span class="label">Order Number</span>
                                        <span class="value">#<?= htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8') ?></span>
                                    </div>
                                    <div class="order-info-group">
                                        <span class="label">Date Placed</span>
                                        <span class="value"><?= date('d M Y', strtotime($order['order_date'])) ?></span>
                                    </div>
                                    <div class="order-info-group">
                                        <span class="label">Total Amount</span>
                                        <span class="value">$<?= number_format((float) $order['total_amount'], 2) ?></span>
                                    </div>
                                </div>
                                <div class="order-card-body">
                                    <div class="order-status-group">
                                        <div class="status-badge payment-<?= htmlspecialchars(strtolower($payment), ENT_QUOTES, 'UTF-8') ?>">
                                            Payment: <?= htmlspecialchars(ucfirst($payment), ENT_QUOTES, 'UTF-8') ?>
                                        </div>
                                        <div class="status-badge status-<?= htmlspecialchars(strtolower($status), ENT_QUOTES, 'UTF-8') ?>">
                                            Status: <?= htmlspecialchars(ucfirst($status), ENT_QUOTES, 'UTF-8') ?>
                                        </div>
                                    </div>
                                    <div class="order-actions">
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
