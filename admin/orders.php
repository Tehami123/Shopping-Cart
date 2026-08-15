<?php
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
                </nav>
            </aside>
            <div class="customer-content">
                <h1 class="customer-page-title">Manage Orders</h1>
                
                <div style="background:#fff; padding:16px; border:1px solid var(--line); border-radius:var(--radius-md); margin-bottom:24px; display:flex; gap:16px; align-items:flex-end;">
                    <div class="form-group" style="margin:0; flex:1;">
                        <label>Filter Status</label>
                        <select class="form-select" style="height:40px;"><option>All</option><option>Processing</option><option>Delivered</option></select>
                    </div>
                    <div class="form-group" style="margin:0; flex:1;">
                        <label>Delivery Type</label>
                        <select class="form-select" style="height:40px;"><option>All</option><option>Standard</option><option>Express</option></select>
                    </div>
                    <button class="primary-button" style="height:40px; padding:0 16px;">Apply Filters</button>
                </div>

                <div style="overflow-x:auto; background:#fff; border:1px solid var(--line); border-radius:var(--radius-md);">
                    <table style="width:100%; border-collapse:collapse; text-align:left;">
                        <thead style="background:var(--bg-soft); border-bottom:1px solid var(--line);">
                            <tr><th style="padding:12px;">Order #</th><th style="padding:12px;">Customer</th><th style="padding:12px;">Date</th><th style="padding:12px;">Total</th><th style="padding:12px;">Payment Status</th><th style="padding:12px;">Order Status</th><th style="padding:12px;">Delivery</th><th style="padding:12px;">Action</th></tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom:1px solid var(--line);">
                                <td style="padding:12px;">11200345001</td>
                                <td style="padding:12px;">Jane Doe</td>
                                <td style="padding:12px;">15 Aug 2026</td>
                                <td style="padding:12px;">$24.00</td>
                                <td style="padding:12px;"><span class="status-badge payment-pending">Pending</span></td>
                                <td style="padding:12px;"><span class="status-badge status-processing">Processing</span></td>
                                <td style="padding:12px;">Standard</td>
                                <td style="padding:12px;"><button class="text-button">View</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
