<?php
$pageTitle = 'Returns & Replacements - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';

// Mock Delivered Items Data
// Demonstrating the 7-day return rule UI.
$mockItems = [
    [
        'order_id' => '1120034500000002',
        'product' => 'Lavender Dream Journal',
        'delivered' => '12 Aug 2026',
        'eligible' => true, // Within 7 days
        'image' => $basePath . '/assets/images/stationery.svg'
    ],
    [
        'order_id' => '1120034500000002',
        'product' => 'Ceramic Gift Box',
        'delivered' => '12 Aug 2026',
        'eligible' => true, // Within 7 days
        'image' => $basePath . '/assets/images/gifts.svg'
    ],
    [
        'order_id' => '1120034500000003',
        'product' => 'Document File Set',
        'delivered' => '20 Jul 2026',
        'eligible' => false, // Outside 7 days
        'image' => $basePath . '/assets/images/stationery.svg'
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
                    <a href="orders.php">My Orders</a>
                    <a href="returns.php" class="active">Returns & Replacements</a>
                    <a href="account.php">Account Details</a>
                    <a href="<?= $basePath ?>/auth/login.php" class="logout-link">Logout</a>
                </nav>
            </aside>
            
            <!-- Main Content -->
            <div class="customer-content">
                <h1 class="customer-page-title">Returns & Replacements</h1>
                
                <div class="policy-notice">
                    <p><strong>Return Policy:</strong> You can request a return or replacement for eligible items within <strong>7 days</strong> of delivery.</p>
                </div>
                
                <div class="returns-list">
                    <?php foreach ($mockItems as $item): ?>
                        <div class="return-item-card <?= $item['eligible'] ? '' : 'expired' ?>">
                            <div class="return-item-image">
                                <img src="<?= htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8') ?>" alt="Product">
                            </div>
                            <div class="return-item-details">
                                <div class="return-item-name"><?= htmlspecialchars($item['product'], ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="return-item-meta">Order #<?= $item['order_id'] ?></div>
                                <div class="return-item-meta">Delivered: <?= $item['delivered'] ?></div>
                            </div>
                            
                            <div class="return-item-actions">
                                <?php if ($item['eligible']): ?>
                                    <button class="primary-button return-btn" onclick="openReturnModal('<?= $item['product'] ?>')">Return / Replace</button>
                                <?php else: ?>
                                    <div class="expired-notice">Return period expired</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
            </div>
        </div>
        
    </div>
</main>

<!-- Mock Return Modal (Hidden by Default) -->
<div id="returnModal" class="mock-modal" style="display: none;">
    <div class="mock-modal-content">
        <h3>Request Return or Replacement</h3>
        <p>Product: <strong id="modalProductName"></strong></p>
        
        <form id="returnForm">
            <div class="form-group" style="margin-top: 20px;">
                <label for="returnType">Request Type</label>
                <select id="returnType" class="form-select" required>
                    <option value="return">Return for Refund</option>
                    <option value="replace">Replacement</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="returnReason">Reason</label>
                <select id="returnReason" class="form-select" required>
                    <option value="" disabled selected>Select a reason...</option>
                    <option value="defective">Item is defective or broken</option>
                    <option value="wrong_item">Received wrong item</option>
                    <option value="not_needed">No longer needed</option>
                    <option value="other">Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="returnComments">Additional Comments</label>
                <textarea id="returnComments" class="form-textarea" rows="3"></textarea>
            </div>
            
            <div class="form-actions" style="margin-top: 24px; display: flex; gap: 16px;">
                <button type="submit" class="primary-button">Submit Request</button>
                <button type="button" class="secondary-button" onclick="closeReturnModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Inline styling for modal since it's a small component strictly for this page */
.mock-modal {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}
.mock-modal-content {
    background: #fff;
    padding: 32px;
    border-radius: 8px;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}
.mock-modal-content h3 {
    margin-top: 0;
    color: var(--brand-primary-dark);
}
</style>

<script>
function openReturnModal(productName) {
    document.getElementById('modalProductName').textContent = productName;
    document.getElementById('returnModal').style.display = 'flex';
}

function closeReturnModal() {
    document.getElementById('returnModal').style.display = 'none';
    document.getElementById('returnForm').reset();
}

document.addEventListener('DOMContentLoaded', function() {
    const returnForm = document.getElementById('returnForm');
    returnForm.addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Return/Replacement request submitted successfully! (Frontend Mock)');
        closeReturnModal();
    });
});
</script>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
