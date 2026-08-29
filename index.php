<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Home';
$basePath = '/Shopping-Cart';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/product-card.php';

$dbProducts = get_all_products(null, null);
$featuredDBProducts = array_slice($dbProducts, 0, 4);

$categories = [
    [
        'title' => 'Stationery',
        'subtitle' => 'Notebooks & writing tools',
        'image' => 'stationary.jpg'
    ],
    [
        'title' => 'Gift Articles',
        'subtitle' => 'Little curated luxuries',
        'image' => 'gifts.jpg'
    ],
    [
        'title' => 'Greeting Cards',
        'subtitle' => 'A message, beautifully framed',
        'image' => 'greeting-cards.jpg'
    ],
    [
        'title' => 'Dolls & Toys',
        'subtitle' => 'Playful pieces, joyful moments',
        'image' => 'dolls.jpg'
    ],
    [
        'title' => 'Files & Folders',
        'subtitle' => 'Keep everything in order',
        'image' => 'files.jpg'
    ],
    [
        'title' => 'Handbags',
        'subtitle' => 'Everyday carry, elevated',
        'image' => 'handbag.jpg'
    ],
    [
        'title' => 'Wallets',
        'subtitle' => 'Compact & well made',
        'image' => 'wallets.jpg'
    ],
    [
        'title' => 'Beauty',
        'subtitle' => 'Small self-care essentials',
        'image' => 'beauty.jpg'
    ],
];

$featuredProducts = [
    [
        'id' => 'ART1001',
        'category' => 'Stationery',
        'name' => 'Lavender Dream Journal',
        'price' => '$24.00',
        'oldPrice' => '$32.00',
        'rating' => 5,
        'reviews' => 128,
        'stock' => 'In Stock',
        'badge' => 'In Stock',
        'wishlist' => '♡',
        'image' => $basePath . '/assets/images/stationery.svg',
    ],
    [
        'id' => 'ART1013',
        'category' => 'Writing',
        'name' => 'Rose Gold Pen Set Trio',
        'price' => '$18.50',
        'oldPrice' => '$24.00',
        'rating' => 4,
        'reviews' => 85,
        'stock' => 'Low Stock',
        'badge' => 'New',
        'wishlist' => '♡',
        'image' => $basePath . '/assets/images/stationery.svg',
    ],
    [
        'id' => 'ART1014',
        'category' => 'Lifestyle',
        'name' => 'Ceramic Desk Organizer',
        'price' => '$32.00',
        'oldPrice' => '$40.00',
        'rating' => 4,
        'reviews' => 12,
        'stock' => 'In Stock',
        'badge' => '',
        'wishlist' => '♡',
        'image' => $basePath . '/assets/images/gifts.svg',
    ],
    [
        'id' => 'ART1015',
        'category' => 'Greeting Cards',
        'name' => 'Botanical Watercolor Card Set',
        'price' => '$15.00',
        'oldPrice' => '$20.00',
        'rating' => 5,
        'reviews' => 46,
        'stock' => 'In Stock',
        'badge' => '',
        'wishlist' => '♡',
        'image' => $basePath . '/assets/images/cards.svg',
    ],
];

$benefits = [
    ['icon' => 'fast-delivery.svg', 'title' => 'Fast Delivery', 'text' => 'Free shipping over $50'],
    ['icon' => 'easy-returns.svg', 'title' => 'Easy Returns', 'text' => '30-day return policy'],
    ['icon' => 'premium-quality.svg', 'title' => 'Premium Quality', 'text' => 'Curated best products'],
    ['icon' => 'support.svg', 'title' => '24/7 Support', 'text' => 'We are here to help'],
];
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap');

/* Homepage Premium Styling */
.homepage {
    font-family: 'Outfit', sans-serif;
    overflow-x: hidden;
    background: #fbf9f6; /* bg-soft */
}

.home-hero {
    position: relative;
    padding: 80px 0 100px;
    background-color: #f4ecfd; /* brand-light */
    border-bottom: 1px solid #e8e3ed; /* line */
}

.home-hero-grid {
    display: grid;
    grid-template-columns: 0.95fr 1.05fr;
    gap: 60px;
    align-items: center;
}

.home-eyebrow {
    display: inline-block;
    padding: 6px 16px;
    background: #fff;
    color: #5f33a8; /* brand-primary */
    border-radius: 999px;
    font-weight: 600;
    font-size: 0.85rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    margin-bottom: 24px;
    border: 1px solid #e8e3ed;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
}

