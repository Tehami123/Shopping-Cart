<?php
$pageTitle = 'About Us - Arts';
$basePath = '/Shopping%20Cart';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<style>
.arts-about .container { max-width: 1140px; margin: 0 auto; padding: 0 24px; }

.arts-about .ab-hero {
    background: linear-gradient(135deg, #6d28d9 0%, #8b5cf6 100%);
    color: #ffffff;
    padding: 72px 0 84px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.arts-about .ab-hero::before {
    content: "";
    position: absolute;
    top: -60px; right: -60px;
    width: 260px; height: 260px;
    border-radius: 50%;
    background: rgba(255,255,255,0.08);
}
.arts-about .ab-hero::after {
    content: "";
    position: absolute;
    bottom: -80px; left: -40px;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
}
.arts-about .ab-eyebrow {
    display: inline-block;
    background: rgba(255,255,255,0.15);
    padding: 6px 16px;
    border-radius: 999px;
    font-size: 13px;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    margin-bottom: 18px;
    position: relative;
}
.arts-about .ab-hero h1 {
    font-size: 2.4rem;
    margin: 0 0 14px;
    font-weight: 700;
    position: relative;
}
.arts-about .ab-hero p {
    max-width: 560px;
    margin: 0 auto 28px;
    font-size: 1.05rem;
    color: rgba(255,255,255,0.92);
    line-height: 1.6;
    position: relative;
}
.arts-about .ab-hero-btn {
    position: relative;
}

.arts-about .primary-button {
    display: inline-block;
    background: #ffffff;
    color: #6d28d9;
    font-weight: 600;
    padding: 13px 30px;
    border-radius: 10px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    font-size: 0.95rem;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}
.arts-about .primary-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 26px rgba(0,0,0,0.16);
}

.arts-about .ab-section { padding: 60px 0; }
.arts-about .ab-heading {
    font-size: 1.7rem;
    font-weight: 700;
    color: #201a2b;
    text-align: center;
    margin: 0 0 8px;
}
.arts-about .ab-subheading {
    text-align: center;
    color: #6b7280;
    margin: 0 0 40px;
    font-size: 1rem;
}

.arts-about .ab-intro {
    max-width: 760px;
    margin: 0 auto;
    text-align: center;
}
.arts-about .ab-intro h2 {
    font-size: 1.7rem;
    color: #201a2b;
    margin-bottom: 14px;
}
.arts-about .ab-intro p {
    color: #4b5563;
    line-height: 1.75;
    font-size: 1.02rem;
    margin: 0 0 12px;
}

.arts-about .ab-category-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
}
.arts-about .ab-category-card {
    background: #ffffff;
    border: 1px solid #ece7f7;
    border-radius: 16px;
    padding: 26px 22px;
    box-shadow: 0 1px 3px rgba(76,29,149,0.06);
    transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
}
.arts-about .ab-category-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 28px rgba(109,40,217,0.14);
    border-color: #c4b5fd;
}
.arts-about .ab-cat-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 48px; height: 48px;
    border-radius: 12px;
    background: #f5f3ff;
    font-size: 22px;
    margin-bottom: 14px;
}
.arts-about .ab-category-card h3 {
    margin: 0 0 6px;
    font-size: 1.05rem;
    color: #201a2b;
}
.arts-about .ab-category-card p {
    margin: 0;
    color: #6b7280;
    font-size: 0.9rem;
    line-height: 1.5;
}

.arts-about .ab-why { background: #faf9fb; border-radius: 24px; }
.arts-about .ab-feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 24px;
}
.arts-about .ab-feature {
    text-align: left;
    padding: 8px 4px;
}
.arts-about .ab-feature-icon {
    width: 42px; height: 42px;
    border-radius: 50%;
    background: #ede9fe;
    color: #6d28d9;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    margin-bottom: 12px;
}
.arts-about .ab-feature h3 {
    margin: 0 0 6px;
    font-size: 1rem;
    color: #201a2b;
}
.arts-about .ab-feature p {
    margin: 0;
    color: #6b7280;
    font-size: 0.92rem;
    line-height: 1.55;
}

.arts-about .ab-cta {
    background: #f5f3ff;
    padding: 56px 0;
    margin-top: 20px;
}
.arts-about .ab-cta-inner {
    text-align: center;
    max-width: 560px;
}
.arts-about .ab-cta-inner h2 {
    font-size: 1.6rem;
    color: #201a2b;
    margin: 0 0 10px;
}
.arts-about .ab-cta-inner p {
    color: #6b7280;
    margin: 0 0 24px;
    line-height: 1.6;
}
.arts-about .ab-cta .primary-button {
    background: #6d28d9;
    color: #ffffff;
    box-shadow: 0 8px 20px rgba(109,40,217,0.25);
}
.arts-about .ab-cta .primary-button:hover {
    background: #5b21b6;
}

