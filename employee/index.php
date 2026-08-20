<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_employee();

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
$db = get_db_connection();
$employeeStats = $db->query(
    'SELECT
        (SELECT COUNT(*) FROM orders WHERE status = "pending") AS pending_orders,
        (SELECT COUNT(*) FROM orders WHERE status IN ("pending", "confirmed") AND payment_status = "cleared") AS ready_for_dispatch,
        (SELECT COUNT(*) FROM orders WHERE status = "dispatched") AS dispatched_orders,
        (SELECT COUNT(*) FROM orders WHERE status = "dispatched") AS deliveries_pending'
)->fetch();
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
                    <div class="stat-card"><div class="stat-value" style="color:#dd6b20;"><?= (int) $employeeStats['pending_orders'] ?></div><div class="stat-label">Pending Orders</div></div>
                    <div class="stat-card"><div class="stat-value" style="color:#2b6cb0;"><?= (int) $employeeStats['ready_for_dispatch'] ?></div><div class="stat-label">Ready for Dispatch</div></div>
                    <div class="stat-card"><div class="stat-value"><?= (int) $employeeStats['dispatched_orders'] ?></div><div class="stat-label">Dispatched Orders</div></div>
                    <div class="stat-card"><div class="stat-value" style="color:#22543d;"><?= (int) $employeeStats['deliveries_pending'] ?></div><div class="stat-label">Deliveries Pending</div></div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

