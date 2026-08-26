<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

$pageTitle = 'Manage Products - Arts';
$basePath = '/Shopping-Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
require_once dirname(__DIR__) . '/includes/admin-shell.php';

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

function handle_product_image_upload(string $fieldName): array
{
    global $basePath;

    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return ['path' => null, 'error' => null];
    }

    $file = $_FILES[$fieldName];
    if ($file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
        return ['path' => null, 'error' => 'The product image could not be uploaded.'];
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'svg', 'webp'];
    if (!in_array($extension, $allowedExtensions, true)) {
        return ['path' => null, 'error' => 'Product images must be JPG, JPEG, PNG, SVG, or WebP files.'];
    }

    if ($extension === 'svg') {
        $svg = file_get_contents($file['tmp_name']);
        if ($svg === false || !preg_match('/<svg\b[^>]*>/i', $svg) || preg_match('/<\/?(script|iframe|object|embed)\b|on[a-z]+\s*=|javascript\s*:/i', $svg)) {
            return ['path' => null, 'error' => 'The uploaded SVG image is not valid or contains unsafe content.'];
        }
    } else {
        $imageInfo = @getimagesize($file['tmp_name']);
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if ($imageInfo === false || !in_array($imageInfo['mime'] ?? '', $allowedMimeTypes, true)) {
            return ['path' => null, 'error' => 'The uploaded file is not a valid JPG, PNG, or WebP image.'];
        }
    }

    $imageDirectory = dirname(__DIR__) . '/assets/images';
    $safeName = 'product-' . bin2hex(random_bytes(12)) . '.' . $extension;
    $destination = $imageDirectory . '/' . $safeName;
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return ['path' => null, 'error' => 'The product image could not be saved.'];
    }

    return ['path' => $basePath . '/assets/images/' . $safeName, 'error' => null];
}

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
        $imageUrl = trim((string) ($_POST['image_url'] ?? '')) ?: '/Shopping-Cart/assets/images/stationery.svg';
        $upload = handle_product_image_upload('image_file');

        if ($upload['error'] !== null) {
            $errorMessage = $upload['error'];
        } elseif (!preg_match('/^\d{2}$/', $code) || !preg_match('/^\d{5}$/', $number) || $categoryId <= 0 || $name === '' || $price < 0 || $stock < 0) {
            $errorMessage = 'Please enter a valid product record.';
        } else {
            $imageUrl = $upload['path'] ?? $imageUrl;
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
        $upload = handle_product_image_upload('image_file');

        if ($upload['error'] !== null) {
            $errorMessage = $upload['error'];
        } elseif ($productId > 0 && $categoryId > 0 && $name !== '' && $price >= 0 && $stock >= 0) {
            $current = $db->prepare('SELECT image_url FROM products WHERE product_id = :product_id LIMIT 1');
            $current->execute([':product_id' => $productId]);
            $currentProduct = $current->fetch();
            if (!$currentProduct) {
                $errorMessage = 'Product not found.';
            } else {
                $imageUrl = $upload['path'] ?? ($currentProduct['image_url'] ?: '/Shopping-Cart/assets/images/stationery.svg');
                $update = $db->prepare('UPDATE products SET category_id = :category_id, name = :name, description = :description, price = :price, stock = :stock, image_url = :image_url, status = :status WHERE product_id = :product_id');
                $update->execute([
                ':category_id' => $categoryId,
                ':name' => $name,
                ':description' => $description,
                ':price' => $price,
                ':stock' => $stock,
                ':image_url' => $imageUrl,
                ':status' => $status,
                ':product_id' => $productId,
                ]);
                $successMessage = 'Product updated successfully.';
            }
        } else {
            $errorMessage = 'Invalid product update data.';
        }
    }
}

