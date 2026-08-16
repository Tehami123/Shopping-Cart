<?php
$pageTitle = 'Home';
$basePath = '/Shopping%20Cart';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/product-card.php';

$categories = [
    [
        'title' => 'Stationery',
        'subtitle' => 'Notebooks & writing tools',
        'image' => 'pen.png'
    ],
    [
        'title' => 'Gift Articles',
        'subtitle' => 'Little curated luxuries',
        'image' => 'gift.jpg'
    ],
    [
        'title' => 'Greeting Cards',
        'subtitle' => 'A message, beautifully framed',
        'image' => 'mail.jpg'
    ],
    [
        'title' => 'Dolls & Toys',
        'subtitle' => 'Playful pieces, joyful moments',
        'image' => 'toys.jpg'
    ],
    [
        'title' => 'Files & Folders',
        'subtitle' => 'Keep everything in order',
        'image' => 'folder.png'
    ],
    [
        'title' => 'Handbags',
        'subtitle' => 'Everyday carry, elevated',
        'image' => 'bag.jpg'
    ],
    [
        'title' => 'Wallets',
        'subtitle' => 'Compact & well made',
        'image' => 'wallet.jpg'
    ],
    [
        'title' => 'Beauty',
        'subtitle' => 'Small self-care essentials',
        'image' => 'lipstick.jpg'
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
    ['icon' => '🚚', 'title' => 'Fast Delivery', 'text' => 'Free shipping over $50'],
    ['icon' => '↺', 'title' => 'Easy Returns', 'text' => '30-day return policy'],
    ['icon' => '✓', 'title' => 'Premium Quality', 'text' => 'Curated best products'],
    ['icon' => '☎', 'title' => '24/7 Support', 'text' => 'We are here to help'],
];
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap');

/* Homepage Premium Styling */
.homepage {
    font-family: 'Outfit', sans-serif;
    overflow-x: hidden;
    background: #fdfcff;
}

/* Animations */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
    100% { transform: translateY(0px); }
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.home-hero {
    position: relative;
    padding: 120px 0 140px;
    background: linear-gradient(-45deg, #f4ecfd, #fbf9f6, #e6dcf2, #fff0f5);
    background-size: 400% 400%;
    animation: gradientShift 15s ease infinite;
    overflow: hidden;
}

.home-hero::before {
    content: '';
    position: absolute;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(95,51,168,0.1) 0%, transparent 70%);
    top: -200px;
    left: -200px;
    border-radius: 50%;
    filter: blur(60px);
}

.home-hero::after {
    content: '';
    position: absolute;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(212,83,89,0.08) 0%, transparent 70%);
    bottom: -150px;
    right: -100px;
    border-radius: 50%;
    filter: blur(60px);
}

.home-hero-grid {
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    gap: 60px;
    align-items: center;
    position: relative;
    z-index: 2;
}

.home-hero-copy {
    animation: fadeInUp 1s cubic-bezier(0.2, 0.8, 0.2, 1) both;
}

.home-eyebrow {
    display: inline-block;
    padding: 8px 18px;
    background: rgba(95, 51, 168, 0.06);
    color: var(--brand-primary);
    border-radius: 999px;
    font-weight: 700;
    font-size: 0.85rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    margin-bottom: 28px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(95, 51, 168, 0.1);
}

.home-hero-copy h1 {
    font-size: clamp(3rem, 5vw, 4.8rem);
    font-weight: 700;
    line-height: 1.05;
    color: #1a1a1a;
    margin: 0 0 24px;
    letter-spacing: -0.03em;
}

.home-hero-copy h1 em {
    font-style: normal;
    background: linear-gradient(135deg, var(--brand-primary), #d45359);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.home-hero-copy p {
    font-family: 'Inter', sans-serif;
    font-size: 1.15rem;
    line-height: 1.7;
    color: #555;
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

.primary-btn-glow {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 16px 36px;
    background: linear-gradient(135deg, var(--brand-primary), #7344be);
    color: #fff;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.05rem;
    transition: all 0.3s ease;
    box-shadow: 0 10px 25px rgba(95, 51, 168, 0.3);
    border: none;
    cursor: pointer;
}

.primary-btn-glow:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(95, 51, 168, 0.4);
}

.ghost-btn-sleek {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 16px 36px;
    background: rgba(255, 255, 255, 0.5);
    color: var(--brand-primary-dark);
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.05rem;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.8);
    cursor: pointer;
}

.ghost-btn-sleek:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: translateY(-3px);
}

