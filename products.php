<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Shop All Products';
$basePath = '/Shopping-Cart';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/product-card.php';

$categoryFilter = $_GET['category'] ?? 'All';
$searchTerm = trim((string) ($_GET['search'] ?? ''));

$categories = ['All'];
foreach (get_product_categories() as $row) {
    $categories[] = $row['name'];
}

$products = get_all_products(
    $categoryFilter !== 'All' ? normalize_category_name($categoryFilter) : null,
    $searchTerm !== '' ? $searchTerm : null
);

$categoryDisplay = $categoryFilter !== 'All' ? normalize_category_name($categoryFilter) : 'All';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap');

.shop-page {
    font-family: 'Outfit', sans-serif;
    background: #fdfcff;
    position: relative;
    overflow-x: hidden;
    padding-bottom: 80px;
    padding-top: 20px;
}

.shop-page::before {
    content: '';
    position: absolute;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(95,51,168,0.06) 0%, transparent 70%);
    top: -100px;
    right: -200px;
    border-radius: 50%;
    filter: blur(60px);
    z-index: 0;
    pointer-events: none;
}

.shop-page-inner {
    position: relative;
    z-index: 1;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.shop-breadcrumb {
    font-size: 0.85rem;
    color: var(--text-soft);
    margin-bottom: 10px;
    animation: fadeInUp 0.6s ease-out both;
}

.shop-breadcrumb a {
    color: var(--text-soft);
    transition: color 0.2s ease;
}

.shop-breadcrumb a:hover {
    color: var(--brand-primary);
}

.shop-header {
    text-align: center;
    padding: 40px 0 50px;
    animation: fadeInUp 0.6s ease-out 0.1s both;
}

.shop-header h1 {
    font-size: clamp(2.5rem, 4vw, 3.5rem);
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 16px;
    letter-spacing: -0.02em;
}

.shop-header p {
    font-family: 'Inter', sans-serif;
    font-size: 1.1rem;
    color: #666;
    max-width: 600px;
    margin: 0 auto;
}

.shop-category-nav {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 12px;
    margin-bottom: 40px;
    animation: fadeInUp 0.6s ease-out 0.2s both;
}

.category-pill {
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

.category-pill:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.05);
    color: var(--brand-primary);
    border-color: rgba(95, 51, 168, 0.1);
}

.category-pill.active {
    background: linear-gradient(135deg, var(--brand-primary), #7344be);
    color: #fff;
    border-color: transparent;
    box-shadow: 0 8px 20px rgba(95, 51, 168, 0.25);
}

.shop-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 1);
    border-radius: 16px;
    padding: 16px 24px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    animation: fadeInUp 0.6s ease-out 0.3s both;
    flex-wrap: wrap;
    gap: 16px;
}

.shop-search {
    display: flex;
    align-items: center;
    background: #f4f4f4;
    border-radius: 12px;
    padding: 12px 18px;
    flex: 1;
    max-width: 400px;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.shop-search:focus-within {
    background: #fff;
    border-color: rgba(95, 51, 168, 0.3);
    box-shadow: 0 0 0 4px rgba(95, 51, 168, 0.05);
}

.shop-search input {
    border: none;
    background: transparent;
    font-family: 'Inter', sans-serif;
    font-size: 0.95rem;
    color: var(--text);
    width: 100%;
    outline: none;
    margin-left: 10px;
}

.sort-control {
    display: flex;
    align-items: center;
    gap: 12px;
}

.sort-control label {
    font-weight: 500;
    color: var(--text-soft);
    font-size: 0.95rem;
}

.sort-select {
    appearance: none;
    background: #f4f4f4 url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 12px center;
    border: 1px solid transparent;
    padding: 12px 36px 12px 16px;
    border-radius: 12px;
    font-family: 'Inter', sans-serif;
    font-size: 0.95rem;
    color: var(--text);
    font-weight: 500;
    outline: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.sort-select:focus {
    background-color: #fff;
    border-color: rgba(95, 51, 168, 0.3);
    box-shadow: 0 0 0 4px rgba(95, 51, 168, 0.05);
}

.shop-controls {
    margin-bottom: 24px;
    animation: fadeInUp 0.6s ease-out 0.4s both;
}

.product-count {
    font-family: 'Inter', sans-serif;
    font-weight: 600;
    color: var(--text-soft);
}

.shop-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(220px, 1fr));
    gap: 26px;
    animation: fadeInUp 0.6s ease-out 0.5s both;
}

