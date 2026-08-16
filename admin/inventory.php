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
                    <a href="<?= $basePath ?>/auth/login.php" class="logout-link">Logout</a>
                </nav>
            </aside>
            <div class="customer-content">
                <h1 class="customer-page-title">Inventory Management</h1>
                
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Product ID</th><th>Product</th><th>Current Stock</th><th>Stock Status</th><th>Update Stock</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ART1001</td>
                                <td>Lavender Dream Journal</td>
                                <td>45</td>
                                <td><span class="status-badge status-delivered">In Stock</span></td>
                                <td>
                                    <form onsubmit="event.preventDefault(); alert('Stock updated!');" style="display:flex; gap:8px;">
                                        <input type="number" value="45" style="width:60px; padding:4px;" class="form-input">
                                        <button type="submit" class="secondary-button" style="padding:4px 8px; font-size:0.8rem; height:auto;">Update</button>
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <td>ART1002</td>
                                <td>Ceramic Mug</td>
                                <td>3</td>
                                <td><span class="status-badge payment-pending">Low Stock</span></td>
                                <td>
                                    <form onsubmit="event.preventDefault(); alert('Stock updated!');" style="display:flex; gap:8px;">
                                        <input type="number" value="3" style="width:60px; padding:4px;" class="form-input">
                                        <button type="submit" class="secondary-button" style="padding:4px 8px; font-size:0.8rem; height:auto;">Update</button>
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <td>ART1003</td>
                                <td>Leather Planner</td>
                                <td>0</td>
                                <td><span class="status-badge status-cancelled">Out of Stock</span></td>
                                <td>
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

