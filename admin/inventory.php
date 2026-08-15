<?php
$pageTitle = 'Inventory Management - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';

$activePage = 'inventory.php';
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
                <h1 class="customer-page-title">Inventory Management</h1>
                
                <div style="overflow-x:auto; background:#fff; border:1px solid var(--line); border-radius:var(--radius-md);">
                    <table style="width:100%; border-collapse:collapse; text-align:left;">
                        <thead style="background:var(--bg-soft); border-bottom:1px solid var(--line);">
                            <tr><th style="padding:12px;">Product ID</th><th style="padding:12px;">Product</th><th style="padding:12px;">Current Stock</th><th style="padding:12px;">Stock Status</th><th style="padding:12px;">Update Stock</th></tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom:1px solid var(--line);">
                                <td style="padding:12px;">ART1001</td>
                                <td style="padding:12px;">Lavender Dream Journal</td>
                                <td style="padding:12px;">45</td>
                                <td style="padding:12px;"><span class="status-badge status-delivered">In Stock</span></td>
                                <td style="padding:12px;">
                                    <form onsubmit="event.preventDefault(); alert('Stock updated!');" style="display:flex; gap:8px;">
                                        <input type="number" value="45" style="width:60px; padding:4px;" class="form-input">
                                        <button type="submit" class="secondary-button" style="padding:4px 8px; font-size:0.8rem; height:auto;">Update</button>
                                    </form>
                                </td>
                            </tr>
                            <tr style="border-bottom:1px solid var(--line);">
                                <td style="padding:12px;">ART1002</td>
                                <td style="padding:12px;">Ceramic Mug</td>
                                <td style="padding:12px;">3</td>
                                <td style="padding:12px;"><span class="status-badge payment-pending">Low Stock</span></td>
                                <td style="padding:12px;">
                                    <form onsubmit="event.preventDefault(); alert('Stock updated!');" style="display:flex; gap:8px;">
                                        <input type="number" value="3" style="width:60px; padding:4px;" class="form-input">
                                        <button type="submit" class="secondary-button" style="padding:4px 8px; font-size:0.8rem; height:auto;">Update</button>
                                    </form>
                                </td>
                            </tr>
                            <tr style="border-bottom:1px solid var(--line);">
                                <td style="padding:12px;">ART1003</td>
                                <td style="padding:12px;">Leather Planner</td>
                                <td style="padding:12px;">0</td>
                                <td style="padding:12px;"><span class="status-badge status-cancelled">Out of Stock</span></td>
                                <td style="padding:12px;">
                                    <form onsubmit="event.preventDefault(); alert('Stock updated!');" style="display:flex; gap:8px;">
                                        <input type="number" value="0" style="width:60px; padding:4px;" class="form-input">
                                        <button type="submit" class="secondary-button" style="padding:4px 8px; font-size:0.8rem; height:auto;">Update</button>
                                    </form>
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
