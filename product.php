<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Product - Arts';
$basePath = '/Shopping%20Cart';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/product-card.php';

$requestedId = trim((string) ($_GET['id'] ?? ''));
$product = null;

if ($requestedId === '') {
    $product = get_all_products()[0] ?? null;
} else {
    $product = get_product_by_id($requestedId);
}

if ($product === null) {
    http_response_code(404);
    $product = [
        'id' => '0000000',
        'full_product_id' => '0000000',
        'name' => 'Product not found',
        'category' => 'Unavailable',
        'description' => 'The requested product could not be found in the catalog.',
        'price' => format_currency(0),
        'price_numeric' => 0,
        'stock' => 'Out of Stock',
        'stock_count' => 0,
        'image' => '/Shopping%20Cart/assets/images/stationery.svg',
        'rating' => 0,
        'reviews' => 0,
        'badge' => '',
        'features' => ['Please check the catalog and try another product.']
    ];
} else {
    $product['id'] = $product['full_product_id'];
    $product['category'] = $product['category_name'] ?? 'Uncategorized';
    $product['image'] = $product['image_url'] ?? '/Shopping%20Cart/assets/images/stationery.svg';
    $product['stock'] = normalize_product_stock_label((int) $product['stock_count']);
    $product['badge'] = ((int) $product['stock_count'] > 0 && (int) $product['product_id'] % 3 === 1) ? 'New' : '';
    $product['rating'] = min(5, max(3, 3 + ((int) $product['product_id'] % 3)));
    $product['reviews'] = 30 + ((int) $product['product_id'] * 11) % 120;
    $product['features'] = [
        ($product['price_numeric'] > 20 ? 'Premium quality finish' : 'Everyday essentials'),
        'Securely packed for delivery',
        'Stock availability updated from the database'
    ];
    $pageTitle = htmlspecialchars($product['name']) . ' - Arts';
}

$relatedProducts = get_all_products($product['category'] ?? null, null);
if (count($relatedProducts) > 4) {
    $relatedProducts = array_slice($relatedProducts, 0, 4);
}

$stockLower = strtolower((string) $product['stock']);
$isOutOfStock = strpos($stockLower, 'out') !== false;
$stockStatusClass = 'stock-in';
if ($isOutOfStock) {
    $stockStatusClass = 'stock-out';
} elseif (strpos($stockLower, 'low') !== false) {
    $stockStatusClass = 'stock-low';
}

$stars = '';
for ($i = 0; $i < 5; $i++) {
    $stars .= $i < ($product['rating'] ?? 0) ? '<span class="star filled">★</span>' : '<span class="star">★</span>';
}
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap');

/* Premium Aesthetics for Product Page */
.product-page {
    font-family: 'Outfit', sans-serif;
    background: #fdfcff;
    position: relative;
    overflow-x: hidden;
    padding-bottom: 80px;
    padding-top: 20px;
}

.product-page::before {
    content: '';
    position: absolute;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(95,51,168,0.06) 0%, transparent 70%);
    top: -100px;
    left: -200px;
    border-radius: 50%;
    filter: blur(60px);
    z-index: 0;
    pointer-events: none;
}

.product-page-inner {
    position: relative;
    z-index: 1;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes floatImage {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-8px); }
    100% { transform: translateY(0px); }
}

.shop-breadcrumb {
    font-size: 0.85rem;
    color: var(--text-soft);
    margin-bottom: 30px;
    animation: fadeInUp 0.5s ease-out both;
}

.shop-breadcrumb a {
    color: var(--text-soft);
    transition: color 0.2s ease;
}

.shop-breadcrumb a:hover {
    color: var(--brand-primary);
}

.product-details-grid {
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    gap: 60px;
    align-items: start;
    margin-bottom: 80px;
}

.product-gallery {
    animation: fadeInUp 0.6s ease-out 0.1s both;
}

.product-main-image {
    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    border: 1px solid rgba(255, 255, 255, 0.8);
    padding: 60px;
    box-shadow: 0 25px 50px rgba(0,0,0,0.04);
    display: flex;
    align-items: center;
    justify-content: center;
    height: 560px;
}

.product-main-image img {
    width: 100%;
    max-height: 100%;
    object-fit: contain;
    filter: drop-shadow(0 20px 30px rgba(0,0,0,0.08));
    animation: floatImage 8s ease-in-out infinite;
}

