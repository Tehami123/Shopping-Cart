<?php
$pageTitle = 'Manage Products - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';

$activePage = 'products.php';
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
                <div class="customer-header-actions">
                    <h1 class="customer-page-title">Manage Products</h1>
                    <button class="primary-button" onclick="document.getElementById('addProductModal').style.display='flex'">Add Product</button>
                </div>
                
                <div style="overflow-x:auto; background:#fff; border:1px solid var(--line); border-radius:var(--radius-md);">
                    <table style="width:100%; border-collapse:collapse; text-align:left;">
                        <thead style="background:var(--bg-soft); border-bottom:1px solid var(--line);">
                            <tr><th style="padding:12px;">ID</th><th style="padding:12px;">Name</th><th style="padding:12px;">Category</th><th style="padding:12px;">Price</th><th style="padding:12px;">Stock</th><th style="padding:12px;">Status</th><th style="padding:12px;">Actions</th></tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom:1px solid var(--line);">
                                <td style="padding:12px;">ART1001</td>
                                <td style="padding:12px;">Lavender Dream Journal</td>
                                <td style="padding:12px;">Stationery</td>
                                <td style="padding:12px;">$24.00</td>
                                <td style="padding:12px;">45</td>
                                <td style="padding:12px;"><span class="status-badge status-delivered">Active</span></td>
                                <td style="padding:12px;">
                                    <button class="text-button">Edit</button> | <button class="text-button" style="color:red;">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<div id="addProductModal" class="mock-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:9999;">
    <div style="background:#fff; padding:32px; border-radius:8px; width:100%; max-width:600px; max-height:90vh; overflow-y:auto;">
        <h3>Add Product</h3>
        <form onsubmit="event.preventDefault(); alert('Product Added!'); this.closest('.mock-modal').style.display='none';">
            <div class="form-row">
                <div class="form-group"><label>Product Code</label><input type="text" class="form-input"></div>
                <div class="form-group"><label>Product Number</label><input type="text" class="form-input"></div>
            </div>
            <div class="form-group"><label>Product Name</label><input type="text" class="form-input"></div>
            <div class="form-group"><label>Category</label><input type="text" class="form-input"></div>
            <div class="form-group"><label>Description</label><textarea class="form-textarea"></textarea></div>
            <div class="form-row">
                <div class="form-group"><label>Price</label><input type="number" class="form-input"></div>
                <div class="form-group"><label>Stock</label><input type="number" class="form-input"></div>
            </div>
            <div class="form-group"><label>Status</label><select class="form-select"><option>Active</option><option>Draft</option></select></div>
            <div style="display:flex; gap:16px; margin-top:24px;">
                <button type="submit" class="primary-button">Save Product</button>
                <button type="button" class="secondary-button" onclick="this.closest('.mock-modal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
