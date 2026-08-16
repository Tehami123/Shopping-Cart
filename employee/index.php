<?php
$pageTitle = 'Employee Dashboard - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';

$activePage = 'index.php';
$employeeNav = [
    'index.php' => 'Dashboard',
    'orders.php' => 'Orders',
    'dispatch.php' => 'Dispatch',
    'delivery.php' => 'Delivery'
];
?>
<main class="customer-page employee-page">
    <div class="container">
        <div class="customer-layout">
            <aside class="customer-sidebar">
                <div class="customer-profile-brief" style="background: #2b6cb0; color: white;">
                    <div class="info">
                        <strong style="color:white;">Employee Portal</strong>
                        <span style="color:#e2e8f0;">Staff Dashboard</span>
                    </div>
                </div>
                <nav class="customer-nav">
                    <?php foreach ($employeeNav as $url => $label): ?>
                        <a href="<?= $url ?>" <?= $activePage === $url ? 'class="active"' : '' ?>><?= $label ?></a>
                    <?php endforeach; ?>
                    <a href="<?= $basePath ?>/auth/login.php" class="logout-link">Logout</a>
                </nav>
            </aside>
            <div class="customer-content">
                <h1 class="customer-page-title">Employee Dashboard</h1>
                <div class="dashboard-stats-grid" style="grid-template-columns: repeat(2, 1fr);">
                    <div class="stat-card"><div class="stat-value" style="color:#dd6b20;">12</div><div class="stat-label">Pending Orders</div></div>
                    <div class="stat-card"><div class="stat-value" style="color:#2b6cb0;">8</div><div class="stat-label">Ready for Dispatch</div></div>
                    <div class="stat-card"><div class="stat-value">45</div><div class="stat-label">Dispatched Orders</div></div>
                    <div class="stat-card"><div class="stat-value" style="color:#22543d;">5</div><div class="stat-label">Deliveries Pending</div></div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