.home-hero-copy h1 {
    font-size: clamp(2.8rem, 4.5vw, 4.2rem);
    font-weight: 700;
    line-height: 1.1;
    color: #2d2d2d;
    margin: 0 0 20px;
    letter-spacing: -0.02em;
}

.home-hero-copy h1 em {
    font-style: normal;
    color: #5f33a8;
}

.home-hero-copy p {
    font-family: 'Inter', sans-serif;
    font-size: 1.15rem;
    line-height: 1.6;
    color: #666666;
    margin: 0 0 40px;
    max-width: 480px;
}

.home-hero-actions {
    display: flex;
    gap: 16px;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 32px;
}

.primary-btn-solid {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 14px 32px;
    background-color: #5f33a8;
    color: #fff;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.05rem;
    transition: background-color 0.2s ease;
    border: none;
    cursor: pointer;
}

.primary-btn-solid:hover {
    background-color: #4b2685;
}

.secondary-btn-outline {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 14px 32px;
    background: transparent;
    color: #5f33a8;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.05rem;
    transition: background-color 0.2s ease;
    border: 2px solid #5f33a8;
    cursor: pointer;
}

.secondary-btn-outline:hover {
    background-color: #fcfaff;
}

.hero-pills {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

.hero-pill {
    font-size: 0.85rem;
    font-weight: 500;
    color: #666;
    background: #fff;
    padding: 6px 14px;
    border-radius: 20px;
    border: 1px solid #e8e3ed;
}

.home-hero-art {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    border: 4px solid #fff;
    background: #fff;
}

.home-hero-art img {
    width: 100%;
    height: auto;
    object-fit: contain;
    border-radius: 16px;
    display: block;
}

.section-premium {
    padding: 80px 0;
}

.premium-title {
    text-align: center;
    margin-bottom: 50px;
}

.premium-title h2 {
    font-size: clamp(2rem, 4vw, 2.8rem);
    font-weight: 700;
    color: #2d2d2d;
    margin: 0 0 12px;
    letter-spacing: -0.01em;
}

.premium-title p {
    font-family: 'Inter', sans-serif;
    font-size: 1.1rem;
    color: #666666;
    max-width: 500px;
    margin: 0 auto;
}

.cat-grid-premium {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 24px;
}

.cat-card-modern {
    background: #ffffff;
    border-radius: 20px;
    padding: 24px;
    text-align: center;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 1px solid #e8e3ed;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
    display: block;
}

.cat-card-modern:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(95, 51, 168, 0.06);
    border-color: rgba(95, 51, 168, 0.15);
}

.cat-image-modern {
    width: 100%;
    height: 180px;
    overflow: hidden;
    border-radius: 12px;
    margin-bottom: 20px;
    background: #fbf9f6;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cat-image-modern img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.cat-card-modern:hover .cat-image-modern img {
    transform: scale(1.03);
}

.cat-card-modern h3 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2d2d2d;
    margin: 0 0 6px;
}

.cat-card-modern p {
    font-family: 'Inter', sans-serif;
    font-size: 0.95rem;
    color: #666666;
    margin: 0;
}

.promo-premium {
    background: #0f0c29;
    background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
    border-radius: 20px;
    padding: 60px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
    color: #fff;
    margin-top: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.promo-premium-copy h2 {
    font-size: clamp(2.2rem, 4vw, 3.2rem);
    font-weight: 700;
    margin: 0 0 20px;
    line-height: 1.1;
    color: #fff;
}

.promo-premium-copy p {
    font-family: 'Inter', sans-serif;
    font-size: 1.1rem;
    color: rgba(255,255,255,0.85);
    line-height: 1.6;
    margin: 0 0 32px;
}

.btn-accent {
    display: inline-flex;
    align-items: center;
    padding: 14px 32px;
    background: #ff7eb3;
    color: #fff;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.05rem;
    transition: background-color 0.2s ease;
    border: none;
}

.btn-accent:hover {
    background: #ff6a9e;
}

.promo-premium-image img {
    width: 100%;
    border-radius: 12px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}

.benefits-premium {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    margin-top: 60px;
}

.benefit-card-premium {
    background: #ffffff;
    padding: 30px 20px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    border: 1px solid #e8e3ed;
}

.benefit-card-premium .icon {
    height: 48px;
    margin: 0 auto 16px;
    display: block;
}

.benefit-card-premium h3 {
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0 0 8px;
    color: #2d2d2d;
}

.benefit-card-premium p {
    font-family: 'Inter', sans-serif;
    font-size: 0.95rem;
    color: #666666;
    margin: 0;
}

.featured-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 26px;
}
.featured-grid-item {
    height: 100%;
}
.featured-grid-item .product-card {
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.03);
    box-shadow: 0 8px 24px rgba(0,0,0,0.02);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    background: #fff;
    height: 100%;
}
.featured-grid-item .product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(95, 51, 168, 0.08);
    border-color: rgba(95, 51, 168, 0.1);
}

