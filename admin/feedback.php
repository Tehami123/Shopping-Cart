<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_admin();

$pageTitle = 'Feedback - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';

$activePage = 'feedback.php';
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
                <h1 class="customer-page-title">Customer Feedback</h1>
                
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Customer</th><th>Date</th><th class="col-wide">Feedback</th><th>Status</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Jane Doe</td>
                                <td>10 Aug 2026</td>
                                <td>I loved the journals, but the delivery was a bit late.</td>
                                <td><span class="status-badge payment-pending">New</span></td>
                                <td><button class="text-button" onclick="alert('Marked as Reviewed')">Mark Reviewed</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

