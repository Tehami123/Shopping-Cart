<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

$pageTitle = 'Inventory Management - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
require_once dirname(__DIR__) . '/includes/admin-shell.php';

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
<main class="admin-app">
    <div class="admin-layout">
        <?php render_admin_sidebar($adminNav, $activePage, $basePath); ?>
        <section class="admin-main">
            <?php render_admin_page_header('Inventory', 'Monitor stock levels and make availability updates before products run low.', 'Operations workspace'); ?>
                <?php if ($successMessage !== ''): ?><div class="alert-box" style="margin-bottom:16px;"><?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                <?php if ($errorMessage !== ''): ?><div class="error-box" style="margin-bottom:16px;"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Product</th><th>Available units</th><th>Stock health</th><th>Update level</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><div class="admin-product-identity"><img src="<?= htmlspecialchars($product['image_url'] ?? '/Shopping%20Cart/assets/images/stationery.svg', ENT_QUOTES, 'UTF-8') ?>" alt=""><span><strong><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></strong><small><?= htmlspecialchars($product['full_product_id'], ENT_QUOTES, 'UTF-8') ?></small></span></div></td>
                                    <td><strong class="admin-stock-number"><?= (int) $product['stock'] ?></strong><span class="admin-table-muted"> units</span></td>
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
        </section>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

