<?php
$pageTitle = 'Home';
$basePath = '/Shopping%20Cart';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/product-card.php';

// NOTE: category list expanded to match the real Arts catalog per project brief.
// 'icon' is an emoji placeholder (consistent with the existing benefits icons).
// 'image' is left wired up to an svg path so a real product photo can be dropped
// in later without touching this markup — see comment further down.
$categories = [
    ['title' => 'Stationery',      'subtitle' => 'Notebooks & writing tools', 'icon' => '✎', 'image' => $basePath . '/assets/images/stationery.svg'],
    ['title' => 'Gift Articles',   'subtitle' => 'Little curated luxuries',   'icon' => '🎁', 'image' => $basePath . '/assets/images/gifts.svg'],
    ['title' => 'Greeting Cards',  'subtitle' => 'A message, beautifully framed', 'icon' => '✉', 'image' => $basePath . '/assets/images/cards.svg'],
    ['title' => 'Dolls & Toys',    'subtitle' => 'Playful pieces, joyful moments', 'icon' => '🪆', 'image' => $basePath . '/assets/images/toys.svg'],
    ['title' => 'Files & Folders', 'subtitle' => 'Keep everything in order',  'icon' => '🗂', 'image' => ''],
    ['title' => 'Handbags',        'subtitle' => 'Everyday carry, elevated',  'icon' => '👜', 'image' => ''],
    ['title' => 'Wallets',         'subtitle' => 'Compact & well made',       'icon' => '👛', 'image' => ''],
    ['title' => 'Beauty',          'subtitle' => 'Small self-care essentials','icon' => '💄', 'image' => ''],
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
    ['icon' => '🚚', 'title' => 'Fast Delivery', 'text' => 'Free shipping over $50'],
    ['icon' => '↺', 'title' => 'Easy Returns', 'text' => '30-day return policy'],
    ['icon' => '✓', 'title' => 'Premium Quality', 'text' => 'Curated best products'],
    ['icon' => '☎', 'title' => '24/7 Support', 'text' => 'We are here to help'],
];
?>

<style>
/*
  Homepage-only styles. Deliberately kept out of assets/css/style.css and
  scoped under "home-*" class names so nothing here can touch products.php,
  product-card.php, header.php or footer.php. Safe to delete this block
  entirely to revert to the previous homepage look.
*/

.home-hero {
    background: var(--bg-soft);
    padding: 52px 0 60px;
}

.home-hero-grid {
    display: grid;
    grid-template-columns: 1.05fr 0.95fr;
    gap: 48px;
    align-items: center;
}

.home-hero-eyebrow {
    display: inline-block;
    text-transform: uppercase;
    letter-spacing: 0.14em;
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--brand-primary);
    margin-bottom: 14px;
}

.home-hero-copy h1 {
    font-family: Georgia, 'Iowan Old Style', 'Palatino Linotype', serif;
    font-weight: 700;
    font-size: clamp(2.1rem, 4vw, 3.1rem);
    line-height: 1.08;
    letter-spacing: -0.02em;
    color: var(--text);
    margin: 0 0 16px;
}

.home-hero-copy h1 em {
    font-style: normal;
    color: var(--brand-primary);
}

.home-hero-copy p {
    font-size: 1.05rem;
    line-height: 1.7;
    color: var(--text-soft);
    margin: 0 0 28px;
    max-width: 440px;
}

.home-hero-actions {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
}

.home-btn-ghost {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 10px;
    border: 1.5px solid var(--brand-primary);
    color: var(--brand-primary);
    font-weight: 700;
    background: transparent;
    transition: background 0.2s ease;
}

.home-btn-ghost:hover {
    background: var(--brand-soft);
}

.home-hero-pills {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 22px;
}

.home-hero-pill {
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--brand-primary-dark);
    background: var(--brand-light);
    border-radius: 999px;
    padding: 6px 12px;
}

.home-hero-art {
    position: relative;
}

/* Subtle dotted "notebook paper" texture — a nod to the stationery catalog,
   used sparingly as the one signature visual motif on this page. */
.home-hero-art::before {
    content: '';
    position: absolute;
    inset: 18px -18px -18px 18px;
    background-image: radial-gradient(circle, rgba(95, 51, 168, 0.18) 1.4px, transparent 1.6px);
    background-size: 14px 14px;
    border-radius: var(--radius-lg);
    z-index: 0;
}

.home-hero-art-frame {
    position: relative;
    z-index: 1;
    background: #ffffff;
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    padding: 28px;
    box-shadow: var(--shadow-soft);
}

.home-hero-art-frame img {
    width: 100%;
    height: 280px;
    object-fit: contain;
}

.home-categories {
    padding-top: 64px;
}

.home-cat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
}

.home-cat-card {
    display: block;
    background: #ffffff;
    border: 1px solid var(--line);
    border-radius: var(--radius-md);
    padding: 22px 16px;
    text-align: center;
    transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
}

.home-cat-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-soft);
    border-color: rgba(95, 51, 168, 0.28);
}

