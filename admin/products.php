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
                    <a href="<?= $basePath ?>/auth/login.php" class="logout-link">Logout</a>
                </nav>
            </aside>
            <div class="customer-content">
                <div class="customer-header-actions">
                    <h1 class="customer-page-title">Manage Products</h1>
                    <button class="primary-button" onclick="document.getElementById('addProductModal').style.display='flex'">Add Product</button>
                </div>
                
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ART1001</td>
                                <td>Lavender Dream Journal</td>
                                <td>Stationery</td>
                                <td>$24.00</td>
                                <td>45</td>
                                <td><span class="status-badge status-delivered">Active</span></td>
                                <td>
                                    <button class="text-button">Edit</button> |
                                    <button class="text-button danger">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<div id="addProductModal" class="mock-modal" style="display:none;">
    <div class="mock-modal-content">
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
            <div class="mock-modal-actions">
                <button type="submit" class="primary-button">Save Product</button>
                <button type="button" class="secondary-button" onclick="this.closest('.mock-modal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

