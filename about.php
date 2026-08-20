<?php
$pageTitle = 'About Us - Arts';
$basePath = '/Shopping%20Cart';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main class="about-page">

    <section class="about-hero">
        <div class="container">
            <span class="eyebrow">Welcome to Arts</span>
            <h1>Thoughtfully Chosen Products for Everyday Life</h1>
            <p>Stationery, gifts, and lifestyle essentials — all in one simple online shop.</p>
            <a href="<?= $basePath ?>/index.php" class="primary-button">Start Shopping</a>
        </div>
    </section>

    <section class="about-section">
        <div class="container">
            <div class="about-intro-grid">
                <div class="about-intro-copy">
                    <h2>Who We Are</h2>
                    <p>
                        <strong>Arts</strong> is an online shopping cart built for the Aptech student eProject.
                        It brings together a modern browsing experience where customers can explore products,
                        place orders, track deliveries, and manage their accounts in one place.
                    </p>
                    <p>
                        Our goal is simple: make everyday shopping for stationery, gifts, and lifestyle items
                        easy, clean, and enjoyable.
                    </p>
                </div>
                <div class="about-pull">
                    <p>"Make everyday shopping for stationery, gifts, and lifestyle items easy, clean, and enjoyable."</p>
                </div>
            </div>
        </div>
    </section>

    <section class="about-section about-section-alt">
        <div class="container">
            <h2 class="section-heading">What We Offer</h2>
            <p class="about-lead">Browse our most-loved categories</p>
            <div class="offer-grid">
                <div class="offer-card">
                    <span class="offer-icon">📓</span>
                    <h3>Stationery</h3>
                    <p>Notebooks, pens, journals, and everyday office essentials.</p>
                </div>
                <div class="offer-card">
                    <span class="offer-icon">🎁</span>
                    <h3>Gift Articles</h3>
                    <p>Thoughtful decorative pieces and gift sets for every occasion.</p>
                </div>
                <div class="offer-card">
                    <span class="offer-icon">💌</span>
                    <h3>Greeting Cards</h3>
                    <p>Cards for birthdays, celebrations, and special moments.</p>
                </div>
                <div class="offer-card">
                    <span class="offer-icon">🧸</span>
                    <h3>Dolls</h3>
                    <p>Soft, playful dolls and collectible favorites.</p>
                </div>
                <div class="offer-card">
                    <span class="offer-icon">🗂️</span>
                    <h3>Files</h3>
                    <p>Folders and filing solutions to keep things organized.</p>
                </div>
                <div class="offer-card">
                    <span class="offer-icon">👜</span>
                    <h3>Handbags</h3>
                    <p>Everyday bags that pair style with practicality.</p>
                </div>
                <div class="offer-card">
                    <span class="offer-icon">👛</span>
                    <h3>Wallets</h3>
                    <p>Compact, durable wallets for everyday carry.</p>
                </div>
                <div class="offer-card">
                    <span class="offer-icon">💄</span>
                    <h3>Beauty Products</h3>
                    <p>A simple selection of everyday beauty essentials.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="about-section">
        <div class="container">
            <h2 class="section-heading">Why Shop With Us</h2>
            <p class="about-lead">A straightforward shopping experience, start to finish</p>
            <div class="about-feature-list">
                <div class="about-feature-row">
                    <span class="about-feature-icon">🛍️</span>
                    <div>
                        <h3>Browse Freely</h3>
                        <p>Explore the full catalog and product details without needing to log in first.</p>
                    </div>
                </div>
                <div class="about-feature-row">
                    <span class="about-feature-icon">🛒</span>
                    <div>
                        <h3>Simple Checkout</h3>
                        <p>Add items to your cart and complete your order with flexible options.</p>
                    </div>
                </div>
                <div class="about-feature-row">
                    <span class="about-feature-icon">📦</span>
                    <div>
                        <h3>Order Tracking</h3>
                        <p>Keep an eye on your order status from your customer dashboard.</p>
                    </div>
                </div>
                <div class="about-feature-row">
                    <span class="about-feature-icon">💬</span>
                    <div>
                        <h3>Support When Needed</h3>
                        <p>Reach out any time through our FAQ or Contact page for help.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="about-section">
        <div class="container">
            <div class="about-cta">
                <h2>Ready to Find Something You'll Love?</h2>
                <p>Take a look through our categories and see what catches your eye.</p>
                <a href="<?= $basePath ?>/index.php" class="primary-button">Browse Products</a>
            </div>
        </div>
    </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>