.shop-grid-item .product-card {
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.03);
    box-shadow: 0 8px 24px rgba(0,0,0,0.02);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    background: #fff;
    height: 100%;
}

.shop-grid-item .product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(95, 51, 168, 0.08);
    border-color: rgba(95, 51, 168, 0.1);
}

.shop-empty-state {
    text-align: center;
    padding: 80px 20px;
    background: rgba(255,255,255,0.6);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    border: 1px dashed rgba(0,0,0,0.1);
    display: none;
}

.shop-empty-state.is-visible {
    display: block;
    animation: fadeInUp 0.4s ease-out;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    margin-top: 60px;
    animation: fadeInUp 0.6s ease-out 0.6s both;
}

.page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 44px;
    height: 44px;
    padding: 0 16px;
    border-radius: 12px;
    background: #fff;
    border: 1px solid rgba(0,0,0,0.05);
    color: var(--text);
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(0,0,0,0.02);
}

.page-link:hover:not(.disabled) {
    background: var(--brand-light);
    color: var(--brand-primary);
    border-color: rgba(95, 51, 168, 0.2);
}

.page-link.active {
    background: linear-gradient(135deg, var(--brand-primary), #7344be);
    color: #fff;
    border-color: transparent;
    box-shadow: 0 6px 15px rgba(95, 51, 168, 0.2);
}

.page-link.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: transparent;
    border-color: transparent;
    box-shadow: none;
}

@media (max-width: 980px) {
    .shop-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 768px) {
    .shop-grid { grid-template-columns: repeat(2, 1fr); }
    .shop-toolbar { flex-direction: column; align-items: stretch; }
    .shop-search { max-width: none; }
}
@media (max-width: 480px) {
    .shop-grid { grid-template-columns: 1fr; }
}
</style>

<main class="shop-page">
    <div class="container shop-page-inner">

        <p class="shop-breadcrumb">
            <a href="<?= $basePath ?>/index.php">Home</a> / Shop
        </p>

        <!-- Page Header -->
        <header class="shop-header">
            <h1>Shop All Products</h1>
            <p>Stationery, gift articles, greeting cards and everyday essentials — browse by category or search for something specific.</p>
        </header>

        <!-- Category Nav -->
        <nav class="shop-category-nav" aria-label="Product Categories">
            <?php foreach ($categories as $index => $cat): ?>
                <a
                    href="<?= $basePath ?>/products.php?category=<?= urlencode($cat) ?>"
                    class="category-pill <?= ($categoryDisplay === $cat) ? 'active' : '' ?>"
                    data-filter-category="<?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>"
                >
                    <?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <!-- Search + Sort Toolbar -->
        <div class="shop-toolbar">
            <form class="shop-search" role="search" method="GET" action="<?= $basePath ?>/products.php">
                <span class="search-icon" style="color: var(--text-soft); font-size: 1.2rem;">⌕</span>
                <input type="text" id="shopSearchInput" name="search" value="<?= htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8') ?>" placeholder="Search products..." aria-label="Search products in this category">
                <?php if ($categoryDisplay !== 'All'): ?>
                    <input type="hidden" name="category" value="<?= htmlspecialchars($categoryDisplay, ENT_QUOTES, 'UTF-8') ?>">
                <?php endif; ?>
            </form>

            <div class="sort-control">
                <label for="sortSelect">Sort by:</label>
                <select id="sortSelect" class="sort-select">
                    <option value="featured">Featured</option>
                    <option value="price-asc">Price: Low to High</option>
                    <option value="price-desc">Price: High to Low</option>
                    <option value="rating-desc">Top Rated</option>
                </select>
            </div>
        </div>

        <!-- Controls Row -->
        <div class="shop-controls">
            <span class="product-count" id="productCount">Showing <?= count($products) ?> products</span>
        </div>

        <!-- Product Grid -->
        <div class="shop-grid" id="shopGrid">
            <?php foreach ($products as $product):
                $priceNumeric = (float) preg_replace('/[^0-9.]/', '', $product['price']);
                $ratingNumeric = (int) ($product['rating'] ?? 0);
            ?>
                <div
                    class="shop-grid-item"
                    data-category="<?= htmlspecialchars($product['category'], ENT_QUOTES, 'UTF-8') ?>"
                    data-name="<?= htmlspecialchars(strtolower($product['name']), ENT_QUOTES, 'UTF-8') ?>"
                    data-price="<?= $priceNumeric ?>"
                    data-rating="<?= $ratingNumeric ?>"
                >
                    <?php renderProductCard($product); ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Empty state -->
        <div class="shop-empty-state" id="shopEmptyState">
            <p style="font-family: 'Inter', sans-serif; font-size: 1.1rem; color: #666; margin-bottom: 16px;">No products match your search.</p>
            <button type="button" class="page-link" id="shopClearFilters" style="cursor: pointer;">Clear search &amp; filters</button>
        </div>

        
    </div>
