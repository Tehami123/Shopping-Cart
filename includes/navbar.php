<?php $basePath = '/Shopping%20Cart'; ?>
<header class="topbar">
    <div class="container nav-container">
        <a href="<?= $basePath ?>/index.php" class="brand" aria-label="Arts home">Arts</a>

        <div class="search-box" role="search">
            <span class="search-icon">⌕</span>
            <input type="text" placeholder="Search products..." aria-label="Search products">
        </div>

        <nav class="main-nav" aria-label="Main navigation">
            <a href="<?= $basePath ?>/index.php">Home</a>
            <a href="<?= $basePath ?>/products.php">Shop</a>
            <a href="<?= $basePath ?>/search.php">Search</a>
            <a href="<?= $basePath ?>/faq.php">FAQ</a>
        </nav>

        <div class="nav-actions">
            <a href="<?= $basePath ?>/cart.php" class="cart-pill" aria-label="Shopping cart">
                <span class="cart-icon">🛒</span>
                <span class="cart-label">Cart</span>
            </a>
            <a href="<?= $basePath ?>/auth/login.php" class="login-button">Login</a>
        </div>
    </div>
</header>
