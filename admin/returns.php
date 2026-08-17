<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_admin();

$pageTitle = 'Manage Returns - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';

$activePage = 'returns.php';
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
                <h1 class="customer-page-title">Manage Returns</h1>
                
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Return ID</th><th>Order #</th><th>Customer</th><th>Product</th><th>Type</th><th>Reason</th><th>Date</th><th>Status</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>RET-001</td>
                                <td>11200345001</td>
                                <td>Jane Doe</td>
                                <td>Lavender Dream Journal</td>
                                <td>Refund</td>
                                <td>Defective</td>
                                <td>16 Aug 2026</td>
                                <td><span class="status-badge payment-pending">Pending</span></td>
                                <td class="admin-actions-cell">
                                    <button class="secondary-button" style="padding:4px 8px; font-size:0.8rem;" onclick="alert('Approved')">Approve</button>
                                    <button class="secondary-button" style="padding:4px 8px; font-size:0.8rem;" onclick="alert('Rejected')">Reject</button>
                                    <button class="secondary-button" style="padding:4px 8px; font-size:0.8rem;" onclick="alert('Completed')">Complete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

