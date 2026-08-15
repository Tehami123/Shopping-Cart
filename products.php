<?php
$pageTitle = 'Shop All Products';
$basePath = '/Shopping%20Cart';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/product-card.php';

$categories = [
    'All',
    'Stationery',
    'Gift Articles',
    'Greeting Cards',
    'Dolls',
    'Files',
    'Handbags',
    'Wallets',
    'Beauty Products'
];

// Realistic mock products for Arts project
$products = [
    ['id' => 'ART1001', 'category' => 'Stationery', 'name' => 'Lavender Dream Journal', 'price' => '$24.00', 'rating' => 5, 'reviews' => 128, 'stock' => 'In Stock', 'badge' => 'New', 'image' => $basePath . '/assets/images/stationery.svg'],
    ['id' => 'ART1002', 'category' => 'Stationery', 'name' => 'Classic Notebook', 'price' => '$16.00', 'rating' => 4, 'reviews' => 85, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/stationery.svg'],
    ['id' => 'ART1003', 'category' => 'Stationery', 'name' => 'Premium Writing Set', 'price' => '$32.00', 'rating' => 5, 'reviews' => 42, 'stock' => 'Low Stock', 'badge' => '', 'image' => $basePath . '/assets/images/stationery.svg'],
    ['id' => 'ART1004', 'category' => 'Gift Articles', 'name' => 'Ceramic Gift Box', 'price' => '$28.00', 'rating' => 4, 'reviews' => 64, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/gifts.svg'],
    ['id' => 'ART1005', 'category' => 'Gift Articles', 'name' => 'Decorative Gift Set', 'price' => '$45.00', 'rating' => 5, 'reviews' => 112, 'stock' => 'In Stock', 'badge' => 'Sale', 'image' => $basePath . '/assets/images/gifts.svg'],
    ['id' => 'ART1006', 'category' => 'Greeting Cards', 'name' => 'Botanical Watercolor Card', 'price' => '$5.50', 'rating' => 5, 'reviews' => 24, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/cards.svg'],
    ['id' => 'ART1007', 'category' => 'Greeting Cards', 'name' => 'Birthday Greeting Card', 'price' => '$4.50', 'rating' => 4, 'reviews' => 18, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/cards.svg'],
    ['id' => 'ART1008', 'category' => 'Dolls', 'name' => 'Soft Plush Doll', 'price' => '$22.00', 'rating' => 5, 'reviews' => 76, 'stock' => 'In Stock', 'badge' => 'New', 'oldPrice' => '$28.00', 'image' => $basePath . '/assets/images/toys.svg'],
    ['id' => 'ART1009', 'category' => 'Dolls', 'name' => 'Mini Teddy Bear', 'price' => '$14.00', 'rating' => 4, 'reviews' => 32, 'stock' => 'Out of Stock', 'badge' => '', 'image' => $basePath . '/assets/images/toys.svg'],
    ['id' => 'ART1010', 'category' => 'Files', 'name' => 'Document File Set', 'price' => '$12.00', 'rating' => 4, 'reviews' => 41, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/stationery.svg'],
    ['id' => 'ART1011', 'category' => 'Files', 'name' => 'Premium Office File', 'price' => '$18.50', 'rating' => 5, 'reviews' => 58, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/stationery.svg'],
    ['id' => 'ART1012', 'category' => 'Handbags', 'name' => 'Casual Handbag', 'price' => '$48.00', 'rating' => 4, 'reviews' => 29, 'stock' => 'In Stock', 'badge' => '', 'image' => $basePath . '/assets/images/gifts.svg'],
];
?>

<main class="shop-page">
    <div class="container">

        <p class="shop-breadcrumb">
            <a href="<?= $basePath ?>/index.php">Home</a> / Shop
        </p>

        <!-- Page Header -->
        <header class="shop-header">
            <h1>Shop All Products</h1>
            <p>Stationery, gift articles, greeting cards and everyday essentials — browse by category or search for something specific.</p>
        </header>

        <!-- Category Nav (client-side filter, no page reload) -->
        <nav class="shop-category-nav" aria-label="Product Categories">
            <?php foreach ($categories as $index => $cat): ?>
                <button
                    type="button"
                    class="category-pill <?= $index === 0 ? 'active' : '' ?>"
                    data-filter-category="<?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>"
                >
                    <?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>
                </button>
            <?php endforeach; ?>
        </nav>

        <!-- Search + Sort Toolbar -->
        <div class="shop-toolbar">
            <div class="shop-search" role="search">
                <span class="search-icon">⌕</span>
                <input type="text" id="shopSearchInput" placeholder="Search products..." aria-label="Search products in this category">
            </div>

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

        <!-- Empty state (shown by JS when a search/filter combo matches nothing) -->
        <div class="shop-empty-state" id="shopEmptyState">
            <p>No products match your search.</p>
            <button type="button" class="text-link" id="shopClearFilters">Clear search &amp; filters</button>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <a href="#" class="page-link prev disabled" aria-disabled="true">← Previous</a>
            <a href="#" class="page-link active" aria-current="page">1</a>
            <a href="#" class="page-link">2</a>
            <a href="#" class="page-link">3</a>
            <a href="#" class="page-link next">Next →</a>
        </div>
    </div>
</main>

<script>
// Lightweight client-side search + category filter + sort for the mock
// product grid. No backend/database involved — this only shows/hides and
// reorders the .shop-grid-item wrappers that were already rendered by PHP.
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