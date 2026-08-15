<?php
$pageTitle = 'Lavender Dream Journal - Arts';
$basePath = '/Shopping%20Cart';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/product-card.php';

$requestedId = $_GET['id'] ?? 'ART1001';

// Master mock product data for the details page
$allProducts = [
    'ART1001' => ['category' => 'Stationery', 'name' => 'Lavender Dream Journal', 'price' => '$24.00', 'rating' => 5, 'reviews' => 128, 'stock' => 'In Stock', 'badge' => 'New', 'image' => $basePath . '/assets/images/stationery.svg', 'description' => "Capture your thoughts in our elegantly designed Lavender Dream Journal. Featuring a soft-touch hardcover, premium 120gsm dotted paper, and a lay-flat binding that makes writing a joy. Perfect for bullet journaling, daily reflections, or creative sketching.\n\nIncludes a matching ribbon bookmark and an expandable inner pocket.", 'features' => ['192 dotted pages', 'FSC-certified acid-free paper', 'Elastic closure band', 'Available in multiple soothing colors']],
    'ART1002' => ['category' => 'Stationery', 'name' => 'Classic Notebook', 'price' => '$16.00', 'rating' => 4, 'reviews' => 85, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/stationery.svg', 'description' => "A classic everyday notebook for all your notes.", 'features' => ['100 lined pages', 'Soft cover']],
    'ART1003' => ['category' => 'Stationery', 'name' => 'Premium Writing Set', 'price' => '$32.00', 'rating' => 5, 'reviews' => 42, 'stock' => 'Low Stock', 'badge' => '', 'image' => $basePath . '/assets/images/stationery.svg', 'description' => "The perfect gift for the writer in your life.", 'features' => ['3 pens', 'Leather case']],
    'ART1004' => ['category' => 'Gift Articles', 'name' => 'Ceramic Gift Box', 'price' => '$28.00', 'rating' => 4, 'reviews' => 64, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/gifts.svg', 'description' => "Beautifully crafted ceramic gift box.", 'features' => ['Hand-painted', 'Secure lid']],
    'ART1005' => ['category' => 'Gift Articles', 'name' => 'Decorative Gift Set', 'price' => '$45.00', 'rating' => 5, 'reviews' => 112, 'stock' => 'In Stock', 'badge' => 'Sale', 'image' => $basePath . '/assets/images/gifts.svg', 'description' => "A curated selection of decorative items.", 'features' => ['Gift wrapped', 'Includes card']],
    'ART1006' => ['category' => 'Greeting Cards', 'name' => 'Botanical Watercolor Card', 'price' => '$5.50', 'rating' => 5, 'reviews' => 24, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/cards.svg', 'description' => "Send a thoughtful message with our watercolor cards.", 'features' => ['Blank inside', 'Includes envelope']],
    'ART1007' => ['category' => 'Greeting Cards', 'name' => 'Birthday Greeting Card', 'price' => '$4.50', 'rating' => 4, 'reviews' => 18, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/cards.svg', 'description' => "Celebrate birthdays in style.", 'features' => ['Gold foil details', 'Premium paper']],
    'ART1008' => ['category' => 'Dolls', 'name' => 'Soft Plush Doll', 'price' => '$22.00', 'rating' => 5, 'reviews' => 76, 'stock' => 'In Stock', 'badge' => 'New', 'oldPrice' => '$28.00', 'image' => $basePath . '/assets/images/toys.svg', 'description' => "A cuddly companion for all ages.", 'features' => ['Machine washable', 'Embroidered eyes']],
    'ART1009' => ['category' => 'Dolls', 'name' => 'Mini Teddy Bear', 'price' => '$14.00', 'rating' => 4, 'reviews' => 32, 'stock' => 'Out of Stock', 'badge' => '', 'image' => $basePath . '/assets/images/toys.svg', 'description' => "A classic pocket-sized friend.", 'features' => ['Soft fur', 'Ribbon bow']],
    'ART1010' => ['category' => 'Files', 'name' => 'Document File Set', 'price' => '$12.00', 'rating' => 4, 'reviews' => 41, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/stationery.svg', 'description' => "Keep your papers organized.", 'features' => ['Set of 3', 'Label holders']],
    'ART1011' => ['category' => 'Files', 'name' => 'Premium Office File', 'price' => '$18.50', 'rating' => 5, 'reviews' => 58, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/stationery.svg', 'description' => "A sturdy file for important documents.", 'features' => ['Heavy duty rings', 'Leather-like finish']],
    'ART1012' => ['category' => 'Handbags', 'name' => 'Casual Handbag', 'price' => '$48.00', 'rating' => 4, 'reviews' => 29, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/gifts.svg', 'description' => "Your new everyday carry.", 'features' => ['Adjustable strap', 'Inner zip pocket']],
    'ART1013' => ['category' => 'Writing', 'name' => 'Rose Gold Pen Set Trio', 'price' => '$18.50', 'rating' => 4, 'reviews' => 85, 'stock' => 'Low Stock', 'badge' => 'New', 'image' => $basePath . '/assets/images/stationery.svg', 'description' => "Write in style.", 'features' => ['Black ink', 'Twist mechanism']],
    'ART1014' => ['category' => 'Lifestyle', 'name' => 'Ceramic Desk Organizer', 'price' => '$32.00', 'rating' => 4, 'reviews' => 12, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/gifts.svg', 'description' => "Keep your desk tidy.", 'features' => ['3 compartments', 'Non-slip base']],
    'ART1015' => ['category' => 'Greeting Cards', 'name' => 'Botanical Watercolor Card Set', 'price' => '$15.00', 'rating' => 5, 'reviews' => 46, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/cards.svg', 'description' => "A set of beautiful cards.", 'features' => ['Set of 5', 'Matching envelopes']],
];

if (!isset($allProducts[$requestedId])) {
    $requestedId = 'ART1001'; // Fallback
}
$product = $allProducts[$requestedId];
$product['id'] = $requestedId;
$pageTitle = htmlspecialchars($product['name']) . ' - Arts';

// Mock related products
$relatedProducts = [
    ['category' => 'Stationery', 'name' => 'Classic Notebook', 'price' => '$16.00', 'rating' => 4, 'reviews' => 85, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/stationery.svg'],
    ['category' => 'Stationery', 'name' => 'Premium Writing Set', 'price' => '$32.00', 'rating' => 5, 'reviews' => 42, 'stock' => 'Low Stock', 'badge' => '', 'image' => $basePath . '/assets/images/stationery.svg'],
    ['category' => 'Gift Articles', 'name' => 'Ceramic Gift Box', 'price' => '$28.00', 'rating' => 4, 'reviews' => 64, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/gifts.svg'],
    ['category' => 'Greeting Cards', 'name' => 'Botanical Watercolor Card', 'price' => '$5.50', 'rating' => 5, 'reviews' => 24, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/cards.svg'],
];

// Calculate stock class
$stockLower = strtolower($product['stock']);
$isOutOfStock = strpos($stockLower, 'out') !== false;
$stockStatusClass = 'stock-in';
if ($isOutOfStock) {
    $stockStatusClass = 'stock-out';
} elseif (strpos($stockLower, 'low') !== false) {
    $stockStatusClass = 'stock-low';
}

$stars = '';
for ($i = 0; $i < 5; $i++) {
    $stars .= $i < $product['rating'] ? '<span class="star filled">★</span>' : '<span class="star">★</span>';
}
?>

<main class="product-page">
    <div class="container">
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

                <div class="product-actions">
                    <div class="quantity-selector">
                        <button type="button" class="qty-btn qty-minus" aria-label="Decrease quantity">−</button>
                        <input type="number" class="qty-input" value="1" min="1" max="99" aria-label="Product quantity">
                        <button type="button" class="qty-btn qty-plus" aria-label="Increase quantity">+</button>
                    </div>

                    <button type="button" class="primary-button add-to-cart-btn" <?= $isOutOfStock ? 'disabled aria-disabled="true"' : '' ?>>
                        Add to Cart
                    </button>
                </div>

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