.product-main-image.is-out-of-stock img {
    opacity: 0.4;
    filter: grayscale(100%);
    animation: none;
}

.product-info {
    animation: fadeInUp 0.6s ease-out 0.2s both;
    padding-top: 20px;
}

.product-id {
    display: inline-block;
    padding: 6px 14px;
    background: rgba(95, 51, 168, 0.05);
    color: var(--brand-primary);
    border-radius: 999px;
    font-weight: 600;
    font-size: 0.8rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    margin-bottom: 16px;
}

.product-title {
    font-size: clamp(2.2rem, 3.5vw, 3rem);
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1.1;
    margin: 0 0 16px;
    letter-spacing: -0.02em;
}

.product-meta-row {
    margin-bottom: 24px;
}

.rating-row {
    display: flex;
    align-items: center;
    gap: 10px;
}

.stars {
    color: #f8bf4a;
    font-size: 1.1rem;
    letter-spacing: 2px;
}

.rating-count {
    font-family: 'Inter', sans-serif;
    color: var(--text-soft);
    font-size: 0.95rem;
}

.product-price-row {
    margin-bottom: 16px;
}

.product-price {
    font-size: 2.2rem;
    font-weight: 700;
    color: var(--brand-primary-dark);
}

.product-description {
    font-family: 'Inter', sans-serif;
    font-size: 1.1rem;
    color: #555;
    line-height: 1.7;
    margin-bottom: 30px;
    border-top: 1px solid rgba(0,0,0,0.06);
    padding-top: 24px;
    margin-top: 24px;
}

.product-features {
    margin-top: 20px;
    padding-left: 20px;
    color: #444;
}

.product-features li {
    margin-bottom: 8px;
}

.product-actions {
    display: flex;
    gap: 16px;
    align-items: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
}

.quantity-selector {
    display: flex;
    align-items: center;
    background: #fff;
    border: 1px solid rgba(0,0,0,0.1);
    border-radius: 12px;
    overflow: hidden;
    height: 52px;
}

.qty-btn {
    width: 44px;
    height: 100%;
    background: transparent;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
    color: var(--text);
    transition: background 0.2s;
}

.qty-btn:hover {
    background: #f4f4f4;
}

.qty-input {
    width: 50px;
    height: 100%;
    border: none;
    text-align: center;
    font-family: 'Inter', sans-serif;
    font-size: 1.05rem;
    font-weight: 600;
    border-left: 1px solid rgba(0,0,0,0.08);
    border-right: 1px solid rgba(0,0,0,0.08);
    -moz-appearance: textfield;
}
.qty-input::-webkit-outer-spin-button,
.qty-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.add-to-cart-btn {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 52px;
    background: linear-gradient(135deg, var(--brand-primary), #7344be);
    color: #fff;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.05rem;
    transition: all 0.3s ease;
    box-shadow: 0 10px 25px rgba(95, 51, 168, 0.3);
    border: none;
    cursor: pointer;
}

.add-to-cart-btn:hover:not([disabled]) {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(95, 51, 168, 0.4);
}

.add-to-cart-btn[disabled] {
    background: #dcdcdc;
    box-shadow: none;
    color: #999;
    cursor: not-allowed;
}

.product-secondary-actions {
    margin-top: 16px;
}

.wishlist-text-btn {
    background: transparent;
    border: none;
    font-family: 'Inter', sans-serif;
    font-size: 0.95rem;
    color: var(--text-soft);
    font-weight: 500;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: color 0.2s;
}

.wishlist-text-btn:hover {
    color: var(--brand-primary);
}

.related-products {
    padding-top: 60px;
    border-top: 1px solid rgba(0,0,0,0.05);
    animation: fadeInUp 0.6s ease-out 0.4s both;
}

.section-heading {
    font-size: clamp(2rem, 3vw, 2.5rem);
    font-weight: 700;
    text-align: center;
    margin-bottom: 40px;
    color: #1a1a1a;
    letter-spacing: -0.02em;
}

/* Related Products Grid Styling */
.shop-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(220px, 1fr));
    gap: 26px;
}

.shop-grid .product-card {
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.03);
    box-shadow: 0 8px 24px rgba(0,0,0,0.02);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    background: #fff;
    height: 100%;
}

