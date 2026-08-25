<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_customer();

$pageTitle = 'My Dashboard - Arts';
$basePath = '/Shopping-Cart';
$userId = current_user_id();
$customerId = get_customer_id_for_user((int) $userId);

// Get customer profile
$profile = null;
$orders = [];
$orderCount = 0;
$pendingCount = 0;
$activeReturnsCount = 0;

if ($customerId !== null) {
    $profile = get_customer_profile($customerId);
    $orders = get_customer_order_history($customerId);
    $orderCount = count($orders);
    
    // Count pending orders
    foreach ($orders as $order) {
        if (in_array($order['status'], ['pending', 'confirmed', 'dispatched'], true)) {
            $pendingCount++;
        }
    }
    
    // Count active returns
    $returns = get_customer_return_requests($customerId);
    foreach ($returns as $ret) {
        if (in_array($ret['status'], ['requested', 'approved'], true)) {
            $activeReturnsCount++;
        }
    }
}

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
                    <a href="index.php" class="active">Dashboard</a>
                    <a href="orders.php">My Orders</a>
                    <a href="returns.php">Returns &amp; Replacements</a>
                    <a href="account.php">Account Details</a>
                    <a href="<?= $basePath ?>/auth/logout.php" class="logout-link">Logout</a>
                </nav>
            </aside>

            <!-- Main Content -->
            <div class="ca-content">
                <div class="ca-header">
                    <div>
                        <span class="ca-eyebrow">My Account</span>
                        <h1 class="ca-title">Welcome back<?php if ($profile) { echo ', ' . htmlspecialchars($profile['first_name'], ENT_QUOTES, 'UTF-8'); } ?></h1>
                        <p class="ca-subtitle">Here's a quick overview of your orders and account activity.</p>
                    </div>
                </div>

                <div class="ca-stats-grid">
                    <div class="ca-stat-card">
                        <div class="ca-stat-icon">📦</div>
                        <div>
                            <div class="ca-stat-value"><?= $orderCount ?></div>
                            <div class="ca-stat-label">Total Orders</div>
                        </div>
                    </div>
                    <div class="ca-stat-card">
                        <div class="ca-stat-icon">⏳</div>
                        <div>
                            <div class="ca-stat-value"><?= $pendingCount ?></div>
                            <div class="ca-stat-label">Pending Orders</div>
                        </div>
                    </div>
                    <div class="ca-stat-card">
                        <div class="ca-stat-icon">↩️</div>
                        <div>
                            <div class="ca-stat-value"><?= $activeReturnsCount ?></div>
                            <div class="ca-stat-label">Active Returns</div>
                        </div>
                    </div>
                </div>

                <h2 class="ca-section-title">Quick Links</h2>
                <div class="ca-quicklinks">
                    <a href="<?= $basePath ?>/products.php" class="ca-quicklink">
                        <span class="icon">🛍️</span>
                        <span class="label">Shop New Arrivals</span>
                    </a>
                    <a href="<?= $basePath ?>/cart.php" class="ca-quicklink">
                        <span class="icon">🛒</span>
                        <span class="label">View Cart</span>
                    </a>
                    <a href="orders.php" class="ca-quicklink">
                        <span class="icon">🔎</span>
                        <span class="label">Track Recent Order</span>
                    </a>
                </div>

            </div>
        </div>

    </div>
</main>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>