<?php
$pageTitle = 'Dispatch Orders - Arts Employee';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';

$activePage = 'dispatch.php';
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
                <h1 class="customer-page-title">Dispatch Management</h1>
                <div class="policy-notice" style="border-left-color:#dd6b20; background:#feebc8; color:#7b341e;">
                    <p><strong>Note:</strong> Credit Card/Cheque orders cannot be dispatched until payment is cleared.</p>
                </div>
                
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Order #</th><th>Customer</th><th>Payment</th><th>Type</th><th>Status</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>11200345001</td>
                                <td>Jane Doe</td>
                                <td><span class="status-badge payment-paid">Cleared</span></td>
                                <td>Standard</td>
                                <td><span class="status-badge status-processing">Ready to Dispatch</span></td>
                                <td><button class="secondary-button" style="padding:4px 8px; font-size:0.8rem;" onclick="alert('Dispatched!')">Dispatch Order</button></td>
                            </tr>
                            <tr>
                                <td>11200345002</td>
                                <td>John Smith</td>
                                <td><span class="status-badge payment-pending">Pending Cheque</span></td>
                                <td>Express</td>
                                <td><span class="status-badge status-cancelled">Hold</span></td>
                                <td><button class="secondary-button" style="padding:4px 8px; font-size:0.8rem; opacity:0.5; cursor:not-allowed;" disabled>Wait for Payment</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

