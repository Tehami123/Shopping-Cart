<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

$pageTitle = 'Admin Dashboard - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
require_once dirname(__DIR__) . '/includes/admin-shell.php';

$activePage = 'index.php';
$adminNav = [
    'index.php' => 'Dashboard',
    'products.php' => 'Products',
    'inventory.php' => 'Inventory',
    'orders.php' => 'Orders',
    'customers.php' => 'Customers',
    'employees.php' => 'Employees',
    'payments.php' => 'Payments',
    'returns.php' => 'Returns',
    'feedback.php' => 'Feedback',
    'faq.php' => 'FAQ'
];
$dashboardStats = get_admin_dashboard_stats();
?>
<main class="admin-app">
    <div class="admin-layout">
        <?php render_admin_sidebar($adminNav, $activePage, $basePath); ?>
        <section class="admin-main">
            <?php render_admin_page_header('Admin Dashboard', 'A focused view of your store operations and the work that needs attention today.', 'Overview'); ?>

            <section class="admin-stat-grid" aria-label="Store overview">
                <article class="admin-stat-card admin-stat-card-primary"><span class="admin-stat-label">Catalog</span><strong><?= $dashboardStats['total_products'] ?></strong><span>Total products</span><a href="products.php">Manage catalog</a></article>
                <article class="admin-stat-card"><span class="admin-stat-label">Orders</span><strong><?= $dashboardStats['total_orders'] ?></strong><span>Orders recorded</span><a href="orders.php">Review orders</a></article>
                <article class="admin-stat-card"><span class="admin-stat-label">Customers</span><strong><?= $dashboardStats['total_customers'] ?></strong><span>Customer accounts</span><a href="customers.php">View directory</a></article>
                <article class="admin-stat-card"><span class="admin-stat-label">Team</span><strong><?= $dashboardStats['total_employees'] ?></strong><span>Employee accounts</span><a href="employees.php">Manage team</a></article>
            </section>

            <section class="admin-dashboard-grid">
                <article class="admin-panel admin-attention-panel">
                    <div class="admin-panel-heading"><div><span class="admin-eyebrow">Needs attention</span><h2>Keep the store moving</h2></div><span class="admin-panel-kicker">Live signals</span></div>
                    <div class="admin-attention-list">
                        <a href="inventory.php" class="admin-attention-item <?= (int) $dashboardStats['low_stock_products'] > 0 ? 'is-alert' : 'is-clear' ?>"><span class="admin-attention-icon">!</span><span><strong><?= $dashboardStats['low_stock_products'] ?> low-stock products</strong><small>Check availability before the next sale.</small></span><span class="admin-arrow">-&gt;</span></a>
                        <a href="orders.php?status=pending" class="admin-attention-item <?= (int) $dashboardStats['pending_orders'] > 0 ? 'is-alert' : 'is-clear' ?>"><span class="admin-attention-icon">~</span><span><strong><?= $dashboardStats['pending_orders'] ?> pending orders</strong><small>Orders waiting for the next fulfillment step.</small></span><span class="admin-arrow">-&gt;</span></a>
                        <a href="returns.php" class="admin-attention-item is-neutral"><span class="admin-attention-icon">&lt;</span><span><strong>Returns queue</strong><small>Review customer requests when they arrive.</small></span><span class="admin-arrow">-&gt;</span></a>
                    </div>
                </article>
                <article class="admin-panel admin-operations-panel">
                    <div class="admin-panel-heading"><div><span class="admin-eyebrow">Shortcuts</span><h2>Quick management</h2></div></div>
                    <div class="admin-shortcut-list">
                        <?php foreach (array_slice($adminNav, 1, 4) as $url => $label): ?>
                            <a href="<?= $url ?>"><span class="admin-shortcut-index">0<?= array_search($url, array_keys($adminNav), true) ?></span><strong><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></strong><span class="admin-arrow">-&gt;</span></a>
                        <?php endforeach; ?>
                    </div>
                </article>
            </section>

            <section class="admin-panel admin-management-panel">
                <div class="admin-panel-heading"><div><span class="admin-eyebrow">Workspace map</span><h2>Management areas</h2></div><p>Move between the parts of the Arts operation without losing context.</p></div>
                <div class="admin-management-grid">
                    <?php foreach (array_slice($adminNav, 1) as $url => $label): ?>
                        <a href="<?= $url ?>"><strong><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></strong><span>Open workspace <span class="admin-arrow">-&gt;</span></span></a>
                    <?php endforeach; ?>
                </div>
            </section>
        </section>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