.hero-pills {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

.hero-pill {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--brand-primary-dark);
    background: rgba(255,255,255,0.6);
    backdrop-filter: blur(5px);
    padding: 8px 16px;
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.8);
    box-shadow: 0 4px 10px rgba(0,0,0,0.02);
}

.home-hero-art {
    position: relative;
    animation: fadeInUp 1s cubic-bezier(0.2, 0.8, 0.2, 1) 0.2s both;
}

.glass-card {
    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(20px);
    border-radius: 28px;
    border: 1px solid rgba(255, 255, 255, 0.8);
    padding: 40px;
    box-shadow: 0 25px 50px rgba(0,0,0,0.06);
    animation: float 6s ease-in-out infinite;
}

.glass-card img {
    width: 100%;
    height: auto;
    max-height: 420px;
    object-fit: contain;
    filter: drop-shadow(0 20px 30px rgba(0,0,0,0.1));
}

.section-premium {
    padding: 100px 0;
    position: relative;
}

.premium-title {
    text-align: center;
    margin-bottom: 60px;
    animation: fadeInUp 0.8s ease out both;
}

.premium-title h2 {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 16px;
    letter-spacing: -0.02em;
}

.premium-title p {
    font-family: 'Inter', sans-serif;
    font-size: 1.1rem;
    color: #666;
    max-width: 500px;
    margin: 0 auto;
}

.cat-grid-premium {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 24px;
}

.cat-card-modern {
    background: #fff;
    border-radius: 20px;
    padding: 36px 24px;
    text-align: center;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 1px solid rgba(0,0,0,0.03);
    box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    position: relative;
    overflow: hidden;
    z-index: 1;
}

.cat-card-modern::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: linear-gradient(135deg, var(--brand-light), #fff);
    z-index: -1;
    opacity: 0;
    transition: opacity 0.4s ease;
}

.cat-card-modern:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(95, 51, 168, 0.08);
    border-color: rgba(95, 51, 168, 0.1);
}

.cat-card-modern:hover::before {
    opacity: 1;
}

.cat-icon-modern {
    width: 76px;
    height: 76px;
    margin: 0 auto 24px;
    background: linear-gradient(135deg, var(--brand-light), #fff);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.2rem;
    box-shadow: inset 0 2px 10px rgba(0,0,0,0.02), 0 5px 15px rgba(95,51,168,0.05);
    transition: transform 0.4s ease;
}

.cat-card-modern:hover .cat-icon-modern {
    transform: scale(1.15) rotate(5deg);
}

.cat-card-modern h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 8px;
}

.cat-card-modern p {
    font-family: 'Inter', sans-serif;
    font-size: 0.95rem;
    color: #666;
    margin: 0;
}

.promo-premium {
    background: #0f0c29;
    background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
    border-radius: 36px;
    padding: 70px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 70px;
    align-items: center;
    position: relative;
    overflow: hidden;
    color: #fff;
    margin-top: 40px;
    box-shadow: 0 30px 60px rgba(0,0,0,0.15);
}

.cat-image-modern {
    width: 100%;
    height: 180px;
    overflow: hidden;
    border-radius: 18px;
    margin-bottom: 18px;
}

.cat-image-modern img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.cat-card-modern:hover .cat-image-modern img {
    transform: scale(1.04);
}

.promo-premium::after {
    content: '';
    position: absolute;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(255,126,179,0.2) 0%, transparent 70%);
    top: -150px;
    right: -150px;
    border-radius: 50%;
}

.promo-premium-copy {
    position: relative;
    z-index: 2;
}

