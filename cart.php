<?php
$pageTitle = 'Your Cart - Arts';
$basePath = '/Shopping%20Cart';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Mock Cart Data
// We can use a query parameter ?empty=1 to simulate the empty state
$isEmpty = isset($_GET['empty']) && $_GET['empty'] == '1';

$cartItems = [];

if (!$isEmpty) {
    $cartItems = [
        [
            'id' => 'ART1001',
            'name' => 'Lavender Dream Journal',
            'price' => 24.00,
            'quantity' => 2,
            'image' => $basePath . '/assets/images/stationery.svg'
        ],
        [
            'id' => 'ART1004',
            'name' => 'Ceramic Gift Box',
            'price' => 28.00,
            'quantity' => 1,
            'image' => $basePath . '/assets/images/gifts.svg'
        ],
        [
            'id' => 'ART1013',
            'name' => 'Rose Gold Pen Set Trio',
            'price' => 18.50,
            'quantity' => 1,
            'image' => $basePath . '/assets/images/stationery.svg'
        ]
    ];
}

$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += ($item['price'] * $item['quantity']);
}
$shipping = ($subtotal > 50 || $subtotal == 0) ? 0 : 5.00;
$total = $subtotal + $shipping;
?>

<main class="cart-page">
    <div class="container">
        <h1 class="cart-page-title">Your Shopping Cart</h1>
        
        <?php if (empty($cartItems)): ?>
            <div class="cart-empty-state">
                <div class="empty-icon">🛒</div>
                <h2>Your cart is empty</h2>
                <p>Browse our products and find something you'll love.</p>
                <a href="<?= $basePath ?>/products.php" class="primary-button">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="cart-layout">
                <!-- Cart Items (Left) -->
                <div class="cart-items-column">
                    <div class="cart-items-list">
                        <!-- Desktop Header -->
                        <div class="cart-list-header">
                            <div class="col-product">Product</div>
                            <div class="col-price">Price</div>
                            <div class="col-qty">Quantity</div>
                            <div class="col-subtotal">Subtotal</div>
                            <div class="col-action"></div>
                        </div>
                        
                        <?php foreach ($cartItems as $item): ?>
                            <?php $itemSubtotal = $item['price'] * $item['quantity']; ?>
                            <div class="cart-item" data-id="<?= htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8') ?>">
                                <div class="col-product">
                                    <a href="<?= $basePath ?>/product.php?id=<?= urlencode($item['id']) ?>" class="cart-item-image">
                                        <img src="<?= htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?>">
                                    </a>
                                    <div class="cart-item-details">
                                        <h3 class="cart-item-name">
                                            <a href="<?= $basePath ?>/product.php?id=<?= urlencode($item['id']) ?>"><?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?></a>
                                        </h3>
                                        <span class="cart-item-id">SKU: <?= htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8') ?></span>
                                        <!-- Mobile only price -->
                                        <div class="cart-item-price-mobile">
                                            $<?= number_format($item['price'], 2) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-price">
                                    $<?= number_format($item['price'], 2) ?>
                                </div>
                                <div class="col-qty">
                                    <div class="quantity-selector cart-qty">
                                        <button type="button" class="qty-btn qty-minus" aria-label="Decrease quantity">−</button>
                                        <input type="number" class="qty-input" value="<?= $item['quantity'] ?>" min="1" max="99" aria-label="Product quantity">
                                        <button type="button" class="qty-btn qty-plus" aria-label="Increase quantity">+</button>
                                    </div>
                                </div>
                                <div class="col-subtotal">
                                    <span class="subtotal-val">$<?= number_format($itemSubtotal, 2) ?></span>
                                </div>
                                <div class="col-action">
                                    <button type="button" class="cart-remove-btn" aria-label="Remove item" title="Remove item">×</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="cart-actions-bottom">
                        <a href="<?= $basePath ?>/products.php" class="secondary-button text-link">← Continue Shopping</a>
                    </div>
                </div>

                <!-- Order Summary (Right) -->
                <div class="cart-summary-column">
                    <div class="order-summary-card">
                        <h2>Order Summary</h2>
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>$<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Delivery</span>
                            <span><?= $shipping == 0 ? 'Free' : '$' . number_format($shipping, 2) ?></span>
                        </div>
                        <div class="summary-divider"></div>
                        <div class="summary-row summary-total">
                            <span>Total</span>
                            <span>$<?= number_format($total, 2) ?></span>
                        </div>
                        
                        <a href="<?= $basePath ?>/checkout.php" class="primary-button proceed-checkout-btn">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Basic frontend-only interactions for mock presentation
    
    // Quantity adjustments
    const qtyWrappers = document.querySelectorAll('.cart-qty');
    qtyWrappers.forEach(wrapper => {
        const input = wrapper.querySelector('.qty-input');
        const btnMinus = wrapper.querySelector('.qty-minus');
        const btnPlus = wrapper.querySelector('.qty-plus');
        
        btnMinus.addEventListener('click', () => {
            let val = parseInt(input.value, 10) || 1;
            if (val > 1) {
                input.value = val - 1;
            }
        });
        
        btnPlus.addEventListener('click', () => {
            let val = parseInt(input.value, 10) || 1;
            if (val < 99) {
                input.value = val + 1;
            }
        });
    });

    // Remove item simulation
    const removeBtns = document.querySelectorAll('.cart-remove-btn');
    removeBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const itemRow = e.target.closest('.cart-item');
            if (itemRow) {
                itemRow.style.opacity = '0';
                itemRow.style.transform = 'scale(0.95)';
                itemRow.style.transition = 'all 0.3s ease';
                setTimeout(() => {
                    itemRow.remove();
                    // If no items remain visually, redirect to empty state.
                    if (document.querySelectorAll('.cart-item').length === 0) {
                        window.location.href = '?empty=1';
                    }
                }, 300);
            }
        });
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
