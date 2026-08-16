<?php
$pageTitle = 'Search - Arts';
$basePath = '/Shopping%20Cart';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/product-card.php';

// Master mock product data (shared with product.php for consistent IDs)
$allProducts = [
    'ART1001' => ['category' => 'Stationery', 'name' => 'Lavender Dream Journal', 'price' => '$24.00', 'rating' => 5, 'reviews' => 128, 'stock' => 'In Stock', 'badge' => 'New', 'image' => $basePath . '/assets/images/stationery.svg'],
    'ART1002' => ['category' => 'Stationery', 'name' => 'Classic Notebook', 'price' => '$16.00', 'rating' => 4, 'reviews' => 85, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/stationery.svg'],
    'ART1003' => ['category' => 'Stationery', 'name' => 'Premium Writing Set', 'price' => '$32.00', 'rating' => 5, 'reviews' => 42, 'stock' => 'Low Stock', 'badge' => '', 'image' => $basePath . '/assets/images/stationery.svg'],
    'ART1004' => ['category' => 'Gift Articles', 'name' => 'Ceramic Gift Box', 'price' => '$28.00', 'rating' => 4, 'reviews' => 64, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/gifts.svg'],
    'ART1005' => ['category' => 'Gift Articles', 'name' => 'Decorative Gift Set', 'price' => '$45.00', 'rating' => 5, 'reviews' => 112, 'stock' => 'In Stock', 'badge' => 'Sale', 'image' => $basePath . '/assets/images/gifts.svg'],
    'ART1006' => ['category' => 'Greeting Cards', 'name' => 'Botanical Watercolor Card', 'price' => '$5.50', 'rating' => 5, 'reviews' => 24, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/cards.svg'],
    'ART1007' => ['category' => 'Greeting Cards', 'name' => 'Birthday Greeting Card', 'price' => '$4.50', 'rating' => 4, 'reviews' => 18, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/cards.svg'],
    'ART1008' => ['category' => 'Dolls', 'name' => 'Soft Plush Doll', 'price' => '$22.00', 'rating' => 5, 'reviews' => 76, 'stock' => 'In Stock', 'badge' => 'New', 'oldPrice' => '$28.00', 'image' => $basePath . '/assets/images/toys.svg'],
    'ART1009' => ['category' => 'Dolls', 'name' => 'Mini Teddy Bear', 'price' => '$14.00', 'rating' => 4, 'reviews' => 32, 'stock' => 'Out of Stock', 'badge' => '', 'image' => $basePath . '/assets/images/toys.svg'],
    'ART1010' => ['category' => 'Files', 'name' => 'Document File Set', 'price' => '$12.00', 'rating' => 4, 'reviews' => 41, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/stationery.svg'],
    'ART1011' => ['category' => 'Files', 'name' => 'Premium Office File', 'price' => '$18.50', 'rating' => 5, 'reviews' => 58, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/stationery.svg'],
    'ART1012' => ['category' => 'Handbags', 'name' => 'Casual Handbag', 'price' => '$48.00', 'rating' => 4, 'reviews' => 29, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/gifts.svg'],
    'ART1013' => ['category' => 'Writing', 'name' => 'Rose Gold Pen Set Trio', 'price' => '$18.50', 'rating' => 4, 'reviews' => 85, 'stock' => 'Low Stock', 'badge' => 'New', 'image' => $basePath . '/assets/images/stationery.svg'],
    'ART1014' => ['category' => 'Lifestyle', 'name' => 'Ceramic Desk Organizer', 'price' => '$32.00', 'rating' => 4, 'reviews' => 12, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/gifts.svg'],
    'ART1015' => ['category' => 'Greeting Cards', 'name' => 'Botanical Watercolor Card Set', 'price' => '$15.00', 'rating' => 5, 'reviews' => 46, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/cards.svg'],
];

// Parse input
$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$sort = isset($_GET['sort']) ? trim($_GET['sort']) : '';

$results = [];
$hasSearched = isset($_GET['q']);

if ($hasSearched) {
    foreach ($allProducts as $id => $p) {
        $p['id'] = $id;
        $match = true;
        
        // text search
        if ($query !== '') {
            $qLower = strtolower($query);
            if (strpos(strtolower($p['name']), $qLower) === false && 
                strpos(strtolower($p['category']), $qLower) === false) {
                $match = false;
            }
        }
        
        // category filter
        if ($category !== '') {
            if (strtolower($p['category']) !== strtolower($category)) {
                $match = false;
            }
        }
        
        if ($match) {
            $results[] = $p;
        }
    }
    
    // Sorting mock logic
    if ($sort === 'price_asc') {
        usort($results, function($a, $b) {
            $pa = (float) str_replace('$', '', $a['price']);
            $pb = (float) str_replace('$', '', $b['price']);
            return $pa <=> $pb;
        });
    } elseif ($sort === 'price_desc') {
        usort($results, function($a, $b) {
            $pa = (float) str_replace('$', '', $a['price']);
            $pb = (float) str_replace('$', '', $b['price']);
            return $pb <=> $pa;
        });
    } elseif ($sort === 'name_asc') {
        usort($results, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
    }
}
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap');

/* Premium Aesthetics for Search Page */
.search-page {
    font-family: 'Outfit', sans-serif;
    background: #fdfcff;
    position: relative;
    overflow-x: hidden;
    padding-bottom: 80px;
    min-height: 70vh;
}

.search-page::before {
    content: '';
    position: absolute;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(95,51,168,0.06) 0%, transparent 70%);
    top: -100px;
    left: 50%;
    transform: translateX(-50%);
    border-radius: 50%;
    filter: blur(60px);
    z-index: 0;
    pointer-events: none;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.search-header {
    position: relative;
    z-index: 1;
    padding: 60px 0 40px;
    text-align: center;
    animation: fadeInUp 0.6s ease-out both;
}

.search-title {
    font-size: clamp(2.5rem, 4vw, 3.5rem);
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 24px;
    letter-spacing: -0.02em;
}

.search-form-large {
    max-width: 640px;
    margin: 0 auto;
    display: flex;
    background: #fff;
    border-radius: 20px;
    padding: 10px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.05);
    border: 1px solid rgba(0,0,0,0.03);
    transition: all 0.3s ease;
}

.search-form-large:focus-within {
    box-shadow: 0 20px 40px rgba(95, 51, 168, 0.1);
    border-color: rgba(95, 51, 168, 0.2);
}

.search-input-large {
    flex: 1;
    border: none;
    background: transparent;
    padding: 16px 24px;
    font-family: 'Inter', sans-serif;
    font-size: 1.15rem;
    color: var(--text);
    outline: none;
}

.search-input-large::placeholder {
    color: #aaa;
}

.search-btn-large {
    background: linear-gradient(135deg, var(--brand-primary), #7344be);
    color: #fff;
    border: none;
    padding: 0 32px;
    border-radius: 14px;
    font-family: 'Outfit', sans-serif;
    font-weight: 600;
    font-size: 1.05rem;
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.search-btn-large:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(95, 51, 168, 0.3);
}

.search-body {
    position: relative;
    z-index: 1;
}

.search-initial-state {
    text-align: center;
    padding: 40px 0;
    animation: fadeInUp 0.6s ease-out 0.2s both;
}

.search-initial-state p {
    font-family: 'Inter', sans-serif;
    font-size: 1.1rem;
    color: #666;
    margin-bottom: 40px;
}

.popular-categories h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 20px;
}

.category-pills {
    display: flex;
    justify-content: center;
    gap: 12px;
    flex-wrap: wrap;
}

.category-pills .pill {
    padding: 10px 24px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.7);
    border: 1px solid rgba(0, 0, 0, 0.05);
    color: var(--text-soft);
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 10px rgba(0,0,0,0.02);
    backdrop-filter: blur(10px);
}

.category-pills .pill:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.05);
    color: var(--brand-primary);
    border-color: rgba(95, 51, 168, 0.1);
}

.search-results-meta {
    margin-bottom: 30px;
    animation: fadeInUp 0.6s ease-out 0.1s both;
}

.search-results-meta h2 {
    font-size: 2rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
    letter-spacing: -0.01em;
}

.search-term {
    color: var(--brand-primary);
}

.result-count {
    font-family: 'Inter', sans-serif;
    font-size: 1.1rem;
    font-weight: 400;
    color: #666;
    margin-left: 12px;
}

.search-controls {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 1);
    border-radius: 16px;
    padding: 20px 24px;
    margin-bottom: 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    animation: fadeInUp 0.6s ease-out 0.2s both;
}

.search-filters-form {
    display: flex;
    gap: 24px;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 12px;
}

.filter-group label {
    font-family: 'Inter', sans-serif;
    font-weight: 500;
    color: var(--text-soft);
    font-size: 0.95rem;
}

.filter-select {
    appearance: none;
    background: #f4f4f4 url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 12px center;
    border: 1px solid transparent;
    padding: 10px 36px 10px 16px;
    border-radius: 12px;
    font-family: 'Inter', sans-serif;
    font-size: 0.95rem;
    color: var(--text);
    font-weight: 500;
    outline: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-select:focus {
    background-color: #fff;
    border-color: rgba(95, 51, 168, 0.3);
    box-shadow: 0 0 0 4px rgba(95, 51, 168, 0.05);
}

.shop-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(220px, 1fr));
    gap: 26px;
    animation: fadeInUp 0.6s ease-out 0.3s both;
}

.shop-grid .product-card {
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.03);
    box-shadow: 0 8px 24px rgba(0,0,0,0.02);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    background: #fff;
    height: 100%;
}

.shop-grid .product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(95, 51, 168, 0.08);
    border-color: rgba(95, 51, 168, 0.1);
}

.search-empty-state {
    text-align: center;
    padding: 80px 20px;
    background: rgba(255,255,255,0.6);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    border: 1px dashed rgba(0,0,0,0.1);
    animation: fadeInUp 0.6s ease-out 0.2s both;
}

.empty-icon {
    font-size: 3.5rem;
    margin-bottom: 20px;
    opacity: 0.6;
}

.search-empty-state h2 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 12px;
}

.search-empty-state p {
    font-family: 'Inter', sans-serif;
    font-size: 1.1rem;
    color: #666;
    margin-bottom: 30px;
    line-height: 1.6;
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
}

.primary-button-glow:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(95, 51, 168, 0.4);
}

