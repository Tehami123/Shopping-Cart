<?php
$pageTitle = 'Manage Customers - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';

$activePage = 'customers.php';
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
                <h1 class="customer-page-title">Manage Customers</h1>
                
                <div style="overflow-x:auto; background:#fff; border:1px solid var(--line); border-radius:var(--radius-md);">
                    <table style="width:100%; border-collapse:collapse; text-align:left;">
                        <thead style="background:var(--bg-soft); border-bottom:1px solid var(--line);">
                            <tr><th style="padding:12px;">Customer</th><th style="padding:12px;">Email</th><th style="padding:12px;">Phone</th><th style="padding:12px;">City</th><th style="padding:12px;">Registered Date</th><th style="padding:12px;">Status</th><th style="padding:12px;">Action</th></tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom:1px solid var(--line);">
                                <td style="padding:12px;">Jane Doe</td>
                                <td style="padding:12px;">jane@example.com</td>
                                <td style="padding:12px;">+1 555-0000</td>
                                <td style="padding:12px;">Metropolis</td>
                                <td style="padding:12px;">01 Jan 2026</td>
                                <td style="padding:12px;"><span class="status-badge status-delivered">Active</span></td>
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
