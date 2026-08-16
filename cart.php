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

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap');

/* Premium Aesthetics for Cart Page */
.cart-page {
    font-family: 'Outfit', sans-serif;
    background: #fdfcff;
    position: relative;
    overflow-x: hidden;
    padding-bottom: 80px;
    padding-top: 20px;
    min-height: 70vh;
}

.cart-page::before {
    content: '';
    position: absolute;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(95,51,168,0.06) 0%, transparent 70%);
    top: -100px;
    right: -200px;
    border-radius: 50%;
    filter: blur(60px);
    z-index: 0;
    pointer-events: none;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.cart-page-title {
    font-size: clamp(2.5rem, 4vw, 3.5rem);
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 40px;
    letter-spacing: -0.02em;
    animation: fadeInUp 0.6s ease-out both;
}

.cart-empty-state {
    text-align: center;
    padding: 80px 20px;
    background: rgba(255,255,255,0.6);
    backdrop-filter: blur(10px);
    border-radius: 24px;
    border: 1px dashed rgba(0,0,0,0.1);
    animation: fadeInUp 0.6s ease-out 0.2s both;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 24px;
    opacity: 0.8;
}

.cart-empty-state h2 {
    font-size: 2rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 12px;
}

.cart-empty-state p {
    font-family: 'Inter', sans-serif;
    font-size: 1.1rem;
    color: #666;
    margin-bottom: 30px;
}

.primary-button-glow {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 14px 32px;
    background: linear-gradient(135deg, var(--brand-primary), #7344be);
    color: #fff;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.05rem;
    transition: all 0.3s ease;
    box-shadow: 0 10px 25px rgba(95, 51, 168, 0.3);
    border: none;
    cursor: pointer;
    text-decoration: none;
}

.primary-button-glow:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(95, 51, 168, 0.4);
}

.cart-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 40px;
    position: relative;
    z-index: 1;
}

.cart-items-column {
    animation: fadeInUp 0.6s ease-out 0.1s both;
}

.cart-items-list {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,1);
    box-shadow: 0 15px 35px rgba(0,0,0,0.03);
    padding: 24px;
}

.cart-list-header {
    display: grid;
    grid-template-columns: 3fr 1fr 1fr 1fr 40px;
    gap: 16px;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    font-weight: 600;
    color: var(--text-soft);
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.cart-item {
    display: grid;
    grid-template-columns: 3fr 1fr 1fr 1fr 40px;
    gap: 16px;
    align-items: center;
    padding: 24px 0;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.cart-item:last-child {
    border-bottom: none;
    padding-bottom: 8px;
}

.col-product { display: flex; gap: 20px; align-items: center; }

.cart-item-image {
    width: 90px;
    height: 90px;
    background: #f8f8f8;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    flex-shrink: 0;
    transition: transform 0.3s ease;
}

.cart-item-image:hover {
    transform: scale(1.05);
}

.cart-item-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.cart-item-name {
    margin: 0 0 4px;
    font-size: 1.15rem;
    font-weight: 600;
}

.cart-item-name a {
    color: var(--text);
    text-decoration: none;
    transition: color 0.2s;
}

.cart-item-name a:hover {
    color: var(--brand-primary);
}

.cart-item-id {
    font-family: 'Inter', sans-serif;
    color: var(--text-soft);
    font-size: 0.85rem;
}

.cart-item-price-mobile { display: none; }

.col-price {
    font-family: 'Inter', sans-serif;
    font-weight: 500;
    font-size: 1.05rem;
    color: var(--text);
}

.col-qty .quantity-selector {
    display: flex;
    align-items: center;
    background: #fff;
    border: 1px solid rgba(0,0,0,0.1);
    border-radius: 10px;
    overflow: hidden;
    height: 40px;
    width: 110px;
}

.col-qty .qty-btn {
    width: 32px;
    height: 100%;
    background: transparent;
    border: none;
    font-size: 1.1rem;
    cursor: pointer;
    color: var(--text);
    transition: background 0.2s;
}

.col-qty .qty-btn:hover { background: #f4f4f4; }

.col-qty .qty-input {
    width: 46px;
    height: 100%;
    border: none;
    text-align: center;
    font-family: 'Inter', sans-serif;
    font-size: 0.95rem;
    font-weight: 600;
    border-left: 1px solid rgba(0,0,0,0.08);
    border-right: 1px solid rgba(0,0,0,0.08);
    -moz-appearance: textfield;
}
.col-qty .qty-input::-webkit-outer-spin-button,
.col-qty .qty-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.col-subtotal .subtotal-val {
    font-family: 'Inter', sans-serif;
    font-weight: 600;
    font-size: 1.1rem;
    color: var(--text);
}

.cart-remove-btn {
    background: rgba(255,0,0,0.05);
    color: #ff4d4d;
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.cart-remove-btn:hover {
    background: #ff4d4d;
    color: #fff;
    transform: rotate(90deg);
}

.cart-actions-bottom {
    margin-top: 24px;
    display: flex;
    justify-content: flex-start;
}

.secondary-link {
    font-family: 'Inter', sans-serif;
    font-weight: 500;
    color: var(--text-soft);
    text-decoration: none;
    transition: color 0.2s;
}

.secondary-link:hover {
    color: var(--brand-primary);
}

.cart-summary-column {
    animation: fadeInUp 0.6s ease-out 0.2s both;
}

.order-summary-card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,1);
    box-shadow: 0 15px 40px rgba(95, 51, 168, 0.08);
    padding: 30px;
    position: sticky;
    top: 40px;
}

.order-summary-card h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 24px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    font-family: 'Inter', sans-serif;
    font-size: 1.05rem;
    color: var(--text-soft);
    margin-bottom: 16px;
}

.summary-row span:last-child {
    font-weight: 500;
    color: var(--text);
}

.summary-divider {
    height: 1px;
    background: rgba(0,0,0,0.06);
    margin: 20px 0;
}

.summary-total {
    font-family: 'Outfit', sans-serif;
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text);
}