@media (max-width: 980px) {
    .shop-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 768px) {
    .shop-grid { grid-template-columns: repeat(2, 1fr); }
    .search-form-large { flex-direction: column; border-radius: 16px; padding: 16px; gap: 12px; }
    .search-input-large { width: 100%; padding: 12px; }
    .search-btn-large { padding: 14px; width: 100%; }
}
@media (max-width: 480px) {
    .shop-grid { grid-template-columns: 1fr; }
}
</style>

<main class="search-page">
    <div class="search-header">
        <div class="container">
            <h1 class="search-title">Search Arts</h1>
            <form action="<?= $basePath ?>/search.php" method="GET" class="search-form-large">
                <input type="text" name="q" value="<?= htmlspecialchars($query, ENT_QUOTES, 'UTF-8') ?>" placeholder="What are you looking for?" class="search-input-large" autofocus>
                <?php if ($category !== ''): ?>
                    <input type="hidden" name="category" value="<?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?>">
                <?php endif; ?>
                <?php if ($sort !== ''): ?>
                    <input type="hidden" name="sort" value="<?= htmlspecialchars($sort, ENT_QUOTES, 'UTF-8') ?>">
                <?php endif; ?>
                <button type="submit" class="search-btn-large">Search</button>
            </form>
        </div>
    </div>
    
    <div class="container search-body">
        <?php if (!$hasSearched): ?>
            <!-- Initial State -->
            <div class="search-initial-state">
                <p>Enter a search term above to find products.</p>
                <div class="popular-categories">
                    <h3>Popular Categories</h3>
                    <div class="category-pills">
                        <a href="?q=&category=Stationery" class="pill">Stationery</a>
                        <a href="?q=&category=Gift Articles" class="pill">Gift Articles</a>
                        <a href="?q=&category=Greeting Cards" class="pill">Greeting Cards</a>
                        <a href="?q=&category=Dolls" class="pill">Dolls</a>
                    </div>
                </div>
            </div>
            
        <?php else: ?>
            <!-- Results State -->
            <div class="search-results-meta">
                <h2>
                    <?php if ($query !== ''): ?>
                        Results for <span class="search-term">"<?= htmlspecialchars($query, ENT_QUOTES, 'UTF-8') ?>"</span>
                    <?php else: ?>
                        All Products
                    <?php endif; ?>
                    <span class="result-count">(<?= count($results) ?> products)</span>
                </h2>
            </div>
            
            <?php if (count($results) > 0): ?>
                
                <div class="shop-controls search-controls">
                    <form action="<?= $basePath ?>/search.php" method="GET" class="search-filters-form" id="filterForm">
                        <input type="hidden" name="q" value="<?= htmlspecialchars($query, ENT_QUOTES, 'UTF-8') ?>">
                        
                        <div class="filter-group">
                            <label for="category">Category</label>
                            <select name="category" id="category" class="filter-select" onchange="document.getElementById('filterForm').submit();">
                                <option value="">All Categories</option>
                                <?php
                                $cats = ['Stationery', 'Gift Articles', 'Greeting Cards', 'Dolls', 'Files', 'Handbags', 'Wallets', 'Beauty Products', 'Writing', 'Lifestyle'];
                                foreach ($cats as $c) {
                                    $sel = (strtolower($c) === strtolower($category)) ? 'selected' : '';
                                    echo "<option value=\"".htmlspecialchars($c, ENT_QUOTES, 'UTF-8')."\" $sel>".htmlspecialchars($c, ENT_QUOTES, 'UTF-8')."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="sort">Sort By</label>
                            <select name="sort" id="sort" class="filter-select" onchange="document.getElementById('filterForm').submit();">
                                <option value="">Relevance</option>
                                <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                                <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                                <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Name: A-Z</option>
                            </select>
                        </div>
                    </form>
                </div>
                
                <div class="shop-grid">
                    <?php foreach ($results as $product): ?>
                        <?php renderProductCard($product); ?>
                    <?php endforeach; ?>
                </div>
                
            <?php else: ?>
                <!-- No Results State -->
                <div class="search-empty-state">
                    <div class="empty-icon">🔍</div>
                    <h2>No products found</h2>
                    <p>We couldn't find anything matching "<?= htmlspecialchars($query, ENT_QUOTES, 'UTF-8') ?>".<br>Try adjusting your search or browse our categories.</p>
                    <a href="<?= $basePath ?>/products.php" class="primary-button-glow">Browse Shop</a>
                </div>
            <?php endif; ?>
            
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
