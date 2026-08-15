<?php
$pageTitle = 'Checkout - Arts';
$basePath = '/Shopping%20Cart';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Mock Cart Data for Checkout Summary
$cartItems = [
    [
        'id' => 'ART1001',
        'name' => 'Lavender Dream Journal',
        'price' => 24.00,
        'quantity' => 2,
        'image' => $basePath . '/assets/images/stationery.svg'
    ],
    [
        'id' => 'ART1013',
        'name' => 'Rose Gold Pen Set Trio',
        'price' => 18.50,
        'quantity' => 1,
        'image' => $basePath . '/assets/images/stationery.svg'
    ]
];

$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += ($item['price'] * $item['quantity']);
}
$shipping = 5.00; // Default Mock Delivery Charge
$total = $subtotal + $shipping;
?>

<main class="checkout-page">
    <div class="container">
        
        <div class="checkout-header-actions">
            <h1 class="checkout-page-title">Checkout</h1>
            <a href="<?= $basePath ?>/cart.php" class="secondary-button text-link">← Back to Cart</a>
        </div>
        
        <div class="checkout-layout">
            <!-- Checkout Form (Left) -->
            <div class="checkout-form-column">
                <form action="#" method="POST" id="mockCheckoutForm">
                    
                    <!-- 1. Customer Information -->
                    <section class="checkout-section">
                        <h2 class="checkout-section-title">1. Customer Information</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">First Name <span class="required">*</span></label>
                                <input type="text" id="firstName" name="firstName" class="form-input" required placeholder="Jane">
                            </div>
                            <div class="form-group">
                                <label for="lastName">Last Name <span class="required">*</span></label>
                                <input type="text" id="lastName" name="lastName" class="form-input" required placeholder="Doe">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email Address <span class="required">*</span></label>
                                <input type="email" id="email" name="email" class="form-input" required placeholder="jane@example.com">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" class="form-input" placeholder="+1 (555) 000-0000">
                            </div>
                        </div>
                    </section>

                    <!-- 2. Delivery Information -->
                    <section class="checkout-section">
                        <h2 class="checkout-section-title">2. Delivery Information</h2>
                        <div class="form-group">
                            <label for="address">Street Address <span class="required">*</span></label>
                            <textarea id="address" name="address" class="form-textarea" required rows="3" placeholder="123 Shopping Avenue, Suite 100"></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City <span class="required">*</span></label>
                                <input type="text" id="city" name="city" class="form-input" required placeholder="Metropolis">
                            </div>
                            <div class="form-group">
                                <label for="postalCode">Postal Code <span class="required">*</span></label>
                                <input type="text" id="postalCode" name="postalCode" class="form-input" required placeholder="10001">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="country">Country <span class="required">*</span></label>
                            <select id="country" name="country" class="form-select" required>
                                <option value="" disabled selected>Select Country</option>
                                <option value="US">United States</option>
                                <option value="UK">United Kingdom</option>
                                <option value="CA">Canada</option>
                                <option value="PK">Pakistan</option>
                            </select>
                        </div>
                    </section>

                    <!-- 3. Delivery Type -->
                    <section class="checkout-section">
                        <h2 class="checkout-section-title">3. Delivery Type</h2>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="deliveryType" value="standard" checked>
                                <div class="radio-option-content">
                                    <span class="radio-title">Standard Delivery</span>
                                    <span class="radio-desc">3-5 Business Days</span>
                                </div>
                                <span class="radio-price">$5.00</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="deliveryType" value="express">
                                <div class="radio-option-content">
                                    <span class="radio-title">Express Delivery</span>
                                    <span class="radio-desc">1-2 Business Days</span>
                                </div>
                                <span class="radio-price">$15.00</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="deliveryType" value="pickup">
                                <div class="radio-option-content">
                                    <span class="radio-title">Store Pickup</span>
                                    <span class="radio-desc">Collect from our main branch</span>
                                </div>
                                <span class="radio-price">Free</span>
                            </label>
                        </div>
                    </section>

                    <!-- 4. Payment Method -->
                    <section class="checkout-section">
                        <h2 class="checkout-section-title">4. Payment Method</h2>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="paymentMethod" value="cod" checked>
                                <div class="radio-option-content">
                                    <span class="radio-title">Pay on Delivery (VPP)</span>
                                    <span class="radio-desc">Pay with cash when your order arrives</span>
                                </div>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="paymentMethod" value="cheque">
                                <div class="radio-option-content">
                                    <span class="radio-title">Cheque</span>
                                    <span class="radio-desc">Mail a cheque before dispatch</span>
                                </div>
                            </label>
                        </div>
                    </section>

                </form>
            </div>

            <!-- Order Summary (Right) -->
            <div class="checkout-summary-column">
                <div class="order-summary-card checkout-summary-card">
                    <h2>Order Summary</h2>
                    
                    <div class="checkout-items-list">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="checkout-item">
                                <div class="checkout-item-image">
                                    <img src="<?= htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?>">
                                </div>
                                <div class="checkout-item-details">
                                    <div class="checkout-item-name"><?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?></div>
                                    <div class="checkout-item-qty">Qty: <?= $item['quantity'] ?></div>
                                </div>
                                <div class="checkout-item-price">
                                    $<?= number_format($item['price'] * $item['quantity'], 2) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="summary-divider"></div>

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>$<?= number_format($subtotal, 2) ?></span>
                    </div>
                    <div class="summary-row" id="deliveryRow">
                        <span>Delivery</span>
                        <span id="deliveryCostDisplay">$<?= number_format($shipping, 2) ?></span>
                    </div>
                    <div class="summary-divider"></div>
                    <div class="summary-row summary-total">
                        <span>Total</span>
                        <span id="totalCostDisplay">$<?= number_format($total, 2) ?></span>
                    </div>
                    
                    <button type="submit" form="mockCheckoutForm" class="primary-button proceed-checkout-btn">Place Order</button>
                    
                    <div class="secure-checkout-badge">
                        🔒 Secure & Encrypted Checkout
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Basic frontend-only interactions for mock presentation
    
    // Delivery Type dynamic updating
    const deliveryRadios = document.querySelectorAll('input[name="deliveryType"]');
    const deliveryCostDisplay = document.getElementById('deliveryCostDisplay');
    const totalCostDisplay = document.getElementById('totalCostDisplay');
    
    const subtotal = <?= $subtotal ?>;
    
    deliveryRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            let shippingCost = 0;
            if (this.value === 'standard') shippingCost = 5.00;
            if (this.value === 'express') shippingCost = 15.00;
            if (this.value === 'pickup') shippingCost = 0.00;
            
            deliveryCostDisplay.textContent = shippingCost === 0 ? 'Free' : '$' + shippingCost.toFixed(2);
            const total = subtotal + shippingCost;
            totalCostDisplay.textContent = '$' + total.toFixed(2);
        });
    });
    
    // Mock Form Submission
    const checkoutForm = document.getElementById('mockCheckoutForm');
    checkoutForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent actual submission for now
        
        // Simple UI feedback
        const btn = this.querySelector('button[type="submit"]') || document.querySelector('.proceed-checkout-btn');
        const originalText = btn.textContent;
        btn.textContent = 'Processing...';
        btn.disabled = true;
        
        setTimeout(() => {
            alert("Order Placed Successfully! (Mock Frontend Simulation)");
            btn.textContent = originalText;
            btn.disabled = false;
        }, 800);
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
