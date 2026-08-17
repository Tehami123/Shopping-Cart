<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_admin();

$pageTitle = 'Admin Dashboard - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';

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
?>
<main class="customer-page admin-page">
    <div class="container">
        <div class="customer-layout">
            <aside class="customer-sidebar">
                <div class="customer-profile-brief" style="background: var(--brand-primary-dark); color: white;">
                    <div class="info">
                        <strong style="color:white;">Admin Portal</strong>
                        <span style="color:#e2e8f0;">System Administrator</span>
                    </div>
                </div>
                <nav class="customer-nav">
                    <?php foreach ($adminNav as $url => $label): ?>
                        <a href="<?= $url ?>" <?= $activePage === $url ? 'class="active"' : '' ?>><?= $label ?></a>
                    <?php endforeach; ?>
                    <a href="<?= $basePath ?>/auth/login.php" class="logout-link">Logout</a>
                </nav>
            </aside>
            <div class="customer-content">
                <h1 class="customer-page-title">Admin Dashboard</h1>
                <div class="dashboard-stats-grid" style="grid-template-columns: repeat(3, 1fr);">
                    <div class="stat-card"><div class="stat-value">124</div><div class="stat-label">Total Products</div></div>
                    <div class="stat-card"><div class="stat-value" style="color:#c53030;">12</div><div class="stat-label">Low Stock</div></div>
                    <div class="stat-card"><div class="stat-value">845</div><div class="stat-label">Total Orders</div></div>
                    <div class="stat-card"><div class="stat-value" style="color:#dd6b20;">34</div><div class="stat-label">Pending Orders</div></div>
                    <div class="stat-card"><div class="stat-value">1,204</div><div class="stat-label">Customers</div></div>
                    <div class="stat-card"><div class="stat-value">8</div><div class="stat-label">Employees</div></div>
                </div>
                
                <h2 class="customer-section-title">Quick Navigation</h2>
                <div class="quick-links-grid">
                    <?php foreach(array_slice($adminNav, 1, 6) as $url => $label): ?>
                        <a href="<?= $url ?>" class="quick-link-card">
                            <span class="icon">&#128193;</span>
                            <span><?= $label ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
