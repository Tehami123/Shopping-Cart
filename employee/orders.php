<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_employee();

$pageTitle = 'Orders - Arts Employee';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';

$activePage = 'orders.php';
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
                <h1 class="customer-page-title">Order View</h1>
                
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Order #</th><th>Customer</th><th>Date</th><th>Payment</th><th>Status</th><th>Type</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>11200345001</td>
                                <td>Jane Doe</td>
                                <td>15 Aug 2026</td>
                                <td><span class="status-badge payment-pending">Pending</span></td>
                                <td><span class="status-badge status-processing">Processing</span></td>
                                <td>Express</td>
                                <td><button class="text-button">View</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