</main>

<script>
(function () {
    var grid = document.getElementById('shopGrid');
    var items = Array.prototype.slice.call(grid.querySelectorAll('.shop-grid-item'));
    var searchInput = document.getElementById('shopSearchInput');
    var sortSelect = document.getElementById('sortSelect');
    var pills = Array.prototype.slice.call(document.querySelectorAll('.category-pill'));
    var countLabel = document.getElementById('productCount');
    var emptyState = document.getElementById('shopEmptyState');
    var clearBtn = document.getElementById('shopClearFilters');

    var activeCategory = 'All';

    function applyFilters() {
        var query = searchInput.value.trim().toLowerCase();
        var visibleCount = 0;

        items.forEach(function (item) {
            var matchesCategory = activeCategory === 'All' || item.dataset.category === activeCategory;
            var matchesSearch = query === '' || item.dataset.name.indexOf(query) !== -1;
            var isVisible = matchesCategory && matchesSearch;
            item.style.display = isVisible ? '' : 'none';
            if (isVisible) visibleCount++;
        });

        countLabel.textContent = 'Showing ' + visibleCount + ' product' + (visibleCount === 1 ? '' : 's');
        emptyState.classList.toggle('is-visible', visibleCount === 0);
        grid.style.display = visibleCount === 0 ? 'none' : 'grid';
    }

    function applySort() {
        var mode = sortSelect.value;
        var sorted = items.slice();

        if (mode === 'price-asc') {
            sorted.sort(function (a, b) { return parseFloat(a.dataset.price) - parseFloat(b.dataset.price); });
        } else if (mode === 'price-desc') {
            sorted.sort(function (a, b) { return parseFloat(b.dataset.price) - parseFloat(a.dataset.price); });
        } else if (mode === 'rating-desc') {
            sorted.sort(function (a, b) { return parseInt(b.dataset.rating, 10) - parseInt(a.dataset.rating, 10); });
        }
        // 'featured' keeps original PHP order — nothing to do.

        sorted.forEach(function (item) { grid.appendChild(item); });
    }

    searchInput.addEventListener('input', applyFilters);

    sortSelect.addEventListener('change', function () {
        applySort();
        applyFilters();
    });

    pills.forEach(function (pill) {
        pill.addEventListener('click', function () {
            pills.forEach(function (p) { p.classList.remove('active'); });
            pill.classList.add('active');
            activeCategory = pill.dataset.filterCategory;
            applyFilters();
        });
    });

    clearBtn.addEventListener('click', function () {
        searchInput.value = '';
        activeCategory = 'All';
        pills.forEach(function (p) { p.classList.remove('active'); });
        pills[0].classList.add('active');
        applyFilters();
    });
})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>