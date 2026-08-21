<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_employee();

$pageTitle = 'Employee Dashboard - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
require_once dirname(__DIR__) . '/includes/employee-shell.php';

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
<main class="employee-app">
    <div class="employee-layout">
        <?php render_employee_sidebar($employeeNav, $activePage, $basePath); ?>
        <section class="employee-main">
            <?php render_employee_page_header('Employee Dashboard', 'Start with the work that needs attention, then move through dispatch and delivery in order.', 'Today at Arts'); ?>

            <section class="employee-stat-grid" aria-label="Operations overview">
                <article class="employee-stat-card employee-stat-card-alert"><span>Pending orders</span><strong><?= (int) $employeeStats['pending_orders'] ?></strong><small>Awaiting confirmation</small><a href="orders.php">View orders</a></article>
                <article class="employee-stat-card employee-stat-card-primary"><span>Ready to dispatch</span><strong><?= (int) $employeeStats['ready_for_dispatch'] ?></strong><small>Payment cleared</small><a href="dispatch.php">Open dispatch</a></article>
                <article class="employee-stat-card"><span>Out for delivery</span><strong><?= (int) $employeeStats['dispatched_orders'] ?></strong><small>Dispatched orders</small><a href="delivery.php">Track delivery</a></article>
            </section>

            <section class="employee-work-grid">
                <article class="employee-panel employee-attention-panel">
                    <div class="employee-panel-heading"><div><span class="employee-eyebrow">Priority queue</span><h2>What needs attention</h2></div><span class="employee-live-label">Live counts</span></div>
                    <div class="employee-attention-list">
                        <a href="orders.php" class="employee-attention-item <?= (int) $employeeStats['pending_orders'] > 0 ? 'is-alert' : 'is-clear' ?>"><span class="employee-attention-icon">!</span><span><strong><?= (int) $employeeStats['pending_orders'] ?> pending orders</strong><small>Review the order queue and current payment details.</small></span><span class="employee-arrow">-&gt;</span></a>
                        <a href="dispatch.php" class="employee-attention-item <?= (int) $employeeStats['ready_for_dispatch'] > 0 ? 'is-ready' : 'is-clear' ?>"><span class="employee-attention-icon">D</span><span><strong><?= (int) $employeeStats['ready_for_dispatch'] ?> ready for dispatch</strong><small>Payment-cleared orders can move to fulfillment.</small></span><span class="employee-arrow">-&gt;</span></a>
                        <a href="delivery.php" class="employee-attention-item <?= (int) $employeeStats['deliveries_pending'] > 0 ? 'is-ready' : 'is-clear' ?>"><span class="employee-attention-icon">&gt;</span><span><strong><?= (int) $employeeStats['deliveries_pending'] ?> delivery updates</strong><small>Keep dispatched orders moving to delivered.</small></span><span class="employee-arrow">-&gt;</span></a>
                    </div>
                </article>
                <article class="employee-panel employee-quick-panel">
                    <div class="employee-panel-heading"><div><span class="employee-eyebrow">Shortcuts</span><h2>Quick actions</h2></div></div>
                    <div class="employee-shortcut-list">
                        <a href="orders.php"><span class="employee-shortcut-index">01</span><strong>View all orders</strong><span class="employee-arrow">-&gt;</span></a>
                        <a href="dispatch.php"><span class="employee-shortcut-index">02</span><strong>Dispatch queue</strong><span class="employee-arrow">-&gt;</span></a>
                        <a href="delivery.php"><span class="employee-shortcut-index">03</span><strong>Delivery updates</strong><span class="employee-arrow">-&gt;</span></a>
                    </div>
                </article>
            </section>
        </section>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

