<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

$pageTitle = 'Manage Products - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';

$db = get_db_connection();
$activePage = 'products.php';
$adminNav = [
    'index.php' => 'Dashboard', 'products.php' => 'Products', 'inventory.php' => 'Inventory',
    'orders.php' => 'Orders', 'customers.php' => 'Customers', 'employees.php' => 'Employees',
    'payments.php' => 'Payments', 'returns.php' => 'Returns', 'feedback.php' => 'Feedback', 'faq.php' => 'FAQ'
];

$successMessage = '';
$errorMessage = '';
$categories = $db->query('SELECT category_id, name FROM categories ORDER BY name ASC')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        $code = trim((string) ($_POST['product_code'] ?? ''));
        $number = trim((string) ($_POST['product_number'] ?? ''));
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $name = trim((string) ($_POST['name'] ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));
        $price = (float) ($_POST['price'] ?? 0);
        $stock = (int) ($_POST['stock'] ?? 0);
        $status = in_array($_POST['status'] ?? '', ['active', 'inactive'], true) ? $_POST['status'] : 'active';
        $imageUrl = trim((string) ($_POST['image_url'] ?? '')) ?: '/Shopping%20Cart/assets/images/stationery.svg';

        if (!preg_match('/^\d{2}$/', $code) || !preg_match('/^\d{5}$/', $number) || $categoryId <= 0 || $name === '' || $price < 0 || $stock < 0) {
            $errorMessage = 'Please enter a valid product record.';
        } else {
            $check = $db->prepare('SELECT product_id FROM products WHERE product_code = :code AND product_number = :number LIMIT 1');
            $check->execute([':code' => $code, ':number' => $number]);
            if ($check->fetch()) {
                $errorMessage = 'A product with that 7-digit ID already exists.';
            } else {
                $insert = $db->prepare('INSERT INTO products (product_code, product_number, category_id, name, description, price, stock, image_url, status) VALUES (:code, :number, :category_id, :name, :description, :price, :stock, :image_url, :status)');
                $insert->execute([
                    ':code' => $code,
                    ':number' => $number,
                    ':category_id' => $categoryId,
                    ':name' => $name,
                    ':description' => $description,
                    ':price' => $price,
                    ':stock' => $stock,
                    ':image_url' => $imageUrl,
                    ':status' => $status,
                ]);
                $successMessage = 'Product created successfully.';
            }
        }
    }

    if (isset($_POST['delete_product'])) {
        $productId = (int) ($_POST['product_id'] ?? 0);
        if ($productId > 0) {
            $db->prepare('DELETE FROM products WHERE product_id = :product_id')->execute([':product_id' => $productId]);
            $successMessage = 'Product deleted successfully.';
        }
    }

    if (isset($_POST['edit_product'])) {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $name = trim((string) ($_POST['name'] ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));
        $price = (float) ($_POST['price'] ?? 0);
        $stock = (int) ($_POST['stock'] ?? 0);
        $status = in_array($_POST['status'] ?? '', ['active', 'inactive'], true) ? $_POST['status'] : 'active';

        if ($productId > 0 && $categoryId > 0 && $name !== '' && $price >= 0 && $stock >= 0) {
            $update = $db->prepare('UPDATE products SET category_id = :category_id, name = :name, description = :description, price = :price, stock = :stock, status = :status WHERE product_id = :product_id');
            $update->execute([
                ':category_id' => $categoryId,
                ':name' => $name,
                ':description' => $description,
                ':price' => $price,
                ':stock' => $stock,
                ':status' => $status,
                ':product_id' => $productId,
            ]);
            $successMessage = 'Product updated successfully.';
        } else {
            $errorMessage = 'Invalid product update data.';
        }
    }
}

$products = $db->query('SELECT p.*, c.name AS category_name FROM products p INNER JOIN categories c ON c.category_id = p.category_id ORDER BY p.product_id DESC')->fetchAll();
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
                <?php if ($successMessage !== ''): ?><div class="alert-box" style="margin-bottom:16px;"><?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                <?php if ($errorMessage !== ''): ?><div class="error-box" style="margin-bottom:16px;"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?= htmlspecialchars($product['full_product_id'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($product['category_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= format_currency((float) $product['price']) ?></td>
                                    <td><?= (int) $product['stock'] ?></td>
                                    <td><span class="status-badge <?= get_status_badge_class($product['status'], 'order') ?>"><?= ucfirst(htmlspecialchars($product['status'], ENT_QUOTES, 'UTF-8')) ?></span></td>
                                    <td>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="product_id" value="<?= (int) $product['product_id'] ?>">
                                            <input type="hidden" name="name" value="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>">
                                            <input type="hidden" name="description" value="<?= htmlspecialchars($product['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                            <input type="hidden" name="category_id" value="<?= (int) $product['category_id'] ?>">
                                            <input type="hidden" name="price" value="<?= (float) $product['price'] ?>">
                                            <input type="hidden" name="stock" value="<?= (int) $product['stock'] ?>">
                                            <input type="hidden" name="status" value="<?= htmlspecialchars($product['status'], ENT_QUOTES, 'UTF-8') ?>">
                                            <button type="submit" name="delete_product" value="1" class="text-button danger" onclick="return confirm('Delete this product?');">Delete</button>
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

<div id="addProductModal" class="mock-modal" style="display:none;">
    <div class="mock-modal-content">
        <h3>Add Product</h3>
        <form method="POST">
            <input type="hidden" name="add_product" value="1">
            <div class="form-row">
                <div class="form-group"><label>Product Code</label><input type="text" name="product_code" class="form-input" pattern="[0-9]{2}" maxlength="2" required></div>
                <div class="form-group"><label>Product Number</label><input type="text" name="product_number" class="form-input" pattern="[0-9]{5}" maxlength="5" required></div>
            </div>
            <div class="form-group"><label>Product Name</label><input type="text" name="name" class="form-input" required></div>
            <div class="form-group"><label>Category</label>
                <select name="category_id" class="form-select" required>
                    <option value="">Select category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= (int) $category['category_id'] ?>"><?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label>Description</label><textarea name="description" class="form-textarea"></textarea></div>
            <div class="form-row">
                <div class="form-group"><label>Price</label><input type="number" name="price" step="0.01" min="0" class="form-input" required></div>
                <div class="form-group"><label>Stock</label><input type="number" name="stock" min="0" class="form-input" required></div>
            </div>
            <div class="form-group"><label>Image URL</label><input type="text" name="image_url" class="form-input" placeholder="/Shopping%20Cart/assets/images/stationery.svg"></div>
            <div class="form-group"><label>Status</label><select name="status" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
            <div class="mock-modal-actions">
                <button type="submit" class="primary-button">Save Product</button>
                <button type="button" class="secondary-button" onclick="this.closest('.mock-modal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