.home-cat-icon-wrap {
    width: 62px;
    height: 62px;
    margin: 0 auto 14px;
    border-radius: 50%;
    background: var(--brand-light);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

/* If a real product photo exists for a category, drop it in as an <img>
   inside .home-cat-icon-wrap (object-fit: cover already handles it below)
   and it will automatically replace the emoji placeholder look. */
.home-cat-icon-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.home-cat-icon {
    font-size: 1.6rem;
}

.home-cat-card h3 {
    margin: 0 0 4px;
    font-size: 0.98rem;
    font-weight: 700;
    color: var(--text);
}

.home-cat-card p {
    margin: 0;
    font-size: 0.8rem;
    color: var(--text-soft);
}

.home-featured {
    padding-top: 64px;
}

.home-featured .featured-grid {
    gap: 26px;
}

.home-promo {
    padding-top: 64px;
}

.home-promo-card {
    position: relative;
    background: #ffffff;
    border: 1px solid var(--line);
    border-left: 5px solid var(--brand-primary);
    border-radius: var(--radius-lg);
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 36px;
    align-items: center;
    padding: 40px 44px;
    overflow: hidden;
}

.home-promo-card::after {
    content: '';
    position: absolute;
    right: -40px;
    top: -40px;
    width: 160px;
    height: 160px;
    background-image: radial-gradient(circle, rgba(95, 51, 168, 0.12) 1.4px, transparent 1.6px);
    background-size: 14px 14px;
    border-radius: 50%;
}

.home-promo-copy h2 {
    font-family: Georgia, 'Iowan Old Style', 'Palatino Linotype', serif;
    margin: 0 0 12px;
    font-size: clamp(1.6rem, 2.6vw, 2.2rem);
    color: var(--brand-primary-dark);
    letter-spacing: -0.01em;
}

.home-promo-copy p {
    margin: 0 0 22px;
    color: var(--text-soft);
    line-height: 1.7;
}

.home-promo-image {
    position: relative;
    z-index: 1;
    border-radius: 14px;
    overflow: hidden;
    background: var(--brand-soft);
    height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.home-promo-image img {
    width: 62%;
    height: 62%;
    object-fit: contain;
}

.home-benefits {
    padding-top: 56px;
}

.home-benefits .benefits-grid {
    padding: 22px 18px;
    box-shadow: none;
}

.home-benefits .benefit-icon {
    width: 42px;
    height: 42px;
    font-size: 1.2rem;
}

@media (max-width: 980px) {
    .home-hero-grid {
        grid-template-columns: 1fr;
    }
    .home-hero-copy p {
        max-width: none;
    }
    .home-hero-art {
        order: -1;
    }
    .home-cat-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .home-promo-card {
        grid-template-columns: 1fr;
        padding: 28px 24px;
    }
    .home-promo-image {
        height: 180px;
    }
}

@media (max-width: 640px) {
    .home-hero {
        padding: 36px 0 44px;
    }
    .home-hero-art-frame {
        padding: 18px;
    }
    .home-hero-art-frame img {
        height: 200px;
    }
    .home-cat-grid {
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    .home-cat-card {
        padding: 16px 10px;
    }
}
</style>

<main class="homepage">
    <section class="home-hero">
        <div class="container home-hero-grid">
            <div class="home-hero-copy">
                <span class="home-hero-eyebrow">Stationery · Gifts · Lifestyle</span>
                <h1>Everyday things, made a little more <em>thoughtful</em>.</h1>
                <p>Arts is a small online shop for journals, gift articles, greeting cards and lifestyle finds — curated in small batches and picked to make ordinary days feel a bit more special.</p>
                <div class="home-hero-actions">
                    <a href="<?= $basePath ?>/products.php" class="primary-button">Shop Now <span>→</span></a>
                    <a href="#home-categories" class="home-btn-ghost">Browse Categories</a>
                </div>
                <div class="home-hero-pills">
                    <span class="home-hero-pill">Free shipping over $50</span>
                    <span class="home-hero-pill">30-day returns</span>
                </div>
            </div>
            <div class="home-hero-art">
                <div class="home-hero-art-frame">
                    <img src="<?= $basePath ?>/assets/images/gift-promo.svg" alt="A curated Arts gift arrangement">
                </div>
            </div>
        </div>
    </section>

    <section class="home-categories" id="home-categories">
        <div class="container">
            <h2 class="section-heading">Shop by Category</h2>

            <div class="home-cat-grid">
                <?php foreach ($categories as $category): ?>
                    <a href="<?= $basePath ?>/products.php" class="home-cat-card">
                        <div class="home-cat-icon-wrap">
                            <?php if (!empty($category['image'])): ?>
                                <img src="<?= htmlspecialchars($category['image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($category['title'], ENT_QUOTES, 'UTF-8') ?>">
                            <?php else: ?>
                                <span class="home-cat-icon"><?= htmlspecialchars($category['icon'], ENT_QUOTES, 'UTF-8') ?></span>
                            <?php endif; ?>
                        </div>
                        <h3><?= htmlspecialchars($category['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p><?= htmlspecialchars($category['subtitle'], ENT_QUOTES, 'UTF-8') ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="home-featured">
        <div class="container">
            <div class="section-head-row">
                <div>
                    <h2 class="section-heading">Featured Products</h2>
                    <p class="section-subheading">Our most loved items this season.</p>
                </div>
                <a href="<?= $basePath ?>/products.php" class="text-link">View All →</a>
            </div>

            <div class="featured-grid">
                <?php foreach ($featuredProducts as $product): ?>
                    <?php renderProductCard($product); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="home-promo">
        <div class="container">
            <div class="home-promo-card">
                <div class="home-promo-copy">
                    <h2>Find the Perfect Gift</h2>
                    <p>Whether it's for a special occasion or just because, our curated gift sets are designed to delight — beautifully packaged and ready to give.</p>
                    <a href="<?= $basePath ?>/products.php" class="primary-button">Explore Gift Guide</a>
                </div>
                <div class="home-promo-image">
                    <img src="<?= $basePath ?>/assets/images/gift-promo.svg" alt="Gift arrangement">
                </div>
            </div>
        </div>
    </section>

    <section class="home-benefits">
        <div class="container">
            <div class="benefits-grid">
                <?php foreach ($benefits as $benefit): ?>
                    <div class="benefit-item">
                        <div class="benefit-icon"><?= htmlspecialchars($benefit['icon'], ENT_QUOTES, 'UTF-8') ?></div>
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