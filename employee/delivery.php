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
                </nav>
            </aside>
            <div class="customer-content">
                <h1 class="customer-page-title">Delivery Updates</h1>
                
                <div style="overflow-x:auto; background:#fff; border:1px solid var(--line); border-radius:var(--radius-md);">
                    <table style="width:100%; border-collapse:collapse; text-align:left;">
                        <thead style="background:var(--bg-soft); border-bottom:1px solid var(--line);">
                            <tr><th style="padding:12px;">Order #</th><th style="padding:12px;">Customer</th><th style="padding:12px;">Dispatch Date</th><th style="padding:12px;">Type</th><th style="padding:12px;">Delivery Status</th><th style="padding:12px;">Action</th></tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom:1px solid var(--line);">
                                <td style="padding:12px;">11200345005</td>
                                <td style="padding:12px;">Jane Doe</td>
                                <td style="padding:12px;">14 Aug 2026</td>
                                <td style="padding:12px;">Standard</td>
                                <td style="padding:12px;"><span class="status-badge status-processing">In Transit</span></td>
                                <td style="padding:12px;"><button class="primary-button" style="padding:4px 12px; font-size:0.8rem; height:auto;" onclick="alert('Marked as Delivered!')">Mark Delivered</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
