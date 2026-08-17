<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_admin();

$pageTitle = 'Inventory Management - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';

$db = get_db_connection();
$activePage = 'inventory.php';
$adminNav = [
    'index.php' => 'Dashboard', 'products.php' => 'Products', 'inventory.php' => 'Inventory',
    'orders.php' => 'Orders', 'customers.php' => 'Customers', 'employees.php' => 'Employees',
    'payments.php' => 'Payments', 'returns.php' => 'Returns', 'feedback.php' => 'Feedback', 'faq.php' => 'FAQ'
];

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock'])) {
    $productId = (int) ($_POST['product_id'] ?? 0);
    $stock = (int) ($_POST['stock'] ?? 0);

    if ($productId > 0 && $stock >= 0) {
        $stmt = $db->prepare('UPDATE products SET stock = :stock WHERE product_id = :product_id');
        $stmt->execute([':stock' => $stock, ':product_id' => $productId]);
        $successMessage = 'Inventory updated successfully.';
    } else {
        $errorMessage = 'Invalid stock value.';
    }
}

$products = $db->query('SELECT p.*, c.name AS category_name FROM products p INNER JOIN categories c ON c.category_id = p.category_id ORDER BY p.product_id ASC')->fetchAll();
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
                <?php if ($successMessage !== ''): ?><div class="alert-box" style="margin-bottom:16px;"><?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                <?php if ($errorMessage !== ''): ?><div class="error-box" style="margin-bottom:16px;"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Product ID</th><th>Product</th><th>Current Stock</th><th>Stock Status</th><th>Update Stock</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?= htmlspecialchars($product['full_product_id'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= (int) $product['stock'] ?></td>
                                    <td><span class="status-badge <?= get_status_badge_class(normalize_product_stock_label((int) $product['stock']), 'order') ?>"><?= htmlspecialchars(normalize_product_stock_label((int) $product['stock']), ENT_QUOTES, 'UTF-8') ?></span></td>
                                    <td>
                                        <form method="POST" style="display:flex; gap:8px; align-items:center;">
                                            <input type="hidden" name="update_stock" value="1">
                                            <input type="hidden" name="product_id" value="<?= (int) $product['product_id'] ?>">
                                            <input type="number" name="stock" value="<?= (int) $product['stock'] ?>" style="width:90px; padding:4px;" class="form-input" min="0">
                                            <button type="submit" class="secondary-button" style="padding:4px 8px; font-size:0.8rem; height:auto;">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

