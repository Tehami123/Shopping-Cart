<?php
$pageTitle = 'Deliveries - Arts Employee';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';

$activePage = 'delivery.php';
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
                    <div class="info"><strong style="color:white;">Employee Portal</strong></div>
                </div>
                <nav class="customer-nav">
                    <?php foreach ($employeeNav as $url => $label): ?>
                        <a href="<?= $url ?>" <?= $activePage === $url ? 'class="active"' : '' ?>><?= $label ?></a>
                    <?php endforeach; ?>
                    <a href="<?= $basePath ?>/auth/login.php" class="logout-link">Logout</a>
                </nav>
            </aside>
            <div class="customer-content">
                <h1 class="customer-page-title">Delivery Updates</h1>
                
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Order #</th><th>Customer</th><th>Dispatch Date</th><th>Type</th><th>Delivery Status</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>11200345005</td>
                                <td>Jane Doe</td>
                                <td>14 Aug 2026</td>
                                <td>Standard</td>
                                <td><span class="status-badge status-processing">In Transit</span></td>
                                <td><button class="primary-button" style="padding:4px 12px; font-size:0.8rem; height:auto;" onclick="alert('Marked as Delivered!')">Mark Delivered</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

