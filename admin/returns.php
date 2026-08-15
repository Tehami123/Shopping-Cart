<?php
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
                </nav>
            </aside>
            <div class="customer-content">
                <h1 class="customer-page-title">Manage Returns</h1>
                
                <div style="overflow-x:auto; background:#fff; border:1px solid var(--line); border-radius:var(--radius-md);">
                    <table style="width:100%; border-collapse:collapse; text-align:left;">
                        <thead style="background:var(--bg-soft); border-bottom:1px solid var(--line);">
                            <tr><th style="padding:12px;">Return ID</th><th style="padding:12px;">Order #</th><th style="padding:12px;">Customer</th><th style="padding:12px;">Product</th><th style="padding:12px;">Type</th><th style="padding:12px;">Reason</th><th style="padding:12px;">Date</th><th style="padding:12px;">Status</th><th style="padding:12px;">Actions</th></tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom:1px solid var(--line);">
                                <td style="padding:12px;">RET-001</td>
                                <td style="padding:12px;">11200345001</td>
                                <td style="padding:12px;">Jane Doe</td>
                                <td style="padding:12px;">Lavender Dream Journal</td>
                                <td style="padding:12px;">Refund</td>
                                <td style="padding:12px;">Defective</td>
                                <td style="padding:12px;">16 Aug 2026</td>
                                <td style="padding:12px;"><span class="status-badge payment-pending">Pending</span></td>
                                <td style="padding:12px; display:flex; gap:8px;">
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
