<?php
require_once __DIR__ . '/auth.php';

$basePath = '/Shopping%20Cart';
$navRole = current_user_role();
$navUser = current_user();
?>
<header class="topbar">
    <div class="container nav-container">
       <a href="<?= $basePath ?>/index.php" class="site-logo">
    <img 
        src="<?= $basePath ?>/assets/images/logo/logo.jpg"
        alt="Arts"
        class="site-logo-image"
    >
</a>

        <div class="search-box" role="search">
            <span class="search-icon">⌕</span>
            <input type="text" placeholder="Search products..." aria-label="Search products">
        </div>

        <nav class="main-nav" aria-label="Main navigation">
            <a href="<?= $basePath ?>/index.php">Home</a>
            <a href="<?= $basePath ?>/products.php">Shop</a>
            <a href="<?= $basePath ?>/search.php">Search</a>
            <a href="<?= $basePath ?>/faq.php">FAQ</a>
            <?php if ($navRole === 'customer'): ?>
                <a href="<?= $basePath ?>/customer/index.php">My Account</a>
            <?php elseif ($navRole === 'employee'): ?>
                <a href="<?= $basePath ?>/employee/index.php">Employee</a>
            <?php elseif ($navRole === 'admin'): ?>
                <a href="<?= $basePath ?>/admin/index.php">Admin</a>
            <?php endif; ?>
        </nav>

        <div class="nav-actions">
            <a href="<?= $basePath ?>/cart.php" class="cart-pill" aria-label="Shopping cart">
                <span class="cart-icon">🛒</span>
                <span class="cart-label">Cart</span>
            </a>

            <?php if ($navRole === 'customer'): ?>
                <a href="<?= $basePath ?>/customer/account.php" class="login-button"><?= htmlspecialchars($navUser['email'] ?? 'Account', ENT_QUOTES, 'UTF-8') ?></a>
                <a href="<?= $basePath ?>/auth/logout.php" class="login-button" style="background:#fff;border:1px solid rgba(0,0,0,0.1);color:var(--text);">Logout</a>
            <?php elseif ($navRole === 'employee'): ?>
                <a href="<?= $basePath ?>/employee/index.php" class="login-button">Employee Portal</a>
                <a href="<?= $basePath ?>/auth/logout.php" class="login-button" style="background:#fff;border:1px solid rgba(0,0,0,0.1);color:var(--text);">Logout</a>
            <?php elseif ($navRole === 'admin'): ?>
                <a href="<?= $basePath ?>/admin/index.php" class="login-button">Admin</a>
                <a href="<?= $basePath ?>/auth/logout.php" class="login-button" style="background:#fff;border:1px solid rgba(0,0,0,0.1);color:var(--text);">Logout</a>
            <?php else: ?>
                <a href="<?= $basePath ?>/auth/login.php" class="login-button">Login</a>
                <a href="<?= $basePath ?>/auth/register.php" class="login-button" style="background:#fff;border:1px solid rgba(0,0,0,0.1);color:var(--text);">Register</a>
            <?php endif; ?>
        </div>
    </div>
</header>
