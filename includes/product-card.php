<?php
function renderProductCard(array $product): void {
    global $basePath;
    $id = htmlspecialchars($product['id'] ?? $product['full_product_id'] ?? '0100001', ENT_QUOTES, 'UTF-8');
    $badge = $product['badge'] ?? '';
    $category = htmlspecialchars($product['category'], ENT_QUOTES, 'UTF-8');
    $name = htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8');
    $price = htmlspecialchars($product['price'] ?? format_currency((float) ($product['price_numeric'] ?? 0)), ENT_QUOTES, 'UTF-8');
    $rating = $product['rating'] ?? 0;
    $stock = $product['stock_label'] ?? ($product['stock'] ?? 'In Stock');
    $image = htmlspecialchars($product['image'] ?? $product['image_url'] ?? '/Shopping%20Cart/assets/images/stationery.svg', ENT_QUOTES, 'UTF-8');
    $wishlist = $product['wishlist'] ?? '♡';
    $badgeClass = (strtolower($badge) === 'new') ? 'card-badge badge-new' : 'card-badge';
    $newLabel = $badge ? '<span class="' . $badgeClass . '">' . htmlspecialchars($badge, ENT_QUOTES, 'UTF-8') . '</span>' : '';
    $stars = '';
    for ($i = 0; $i < 5; $i++) {
        $stars .= $i < $rating ? '<span class="star filled">★</span>' : '<span class="star">★</span>';
    }

    // Map the free-text stock value to a small status class so it can be
    // color-coded (green / amber / red) without changing the data shape.
    $stockLower = strtolower($stock);
    $isOutOfStock = strpos($stockLower, 'out') !== false;
    $isLowStock = strpos($stockLower, 'low') !== false;
    if ($isOutOfStock) {
        $stockStatusClass = 'stock-out';
    } elseif ($isLowStock) {
        $stockStatusClass = 'stock-low';
    } else {
        $stockStatusClass = 'stock-in';
    }

    $imageWrapClass = 'product-image-wrap' . ($isOutOfStock ? ' is-out-of-stock' : '');
    $cartBtnClass = 'mini-cart-btn' . ($isOutOfStock ? ' is-disabled' : '');
    ?>
    <article class="product-card">
        <a href="<?= $basePath ?>/product.php?id=<?= $id ?>" class="<?= $imageWrapClass ?>">
            <?= $newLabel ?>
            <button type="button" class="wishlist-btn" aria-label="Add to wishlist"><?= htmlspecialchars($wishlist, ENT_QUOTES, 'UTF-8') ?></button>
            <img src="<?= $image ?>" alt="<?= $name ?>" loading="lazy">
        </a>

        <div class="product-meta">
            <span class="product-category"><?= $category ?></span>
            <h3><a href="<?= $basePath ?>/product.php?id=<?= $id ?>"><?= $name ?></a></h3>
            <div class="rating-row">
                <div class="stars" aria-label="Rated <?= $rating ?> out of 5"><?= $stars ?></div>
                <span class="rating-count">(<?= htmlspecialchars((string) ($product['reviews'] ?? 0), ENT_QUOTES, 'UTF-8') ?>)</span>
            </div>
            <div class="stock-line <?= $stockStatusClass ?>"><?= htmlspecialchars($stock, ENT_QUOTES, 'UTF-8') ?></div>
            <div class="product-bottom-row">
                <div class="price-wrap">
                    <span class="price"><?= $price ?></span>
                    <?php if (!empty($product['oldPrice'])): ?>
                        <span class="old-price"><?= htmlspecialchars($product['oldPrice'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                </div>
                <button type="button" class="<?= $cartBtnClass ?>" aria-label="Add to cart" <?= $isOutOfStock ? 'disabled aria-disabled="true"' : '' ?>>🛒</button>
            </div>
        </div>
    </article>
    <?php
}