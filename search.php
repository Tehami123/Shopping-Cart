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
                    <a href="<?= $basePath ?>/products.php" class="primary-button">Browse Shop</a>
                </div>
            <?php endif; ?>
            
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