@media (max-width: 640px) {
    .arts-about .ab-hero { padding: 56px 0 64px; }
    .arts-about .ab-hero h1 { font-size: 1.8rem; }
    .arts-about .ab-section { padding: 44px 0; }
}
</style>

<main class="arts-about">

    <section class="ab-hero">
        <div class="container">
            <span class="ab-eyebrow">Welcome to Arts</span>
            <h1>Thoughtfully Chosen Products for Everyday Life</h1>
            <p>Stationery, gifts, and lifestyle essentials — all in one simple online shop.</p>
            <a href="<?= $basePath ?>/index.php" class="primary-button ab-hero-btn">Start Shopping</a>
        </div>
    </section>

    <section class="ab-section">
        <div class="container ab-intro">
            <h2>Who We Are</h2>
            <p>
                Arts is an online shopping cart built for the Aptech student eProject. It brings together
                a modern browsing experience where customers can explore products, place orders, track
                deliveries, and manage their accounts in one place.
            </p>
            <p>
                Our goal is simple: make everyday shopping for stationery, gifts, and lifestyle items
                easy, clean, and enjoyable.
            </p>
        </div>
    </section>

    <section class="ab-section">
        <div class="container">
            <h2 class="ab-heading">What We Offer</h2>
            <p class="ab-subheading">Browse our most-loved categories</p>
            <div class="ab-category-grid">
                <div class="ab-category-card">
                    <span class="ab-cat-icon">📓</span>
                    <h3>Stationery</h3>
                    <p>Notebooks, pens, journals, and everyday office essentials.</p>
                </div>
                <div class="ab-category-card">
                    <span class="ab-cat-icon">🎁</span>
                    <h3>Gift Articles</h3>
                    <p>Thoughtful decorative pieces and gift sets for every occasion.</p>
                </div>
                <div class="ab-category-card">
                    <span class="ab-cat-icon">💌</span>
                    <h3>Greeting Cards</h3>
                    <p>Cards for birthdays, celebrations, and special moments.</p>
                </div>
                <div class="ab-category-card">
                    <span class="ab-cat-icon">🧸</span>
                    <h3>Dolls</h3>
                    <p>Soft, playful dolls and collectible favorites.</p>
                </div>
                <div class="ab-category-card">
                    <span class="ab-cat-icon">🗂️</span>
                    <h3>Files</h3>
                    <p>Folders and filing solutions to keep things organized.</p>
                </div>
                <div class="ab-category-card">
                    <span class="ab-cat-icon">👜</span>
                    <h3>Handbags</h3>
                    <p>Everyday bags that pair style with practicality.</p>
                </div>
                <div class="ab-category-card">
                    <span class="ab-cat-icon">👛</span>
                    <h3>Wallets</h3>
                    <p>Compact, durable wallets for everyday carry.</p>
                </div>
                <div class="ab-category-card">
                    <span class="ab-cat-icon">💄</span>
                    <h3>Beauty Products</h3>
                    <p>A simple selection of everyday beauty essentials.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="ab-section ab-why">
        <div class="container">
            <h2 class="ab-heading">Why Shop With Us</h2>
            <p class="ab-subheading">A straightforward shopping experience, start to finish</p>
            <div class="ab-feature-grid">
                <div class="ab-feature">
                    <span class="ab-feature-icon">🛍️</span>
                    <h3>Browse Freely</h3>
                    <p>Explore the full catalog and product details without needing to log in first.</p>
                </div>
                <div class="ab-feature">
                    <span class="ab-feature-icon">🛒</span>
                    <h3>Simple Checkout</h3>
                    <p>Add items to your cart and complete your order with flexible options.</p>
                </div>
                <div class="ab-feature">
                    <span class="ab-feature-icon">📦</span>
                    <h3>Order Tracking</h3>
                    <p>Keep an eye on your order status from your customer dashboard.</p>
                </div>
                <div class="ab-feature">
                    <span class="ab-feature-icon">💬</span>
                    <h3>Support When Needed</h3>
                    <p>Reach out any time through our FAQ or Contact page for help.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="ab-cta">
        <div class="container ab-cta-inner">
            <h2>Ready to Find Something You'll Love?</h2>
            <p>Take a look through our categories and see what catches your eye.</p>
            <a href="<?= $basePath ?>/index.php" class="primary-button">Browse Products</a>
        </div>
    </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>