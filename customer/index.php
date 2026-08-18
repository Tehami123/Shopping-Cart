<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_customer();

$pageTitle = 'My Dashboard - Arts';
$basePath = '/Shopping%20Cart';
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
    margin: 0 0 10px;
    letter-spacing: -0.02em;
}

.customer-welcome {
    font-family: 'Inter', sans-serif;
    font-size: 1.1rem;
    color: var(--text-soft);
    margin-bottom: 30px;
}

.dashboard-stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-bottom: 40px;
}

.stat-card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,1);
    box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    padding: 30px;
    text-align: center;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.06);
}

.stat-value {
    font-size: 3rem;
    font-weight: 700;
    color: var(--brand-primary);
    line-height: 1;
    margin-bottom: 10px;
}

.stat-label {
    font-family: 'Inter', sans-serif;
    font-size: 1rem;
    color: var(--text-soft);
    font-weight: 500;
}

.customer-section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 20px;
}

.quick-links-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.quick-link-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(20px);
    border-radius: 16px;
    border: 1px solid rgba(255,255,255,1);
    box-shadow: 0 8px 25px rgba(0,0,0,0.02);
    padding: 24px;
    text-decoration: none;
    color: var(--text);
    font-weight: 600;
    transition: all 0.3s ease;
}

.quick-link-card .icon {
    font-size: 2.5rem;
    margin-bottom: 12px;
    transition: transform 0.3s ease;
}

.quick-link-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(95, 51, 168, 0.08);
    border-color: rgba(95, 51, 168, 0.2);
    color: var(--brand-primary);
}

.quick-link-card:hover .icon {
    transform: scale(1.1);
}

@media (max-width: 900px) {
    .customer-layout {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 600px) {
    .dashboard-stats-grid, .quick-links-grid {
        grid-template-columns: 1fr;
    }
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
                    <a href="index.php" class="active">Dashboard</a>
                    <a href="orders.php">My Orders</a>
                    <a href="returns.php">Returns & Replacements</a>
                    <a href="account.php">Account Details</a>
                    <a href="<?= $basePath ?>/auth/logout.php" class="logout-link">Logout</a>
                </nav>
            </aside>
            
            <!-- Main Content -->
            <div class="customer-content">
                <h1 class="customer-page-title">My Dashboard</h1>
                <p class="customer-welcome">Welcome back, <?php if ($profile) { echo htmlspecialchars($profile['first_name'], ENT_QUOTES, 'UTF-8'); } else { echo 'User'; } ?>! Here's an overview of your account.</p>
                
                <div class="dashboard-stats-grid">
                    <div class="stat-card">
                        <div class="stat-value"><?= $orderCount ?></div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= $pendingCount ?></div>
                        <div class="stat-label">Pending Orders</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= $activeReturnsCount ?></div>
                        <div class="stat-label">Active Returns</div>
                    </div>
                </div>
                
                <h2 class="customer-section-title">Quick Links</h2>
                <div class="quick-links-grid">
                    <a href="<?= $basePath ?>/products.php" class="quick-link-card">
                        <span class="icon">🛍️</span>
                        <span>Shop New Arrivals</span>
                    </a>
                    <a href="<?= $basePath ?>/cart.php" class="quick-link-card">
                        <span class="icon">🛒</span>
                        <span>View Cart</span>
                    </a>
                    <a href="orders.php" class="quick-link-card">
                        <span class="icon">📦</span>
                        <span>Track Recent Order</span>
                    </a>
                </div>
                
            </div>
        </div>
        
    </div>
</main>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
