<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_admin();

$pageTitle = 'Manage Orders - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';

$activePage = 'orders.php';
$adminNav = [
    'index.php' => 'Dashboard', 'products.php' => 'Products', 'inventory.php' => 'Inventory',
    'orders.php' => 'Orders', 'customers.php' => 'Customers', 'employees.php' => 'Employees',
    'payments.php' => 'Payments', 'returns.php' => 'Returns', 'feedback.php' => 'Feedback', 'faq.php' => 'FAQ'
];
?>
<main class="customer-page admin-page">
    <div class="container">
        <div class="customer-layout">
            <aside class="customer-sidebar">
                <div class="customer-profile-brief" style="background: var(--brand-primary-dark); color: white;">
                    <div class="info"><strong style="color:white;">Admin Portal</strong></div>
                </div>
                <nav class="customer-nav">
                    <?php foreach ($adminNav as $url => $label): ?>
                        <a href="<?= $url ?>" <?= $activePage === $url ? 'class="active"' : '' ?>><?= $label ?></a>
                    <?php endforeach; ?>
                    <a href="<?= $basePath ?>/auth/login.php" class="logout-link">Logout</a>
                </nav>
            </aside>
            <div class="customer-content">
                <h1 class="customer-page-title">Manage Orders</h1>
                
                <div class="admin-filter-bar">
                    <div class="form-group">
                        <label>Filter Status</label>
                        <select class="form-select"><option>All</option><option>Processing</option><option>Delivered</option></select>
                    </div>
                    <div class="form-group">
                        <label>Delivery Type</label>
                        <select class="form-select"><option>All</option><option>Standard</option><option>Express</option></select>
                    </div>
                    <button class="primary-button">Apply Filters</button>
                </div>

                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Order #</th><th>Customer</th><th>Date</th><th>Total</th><th>Payment Status</th><th>Order Status</th><th>Delivery</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>11200345001</td>
                                <td>Jane Doe</td>
                                <td>15 Aug 2026</td>
                                <td>$24.00</td>
                                <td><span class="status-badge payment-pending">Pending</span></td>
                                <td><span class="status-badge status-processing">Processing</span></td>
                                <td>Standard</td>
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