.summary-total span:last-child {
    color: var(--brand-primary-dark);
    font-size: 1.5rem;
}

.proceed-checkout-btn {
    display: block;
    width: 100%;
    text-align: center;
    padding: 16px;
    margin-top: 30px;
    background: linear-gradient(135deg, var(--brand-primary), #7344be);
    color: #fff;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.1rem;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 10px 25px rgba(95, 51, 168, 0.3);
    border: none;
    cursor: pointer;
}

.proceed-checkout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(95, 51, 168, 0.4);
}

@media (max-width: 980px) {
    .cart-layout { grid-template-columns: 1fr; }
    .order-summary-card { position: static; }
}

@media (max-width: 768px) {
    .cart-list-header { display: none; }
    .cart-item {
        grid-template-columns: 1fr;
        grid-template-areas: 
            "product"
            "qty"
            "subtotal";
        gap: 12px;
        position: relative;
    }
    .col-product { grid-area: product; align-items: flex-start; }
    .col-price { display: none; }
    .cart-item-price-mobile { display: block; margin-top: 8px; font-weight: 500; color: var(--text); }
    .col-qty { grid-area: qty; }
    .col-subtotal { grid-area: subtotal; display: flex; justify-content: space-between; align-items: center; }
    .col-subtotal::before { content: 'Subtotal:'; font-family: 'Inter', sans-serif; font-size: 0.95rem; color: var(--text-soft); }
    .col-action { position: absolute; top: 20px; right: 0; }
}
</style>

<main class="cart-page">
    <div class="container">
        <h1 class="cart-page-title">Your Shopping Cart</h1>
        
        <?php if (empty($cartItems)): ?>
            <div class="cart-empty-state">
                <div class="empty-icon">🛒</div>
                <h2>Your cart is empty</h2>
                <p>Browse our products and find something you'll love.</p>
                <a href="<?= $basePath ?>/products.php" class="primary-button-glow">Continue Shopping</a>
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
                        <a href="<?= $basePath ?>/products.php" class="secondary-link">← Continue Shopping</a>
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
                        
                        <a href="<?= $basePath ?>/checkout.php" class="proceed-checkout-btn">Proceed to Checkout</a>
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
