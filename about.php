<?php
$pageTitle = 'About Us - Arts';
$basePath = '/Shopping%20Cart';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>



<main class="about-page">

    <section class="about-hero">
        <div class="container about-hero-grid">
            <div class="about-hero-copy reveal-on-scroll">
                <span class="eyebrow">Arts studio</span>
                <h1>Objects that make everyday rituals feel a little more personal.</h1>
                <p>
                    At Arts, we curate thoughtful stationery, gifts, and lifestyle essentials designed to make the ordinary feel a little more beautiful.
                </p>
                <div class="about-hero-actions">
                    <a href="<?= $basePath ?>/index.php" class="primary-button">Shop now</a>
                    <a href="<?= $basePath ?>/products.php" class="secondary-button about-secondary-button">Browse collection</a>
                </div>
            </div>

            <div class="about-hero-visual reveal-on-scroll">
                <div class="photo-stack photo-stack-main">
                    <img src="<?= $basePath ?>/assets/images/about-images/about-main-img.jpg" alt="Gift items and lifestyle objects">
                </div>
                <div class="photo-stack photo-stack-small">
                    <img src="<?= $basePath ?>/assets/images/about-images/about-sec-img.jpg" alt="Stationery card design">
                </div>
                <div class="floating-label">Stationery & gifts</div>
            </div>
        </div>
    </section>

    <section class="about-story about-section">
        <div class="container story-layout">
            <div class="story-panel story-copy reveal-on-scroll">
                <span class="mini-label">Our approach</span>
                <h2>Curated for work, gifting, and everyday life.</h2>
                <p>
                    <strong>Arts</strong> brings together a clean and modern shopping experience with a catalogue shaped around useful, personal pieces.
                </p>
                <p>
                    From stationery essentials to gift-worthy accessories, the collection is designed to feel considered, practical, and enjoyable to keep around.
                </p>
            </div>

            <div class="story-panel story-art reveal-on-scroll">
                <img src="<?= $basePath ?>/assets/images/about-images/about-work-img.jpg" alt="Arts stationery illustration">
            </div>
        </div>
    </section>

    <section class="about-categories about-section accent-panel">
        <div class="container">
            <div class="section-heading reveal-on-scroll">
                <span class="mini-label dark-label">Collection</span>
                <h2>Thoughtful pieces across every part of the day.</h2>
            </div>

            <div class="category-feature reveal-on-scroll">
                <div class="category-copy">
                    <span class="feature-kicker">Stationery</span>
                    <h3>Notebooks, pens, and small tools for the work you do every day.</h3>
                    <p>Useful essentials made to look good on a desk, a bedside table, or in a bag.</p>
                </div>
                <div class="category-visual">
                    <img src="<?= $basePath ?>/assets/images/about-images/about-notebook-img.jpg" alt="Arts stationery card spread">
                </div>
            </div>

            <div class="category-grid">
                <article class="mini-category reveal-on-scroll">
                    <img class="mini-icon" src="<?= $basePath ?>/assets/images/categories/gift-articles.svg" alt="Gift Articles">
                    <h3>Gift Articles</h3>
                    <p>Decorative pieces and gift-ready items chosen for special moments.</p>
                </article>
                <article class="mini-category reveal-on-scroll">
                    <img class="mini-icon" src="<?= $basePath ?>/assets/images/categories/greeting-cards.svg" alt="Greeting Cards">
                    <h3>Greeting Cards</h3>
                    <p>Thoughtful cards for birthdays, celebrations, and everyday notes.</p>
                </article>
                <article class="mini-category reveal-on-scroll">
                    <img class="mini-icon" src="<?= $basePath ?>/assets/images/categories/dolls.svg" alt="Dolls">
                    <h3>Dolls</h3>
                    <p>Playful companions and keepsakes that add character to a room.</p>
                </article>
                <article class="mini-category reveal-on-scroll">
                    <img class="mini-icon" src="<?= $basePath ?>/assets/images/categories/handbags.svg" alt="Handbags">
                    <h3>Handbags</h3>
                    <p>Everyday styles designed to carry more than just essentials.</p>
                </article>
                <article class="mini-category reveal-on-scroll">
                    <img class="mini-icon" src="<?= $basePath ?>/assets/images/categories/wallets.svg" alt="Wallets">
                    <h3>Wallets</h3>
                    <p>Simple, durable, and easy-to-carry pieces for the day ahead.</p>
                </article>
                <article class="mini-category reveal-on-scroll">
                    <img class="mini-icon" src="<?= $basePath ?>/assets/images/categories/beauty-products.svg" alt="Beauty Products">
                    <h3>Beauty Products</h3>
                    <p>Compact essentials chosen for daily routines and personal care.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="about-values about-section">
        <div class="container value-layout">
            <div class="value-intro reveal-on-scroll">
                <span class="mini-label">Why Arts</span>
                <h2>Simple shopping, thoughtfully designed.</h2>
            </div>

            <div class="value-grid">
                <article class="value-card reveal-on-scroll">
                    <img class="value-icon" src="<?= $basePath ?>/assets/images/value-icons/browse-with-ease.svg" alt="Browse with ease">
                    <h3>Browse with ease</h3>
                    <p>Explore the complete collection and product details without friction.</p>
                </article>
                <article class="value-card reveal-on-scroll">
                    <img class="value-icon" src="<?= $basePath ?>/assets/images/value-icons/checkout-made-easy.svg" alt="Checkout made easy">
                    <h3>Checkout made easy</h3>
                    <p>Add what you need and move through purchase steps without overwhelm.</p>
                </article>
                <article class="value-card reveal-on-scroll">
                    <img class="value-icon" src="<?= $basePath ?>/assets/images/value-icons/stay-informed.svg" alt="Stay informed">
                    <h3>Stay informed</h3>
                    <p>Track your order status and keep the experience clear from start to finish.</p>
                </article>
                <article class="value-card reveal-on-scroll">
                    <img class="value-icon" src="<?= $basePath ?>/assets/images/value-icons/support-when-needed.svg" alt="Support when needed">
                    <h3>Support when needed</h3>
                    <p>Reach out through the Contact page whenever you need assistance.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="about-section">
        <div class="container">
            <div class="about-cta reveal-on-scroll">
                <h2>Find something that fits your everyday routine.</h2>
                <p>Browse the collection and discover useful pieces that feel thoughtful, personal, and easy to love.</p>
                <a href="<?= $basePath ?>/index.php" class="primary-button">Browse products</a>
            </div>
        </div>
    </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>