@media (max-width: 992px) {
    .featured-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .home-hero-grid, .promo-premium {
        grid-template-columns: 1fr;
        text-align: center;
    }
    .home-hero-actions, .hero-pills {
        justify-content: center;
    }
    .home-hero-art {
        order: -1;
    }
    .benefits-premium {
        grid-template-columns: repeat(2, 1fr);
    }
    .home-hero-copy p {
        margin-left: auto;
        margin-right: auto;
    }
}
@media (max-width: 576px) {
    .featured-grid {
        grid-template-columns: 1fr;
    }
    .benefits-premium {
        grid-template-columns: 1fr;
    }
    .promo-premium {
        padding: 40px 24px;
    }
    .home-hero {
        padding: 60px 0 80px;
    }
}
</style>

<main class="homepage">
    <section class="home-hero">
        <div class="container home-hero-grid">
            <div class="home-hero-copy">
                <span class="home-eyebrow">Stationery · Gifts · Lifestyle</span>
                <h1>Everyday things, made a little more <em>thoughtful</em>.</h1>
                <p>Arts is a small online shop for journals, gift articles, greeting cards and lifestyle finds — curated in small batches and picked to make ordinary days feel a bit more special.</p>
                <div class="home-hero-actions">
                    <a href="<?= $basePath ?>/products.php" class="primary-btn-solid">Shop Collection</a>
                    <a href="#home-categories" class="secondary-btn-outline">Explore Categories</a>
                </div>
                <div class="hero-pills">
                    <span class="hero-pill">✓ Free shipping over $50</span>
                    <span class="hero-pill">✓ 30-day returns</span>
                </div>
            </div>
            <div class="home-hero-art">
                <img src="<?= $basePath ?>/assets/images/hero-images/hero1.jpg" alt="Curated Arts arrangement">
            </div>
        </div>
    </section>

    <section class="section-premium" id="home-categories">
        <div class="container">
            <div class="premium-title">
                <h2>Discover Collections</h2>
                <p>Find exactly what you're looking for, from hand-bound journals to elegant gift wrap.</p>
            </div>
            <div class="cat-grid-premium">
                <?php foreach ($categories as $category): ?>
                    <a href="<?= $basePath ?>/products.php" class="cat-card-modern">
                        <div class="cat-image-modern">
                            <img src="<?= $basePath ?>/assets/images/hero-category-images/<?= htmlspecialchars($category['image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($category['title'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <h3><?= htmlspecialchars($category['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p><?= htmlspecialchars($category['subtitle'], ENT_QUOTES, 'UTF-8') ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section-premium" id="featured-products">
        <div class="container">
            <div class="premium-title">
                <h2>Featured Products</h2>
                <p>A few of our favourites, picked for you.</p>
            </div>
            <div class="featured-grid">
                <?php foreach ($featuredDBProducts as $product): ?>
                    <div class="featured-grid-item">
                        <?php renderProductCard($product); ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div style="text-align: center; margin-top: 40px;">
                <a href="<?= $basePath ?>/products.php" class="secondary-btn-outline">View All Products</a>
            </div>
        </div>
    </section>

    <section class="section-premium">
        <div class="container">
            <div class="promo-premium">
                <div class="promo-premium-copy">
                    <h2>The Art of Gifting</h2>
                    <p>Whether it's for a special occasion or just because, our curated gift sets are designed to delight — beautifully packaged and ready to give.</p>
                    <a href="<?= $basePath ?>/products.php" class="btn-accent">Explore Gift Guide</a>
                </div>
                <div class="promo-premium-image">
                    <img src="<?= $basePath ?>/assets/images/hero-images/hero2.jpg" alt="Premium Gifts">
                </div>
            </div>

            <div class="benefits-premium">
                <?php foreach ($benefits as $benefit): ?>
                    <div class="benefit-card-premium">
                        <img class="icon" src="<?= $basePath ?>/assets/images/benefits/<?= htmlspecialchars($benefit['icon'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($benefit['title'], ENT_QUOTES, 'UTF-8') ?>">
                        <h3><?= htmlspecialchars($benefit['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p><?= htmlspecialchars($benefit['text'], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<?php
require_once __DIR__ . '/includes/footer.php';
?>