$products = $db->query('SELECT p.*, c.name AS category_name FROM products p INNER JOIN categories c ON c.category_id = p.category_id ORDER BY p.product_id DESC')->fetchAll();
?>
<main class="admin-app">
    <div class="admin-layout">
        <?php render_admin_sidebar($adminNav, $activePage, $basePath); ?>
        <section class="admin-main">
            <div class="admin-page-header admin-page-header-with-action">
                <div><span class="admin-eyebrow">Catalog workspace</span><h1>Products</h1><p>Manage the catalog, pricing, availability, and product presentation.</p></div>
                <button class="primary-button" onclick="document.getElementById('addProductModal').style.display='flex'">Add product</button>
            </div>
                <?php if ($successMessage !== ''): ?><div class="alert-box" style="margin-bottom:16px;"><?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                <?php if ($errorMessage !== ''): ?><div class="error-box" style="margin-bottom:16px;"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <?php
                                $productDetails = [
                                    'productId' => (int) $product['product_id'],
                                    'fullProductId' => $product['full_product_id'],
                                    'productCode' => $product['product_code'],
                                    'productNumber' => $product['product_number'],
                                    'categoryId' => (int) $product['category_id'],
                                    'name' => $product['name'],
                                    'category' => $product['category_name'],
                                    'description' => $product['description'] ?? '',
                                    'price' => format_currency((float) $product['price']),
                                    'priceNumeric' => (float) $product['price'],
                                    'stock' => (int) $product['stock'],
                                    'status' => $product['status'],
                                    'image' => $product['image_url'] ?: '/Shopping-Cart/assets/images/stationery.svg',
                                ];
                                ?>
                                <tr class="admin-product-row" tabindex="0" role="button" aria-label="View details for <?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>" data-product="<?= htmlspecialchars(json_encode($productDetails, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>">
                                    <td><div class="admin-product-identity"><img src="<?= htmlspecialchars($product['image_url'] ?: '/Shopping-Cart/assets/images/stationery.svg', ENT_QUOTES, 'UTF-8') ?>" alt=""><span><strong><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></strong><small><?= htmlspecialchars($product['full_product_id'], ENT_QUOTES, 'UTF-8') ?></small></span></div></td>
                                    <td><?= htmlspecialchars($product['category_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= format_currency((float) $product['price']) ?></td>
                                    <td><?= (int) $product['stock'] ?></td>
                                    <td><span class="status-badge <?= get_status_badge_class($product['status'], 'order') ?>"><?= ucfirst(htmlspecialchars($product['status'], ENT_QUOTES, 'UTF-8')) ?></span></td>
                                    <td>
                                        <button type="button" class="secondary-button" onclick="openEditProduct(this.closest('.admin-product-row'))">Edit</button>
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
        </section>
    </div>
</main>

<div id="addProductModal" class="mock-modal" style="display:none;">
    <div class="mock-modal-content add-product-modal-content">
        <h3>Add Product</h3>
        <form method="POST" enctype="multipart/form-data">
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
            <div class="form-group"><label>Image URL</label><input type="text" name="image_url" class="form-input" placeholder="/Shopping-Cart/assets/images/stationery.svg"></div>
            <div class="form-group"><label for="addProductImage">Upload Image</label><input type="file" id="addProductImage" name="image_file" class="form-input" accept=".jpg,.jpeg,.png,.svg,.webp,image/jpeg,image/png,image/svg+xml,image/webp"></div>
            <div class="form-group"><label>Status</label><select name="status" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
            <div class="mock-modal-actions">
                <button type="submit" class="primary-button">Save Product</button>
                <button type="button" class="secondary-button" onclick="this.closest('.mock-modal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div id="editProductModal" class="mock-modal" style="display:none;">
    <div class="mock-modal-content add-product-modal-content">
        <h3>Edit Product</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="edit_product" value="1">
            <input type="hidden" id="editProductId" name="product_id" value="">
            <div class="form-group"><label>Product ID</label><input type="text" id="editProductFullId" class="form-input" readonly></div>
            <div class="form-group"><label>Product Name</label><input type="text" id="editProductName" name="name" class="form-input" required></div>
            <div class="form-group"><label>Category</label>
                <select id="editProductCategory" name="category_id" class="form-select" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= (int) $category['category_id'] ?>"><?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label>Description</label><textarea id="editProductDescription" name="description" class="form-textarea"></textarea></div>
            <div class="form-row">
                <div class="form-group"><label>Price</label><input type="number" id="editProductPrice" name="price" step="0.01" min="0" class="form-input" required></div>
                <div class="form-group"><label>Stock</label><input type="number" id="editProductStock" name="stock" min="0" class="form-input" required></div>
            </div>
            <div class="form-group"><label>Current Image</label><img id="editProductCurrentImage" class="edit-product-current-image" src="" alt=""></div>
            <div class="form-group"><label for="editProductImage">Replace Image</label><input type="file" id="editProductImage" name="image_file" class="form-input" accept=".jpg,.jpeg,.png,.svg,.webp,image/jpeg,image/png,image/svg+xml,image/webp"></div>
            <div class="form-group"><label>Status</label><select id="editProductStatus" name="status" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
            <div class="mock-modal-actions">
                <button type="submit" class="primary-button">Save Changes</button>
                <button type="button" class="secondary-button" onclick="this.closest('.mock-modal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div id="productDetailsModal" class="mock-modal product-details-modal" style="display:none;" aria-hidden="true">
    <div class="mock-modal-content product-details-modal-content" role="dialog" aria-modal="true" aria-labelledby="productDetailsTitle">
        <button type="button" class="product-details-close" aria-label="Close product details" onclick="closeProductDetails()">&times;</button>
        <div class="product-details-layout">
            <div class="product-details-image-wrap">
                <img id="productDetailsImage" src="" alt="">
            </div>
            <div class="product-details-copy">
                <span class="admin-eyebrow">Product details</span>
                <h3 id="productDetailsTitle"></h3>
                <p id="productDetailsDescription" class="product-details-description"></p>
                <dl class="product-details-list">
                    <div><dt>Product ID</dt><dd id="productDetailsId"></dd></div>
                    <div><dt>Category</dt><dd id="productDetailsCategory"></dd></div>
                    <div><dt>Price</dt><dd id="productDetailsPrice"></dd></div>
                    <div><dt>Availability</dt><dd id="productDetailsAvailability"></dd></div>
                    <div><dt>Status</dt><dd id="productDetailsStatus"></dd></div>
                </dl>
            </div>
        </div>
    </div>
</div>

<script>
function closeProductDetails() {
    const modal = document.getElementById('productDetailsModal');
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
}

function openEditProduct(row) {
    const product = JSON.parse(row.dataset.product);
    document.getElementById('editProductId').value = product.productId;
    document.getElementById('editProductFullId').value = product.fullProductId;
    document.getElementById('editProductName').value = product.name;
    document.getElementById('editProductCategory').value = product.categoryId;
    document.getElementById('editProductDescription').value = product.description;
    document.getElementById('editProductPrice').value = product.priceNumeric;
    document.getElementById('editProductStock').value = product.stock;
    document.getElementById('editProductStatus').value = product.status;
    const image = document.getElementById('editProductCurrentImage');
    image.src = product.image;
    image.alt = product.name;
    document.getElementById('editProductImage').value = '';
    document.getElementById('editProductModal').style.display = 'flex';
}

function openProductDetails(row) {
    const product = JSON.parse(row.dataset.product);
    const modal = document.getElementById('productDetailsModal');
    const image = document.getElementById('productDetailsImage');
    image.src = product.image;
    image.alt = product.name;
    document.getElementById('productDetailsTitle').textContent = product.name;
    document.getElementById('productDetailsDescription').textContent = product.description || 'No description available.';
    document.getElementById('productDetailsId').textContent = product.fullProductId;
    document.getElementById('productDetailsCategory').textContent = product.category;
    document.getElementById('productDetailsPrice').textContent = product.price;
    document.getElementById('productDetailsAvailability').textContent = product.stock > 0 ? `${product.stock} in stock` : 'Out of stock';
    document.getElementById('productDetailsStatus').textContent = product.status.charAt(0).toUpperCase() + product.status.slice(1);
    modal.style.display = 'flex';
    modal.setAttribute('aria-hidden', 'false');
}

document.querySelectorAll('.admin-product-row').forEach((row) => {
    row.addEventListener('click', (event) => {
        if (event.target.closest('form, button, input, select, textarea, a')) {
            return;
        }
        openProductDetails(row);
    });
    row.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            openProductDetails(row);
        }
    });
});

document.getElementById('productDetailsModal').addEventListener('click', (event) => {
    if (event.target.id === 'productDetailsModal') {
        closeProductDetails();
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        closeProductDetails();
    }
});
</script>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

