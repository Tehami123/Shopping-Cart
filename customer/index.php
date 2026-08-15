<?php
$pageTitle = 'My Dashboard - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
?>

<main class="customer-page">
    <div class="container">
        
        <div class="customer-layout">
            
            <!-- Customer Navigation Sidebar -->
            <aside class="customer-sidebar">
                <div class="customer-profile-brief">
                    <div class="avatar">JD</div>
                    <div class="info">
                        <strong>Jane Doe</strong>
                        <span>jane@example.com</span>
                    </div>
                </div>
                <nav class="customer-nav">
                    <a href="index.php" class="active">Dashboard</a>
                    <a href="orders.php">My Orders</a>
                    <a href="returns.php">Returns & Replacements</a>
                    <a href="account.php">Account Details</a>
                    <a href="<?= $basePath ?>/auth/login.php" class="logout-link">Logout</a>
                </nav>
            </aside>
            
            <!-- Main Content -->
            <div class="customer-content">
                <h1 class="customer-page-title">My Dashboard</h1>
                <p class="customer-welcome">Welcome back, Jane! Here's an overview of your account.</p>
                
                <div class="dashboard-stats-grid">
                    <div class="stat-card">
                        <div class="stat-value">12</div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">2</div>
                        <div class="stat-label">Pending Orders</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">1</div>
                        <div class="stat-label">Active Returns</div>
                    </div>
                </div>
                
                <h2 class="customer-section-title">Quick Links</h2>
                <div class="quick-links-grid">
                    <a href="<?= $basePath ?>/products.php" class="quick-link-card">
                        <span class="icon">🛍️</span>
                        <span>Shop New Arrivals</span>
                    </a>
                    <a href="<?= $basePath ?>/cart.php" class="quick-link-card">
                        <span class="icon">🛒</span>
                        <span>View Cart (3)</span>
                    </a>
                    <a href="orders.php" class="quick-link-card">
                        <span class="icon">📦</span>
                        <span>Track Recent Order</span>
                    </a>
                </div>
                
            </div>
        </div>
        
    </div>
</main>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
