<?php
$pageTitle = 'My Orders - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';

// Mock Orders Data
$mockOrders = [
    [
        'order_id' => '1120034500000001',
        'date' => '15 Aug 2026',
        'total' => 2450.00,
        'payment' => 'Pending',
        'status' => 'Processing',
        'cancellable' => true
    ],
    [
        'order_id' => '1120034500000002',
        'date' => '02 Aug 2026',
        'total' => 850.50,
        'payment' => 'Paid',
        'status' => 'Delivered',
        'cancellable' => false
    ],
    [
        'order_id' => '1120034500000003',
        'date' => '18 Jul 2026',
        'total' => 120.00,
        'payment' => 'Refunded',
        'status' => 'Cancelled',
        'cancellable' => false
    ]
];
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
                    <a href="index.php">Dashboard</a>
                    <a href="orders.php" class="active">My Orders</a>
                    <a href="returns.php">Returns & Replacements</a>
                    <a href="account.php">Account Details</a>
                    <a href="<?= $basePath ?>/auth/login.php" class="logout-link">Logout</a>
                </nav>
            </aside>
            
            <!-- Main Content -->
            <div class="customer-content">
                <h1 class="customer-page-title">My Orders</h1>
                
                <div class="orders-list">
                    <?php foreach ($mockOrders as $order): ?>
                        <div class="order-card">
                            <div class="order-card-header">
                                <div class="order-info-group">
                                    <span class="label">Order Number</span>
                                    <span class="value">#<?= $order['order_id'] ?></span>
                                </div>
                                <div class="order-info-group">
                                    <span class="label">Date Placed</span>
                                    <span class="value"><?= $order['date'] ?></span>
                                </div>
                                <div class="order-info-group">
                                    <span class="label">Total Amount</span>
                                    <span class="value">Rs. <?= number_format($order['total'], 2) ?></span>
                                </div>
                            </div>
                            <div class="order-card-body">
                                <div class="order-status-group">
                                    <div class="status-badge payment-<?= strtolower($order['payment']) ?>">
                                        Payment: <?= $order['payment'] ?>
                                    </div>
                                    <div class="status-badge status-<?= strtolower($order['status']) ?>">
                                        Status: <?= $order['status'] ?>
                                    </div>
                                </div>
                                <div class="order-actions">
                                    <button class="secondary-button order-action-btn">View Details</button>
                                    <?php if ($order['cancellable']): ?>
                                        <button class="text-button cancel-order-btn" onclick="alert('Order cancellation mock triggered.');">Cancel Order</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
            </div>
        </div>
        
    </div>
</main>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