.shop-grid .product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(95, 51, 168, 0.08);
    border-color: rgba(95, 51, 168, 0.1);
}

@media (max-width: 980px) {
    .product-details-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    .product-main-image {
        height: auto;
        padding: 40px;
    }
    .shop-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
    .shop-grid { grid-template-columns: 1fr; }
}
</style>

<main class="product-page">
    <div class="container product-page-inner">
        <!-- Breadcrumb -->
        <p class="shop-breadcrumb">
            <a href="<?= $basePath ?>/index.php">Home</a> / 
            <a href="<?= $basePath ?>/products.php">Shop</a> / 
            <a href="<?= $basePath ?>/products.php?category=<?= urlencode($product['category']) ?>"><?= htmlspecialchars($product['category']) ?></a> / 
            <?= htmlspecialchars($product['name']) ?>
        </p>

        <!-- Product Details Layout -->
        <div class="product-details-grid">
            
            <!-- Left: Image Gallery -->
            <div class="product-gallery">
                <div class="product-main-image <?= $isOutOfStock ? 'is-out-of-stock' : '' ?>">
                    <img src="<?= htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
            </div>

            <!-- Right: Product Info -->
            <div class="product-info">
                <span class="product-id">SKU: <?= htmlspecialchars($product['id']) ?></span>
                <h1 class="product-title"><?= htmlspecialchars($product['name']) ?></h1>
                
                <div class="product-meta-row">
                    <div class="rating-row">
                        <div class="stars" aria-label="Rated <?= $product['rating'] ?> out of 5"><?= $stars ?></div>
                        <span class="rating-count">(<?= htmlspecialchars((string)$product['reviews']) ?> reviews)</span>
                    </div>
                </div>

                <div class="product-price-row">
                    <span class="product-price"><?= htmlspecialchars($product['price']) ?></span>
                </div>

                <div class="stock-line <?= $stockStatusClass ?>"><?= htmlspecialchars($product['stock']) ?></div>

                <div class="product-description">
                    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                    <ul class="product-features">
                        <?php foreach ($product['features'] as $feature): ?>
                            <li><?= htmlspecialchars($feature) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <form method="post" action="<?= $basePath ?>/cart.php" class="product-actions" style="display:flex; align-items:center; gap:16px;">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars((string) ($product['id'] ?? $product['full_product_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                    <div class="quantity-selector">
                        <button type="button" class="qty-btn qty-minus" aria-label="Decrease quantity">−</button>
                        <input type="number" name="quantity" class="qty-input" value="1" min="1" max="99" aria-label="Product quantity">
                        <button type="button" class="qty-btn qty-plus" aria-label="Increase quantity">+</button>
                    </div>

                    <button type="submit" class="add-to-cart-btn" <?= $isOutOfStock ? 'disabled aria-disabled="true"' : '' ?>>
                        Add to Cart
                    </button>
                </form>

                <div class="product-secondary-actions">
                    <button type="button" class="wishlist-text-btn">♡ Add to Wishlist</button>
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        <section class="related-products">
            <h2 class="section-heading">You May Also Like</h2>
            <div class="shop-grid">
                <?php foreach ($relatedProducts as $related): ?>
                    <?php renderProductCard($related); ?>
                <?php endforeach; ?>
            </div>
        </section>

    </div>
</main>

<script>
// Simple quantity selector logic
document.addEventListener('DOMContentLoaded', function() {
    const qtyInput = document.querySelector('.qty-input');
    const btnMinus = document.querySelector('.qty-minus');
    const btnPlus = document.querySelector('.qty-plus');

    if (btnMinus && btnPlus && qtyInput) {
        btnMinus.addEventListener('click', function() {
            let val = parseInt(qtyInput.value, 10) || 1;
            if (val > 1) {
                qtyInput.value = val - 1;
            }
        });

        btnPlus.addEventListener('click', function() {
            let val = parseInt(qtyInput.value, 10) || 1;
            if (val < 99) {
                qtyInput.value = val + 1;
            }
        });
        
        qtyInput.addEventListener('change', function() {
            let val = parseInt(qtyInput.value, 10);
            if (isNaN(val) || val < 1) {
                qtyInput.value = 1;
            } else if (val > 99) {
                qtyInput.value = 99;
            }
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