.promo-premium-copy h2 {
    font-size: clamp(2.5rem, 4.5vw, 3.8rem);
    font-weight: 700;
    margin: 0 0 24px;
    line-height: 1.1;
    background: linear-gradient(to right, #fff, #e0e0e0);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.promo-premium-copy p {
    font-family: 'Inter', sans-serif;
    font-size: 1.15rem;
    color: rgba(255,255,255,0.85);
    line-height: 1.7;
    margin: 0 0 36px;
}

.btn-accent {
    display: inline-flex;
    align-items: center;
    padding: 18px 40px;
    background: linear-gradient(135deg, #ff7eb3, #ff758c);
    color: #fff;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 10px 25px rgba(255, 117, 140, 0.4);
}

.btn-accent:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(255, 117, 140, 0.5);
}

.promo-premium-image {
    position: relative;
    
    z-index: 2;
    animation: float 8s ease-in-out infinite;
}

.promo-premium-image img {
    width: 100%;
    max-width: 550px;
    border-radius: 18px;
    filter: drop-shadow(0 40px 50px rgba(0,0,0,0.4));
}

.benefits-premium {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
    margin-top: 80px;
}

.benefit-card-premium {
    background: #fff;
    padding: 36px 24px;
    border-radius: 24px;
    text-align: center;
    box-shadow: 0 15px 35px rgba(0,0,0,0.03);
    border: 1px solid rgba(0,0,0,0.03);
    transition: transform 0.3s ease;
}

.benefit-card-premium:hover {
    transform: translateY(-6px);
}

.benefit-card-premium .icon {
    font-size: 2.8rem;
    margin-bottom: 20px;
    display: block;
}

.benefit-card-premium h3 {
    font-size: 1.15rem;
    font-weight: 700;
    margin: 0 0 10px;
    color: #1a1a1a;
}

.benefit-card-premium p {
    font-family: 'Inter', sans-serif;
    font-size: 0.95rem;
    color: #666;
    margin: 0;
}

@media (max-width: 992px) {
    .home-hero-grid, .promo-premium {
        grid-template-columns: 1fr;
    }
    .home-hero-art, .promo-premium-image {
        order: -1;
    }
    .benefits-premium {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 576px) {
    .benefits-premium {
        grid-template-columns: 1fr;
    }
    .cat-grid-premium {
        grid-template-columns: 1fr;
    }
    .promo-premium {
        padding: 50px 24px;
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
                    <a href="<?= $basePath ?>/products.php" class="primary-btn-glow">Shop Collection</a>
                    <a href="#home-categories" class="ghost-btn-sleek">Explore Categories</a>
                </div>
                <div class="hero-pills">
                    <span class="hero-pill">✓ Free shipping over $50</span>
                    <span class="hero-pill">✓ 30-day returns</span>
                </div>
            </div>
            <div class="home-hero-art">
                <div class="glass-card">
                    <img src="<?= $basePath ?>/assets/images/hero-images/hero1.jpg" alt="Curated Arts arrangement">
                    <!-- <img src="<?= $basePath ?>/assets/images/hero-images/hero2.jpg" alt="Curated Arts arrangement"> -->
                </div>
            </div>
        </div>
    </section>

    <section class="section-premium" id="home-categories">
    <div class="container">

        <div class="premium-title">
            <h2>Discover Collections</h2>
            <p>
                Find exactly what you're looking for, from hand-bound journals
                to elegant gift wrap.
            </p>
        </div>

        <div class="cat-grid-premium">

            <?php foreach ($categories as $category): ?>

                <a href="<?= $basePath ?>/products.php" class="cat-card-modern">

                    <div class="cat-image-modern">
                        <img
                            src="<?= $basePath ?>/assets/images/hero-images/<?= htmlspecialchars($category['image'], ENT_QUOTES, 'UTF-8') ?>"
                            alt="<?= htmlspecialchars($category['title'], ENT_QUOTES, 'UTF-8') ?>"
                        >
                    </div>

                    <h3>
                        <?= htmlspecialchars($category['title'], ENT_QUOTES, 'UTF-8') ?>
                    </h3>

                    <p>
                        <?= htmlspecialchars($category['subtitle'], ENT_QUOTES, 'UTF-8') ?>
                    </p>

                </a>

            <?php endforeach; ?>

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
                        <span class="icon"><?= htmlspecialchars($benefit['icon'], ENT_QUOTES, 'UTF-8') ?></span>
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