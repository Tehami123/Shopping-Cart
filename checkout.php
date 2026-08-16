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

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap');

/* Premium Aesthetics for Checkout Page */
.checkout-page {
    font-family: 'Outfit', sans-serif;
    background: #fdfcff;
    position: relative;
    overflow-x: hidden;
    padding-bottom: 80px;
    padding-top: 20px;
    min-height: 70vh;
}

.checkout-page::before {
    content: '';
    position: absolute;
    width: 700px;
    height: 700px;
    background: radial-gradient(circle, rgba(95,51,168,0.06) 0%, transparent 70%);
    top: -150px;
    left: -250px;
    border-radius: 50%;
    filter: blur(70px);
    z-index: 0;
    pointer-events: none;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.checkout-header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
    position: relative;
    z-index: 1;
    animation: fadeInUp 0.6s ease-out both;
}

.checkout-page-title {
    font-size: clamp(2.2rem, 3.5vw, 3rem);
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
    letter-spacing: -0.02em;
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

.checkout-layout {
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 50px;
    position: relative;
    z-index: 1;
}

.checkout-form-column {
    animation: fadeInUp 0.6s ease-out 0.1s both;
}

.checkout-section {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,1);
    box-shadow: 0 15px 35px rgba(0,0,0,0.03);
    padding: 32px;
    margin-bottom: 30px;
}

.checkout-section-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-row .form-group {
    margin-bottom: 0;
}

.form-group label {
    display: block;
    font-family: 'Inter', sans-serif;
    font-weight: 500;
    font-size: 0.95rem;
    color: var(--text-soft);
    margin-bottom: 8px;
}

.form-group .required {
    color: #ff4d4d;
}

.form-input, .form-textarea, .form-select {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid rgba(0,0,0,0.1);
    border-radius: 12px;
    font-family: 'Inter', sans-serif;
    font-size: 1rem;
    color: var(--text);
    background: #fff;
    transition: all 0.3s ease;
    outline: none;
}

.form-input:focus, .form-textarea:focus, .form-select:focus {
    border-color: rgba(95, 51, 168, 0.4);
    box-shadow: 0 0 0 4px rgba(95, 51, 168, 0.05);
}

.form-textarea {
    resize: vertical;
    min-height: 80px;
}

.form-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
}

.radio-group {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.radio-option {
    display: flex;
    align-items: center;
    padding: 20px;
    border: 1px solid rgba(0,0,0,0.1);
    border-radius: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #fff;
}

.radio-option:hover {
    border-color: rgba(95, 51, 168, 0.2);
    box-shadow: 0 8px 20px rgba(0,0,0,0.02);
}

.radio-option:has(input:checked) {
    border-color: var(--brand-primary);
    background: rgba(95, 51, 168, 0.02);
    box-shadow: 0 8px 20px rgba(95, 51, 168, 0.05);
}

.radio-option input[type="radio"] {
    width: 20px;
    height: 20px;
    margin-right: 20px;
    accent-color: var(--brand-primary);
    cursor: pointer;
}

.radio-option-content {
    flex: 1;
}

.radio-title {
    display: block;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 4px;
    font-size: 1.05rem;
}

.radio-desc {
    font-family: 'Inter', sans-serif;
    display: block;
    font-size: 0.9rem;
    color: var(--text-soft);
}

.radio-price {
    font-weight: 700;
    color: var(--text);
    font-size: 1.1rem;
}

.checkout-summary-column {
    animation: fadeInUp 0.6s ease-out 0.2s both;
}

.order-summary-card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,1);
    box-shadow: 0 15px 40px rgba(95, 51, 168, 0.08);
    padding: 32px;
    position: sticky;
    top: 40px;
}

.order-summary-card h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 24px;
}

.checkout-items-list {
    margin-bottom: 24px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.checkout-item {
    display: flex;
    align-items: center;
    gap: 16px;
}

.checkout-item-image {
    width: 64px;
    height: 64px;
    background: #f8f8f8;
    border-radius: 12px;
    padding: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.checkout-item-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.checkout-item-details {
    flex: 1;
}

.checkout-item-name {
    font-weight: 600;
    font-size: 1rem;
    color: var(--text);
    margin-bottom: 4px;
}

.checkout-item-qty {
    font-family: 'Inter', sans-serif;
    font-size: 0.85rem;
    color: var(--text-soft);
}

.checkout-item-price {
    font-family: 'Inter', sans-serif;
    font-weight: 600;
    color: var(--text);
}

.summary-divider {
    height: 1px;
    background: rgba(0,0,0,0.06);
    margin: 20px 0;
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

.secure-checkout-badge {
    text-align: center;
    margin-top: 20px;
    font-family: 'Inter', sans-serif;
    font-size: 0.9rem;
    color: #4CAF50;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

@media (max-width: 980px) {
    .checkout-layout { grid-template-columns: 1fr; }
    .order-summary-card { position: static; }
}

@media (max-width: 600px) {
    .form-row { grid-template-columns: 1fr; gap: 0; }
}
</style>

<main class="checkout-page">
    <div class="container">
        
        <div class="checkout-header-actions">
            <h1 class="checkout-page-title">Checkout</h1>
            <a href="<?= $basePath ?>/cart.php" class="secondary-link">← Back to Cart</a>
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
                    
                    <button type="submit" form="mockCheckoutForm" class="proceed-checkout-btn">Place Order</button>
                    
                    <div class="secure-checkout-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        Secure & Encrypted Checkout
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
