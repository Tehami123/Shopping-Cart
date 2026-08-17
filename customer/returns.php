<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_customer();

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

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap');

/* Premium Aesthetics for Customer Dashboard */
.customer-page {
    font-family: 'Outfit', sans-serif;
    background: #fdfcff;
    position: relative;
    overflow-x: hidden;
    padding: 40px 0 80px;
    min-height: calc(100vh - 200px);
}

.customer-page::before {
    content: '';
    position: absolute;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(95,51,168,0.06) 0%, transparent 70%);
    top: -100px;
    left: -100px;
    border-radius: 50%;
    filter: blur(60px);
    z-index: 0;
    pointer-events: none;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.customer-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 40px;
    position: relative;
    z-index: 1;
}

/* Sidebar */
.customer-sidebar {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,1);
    box-shadow: 0 15px 35px rgba(0,0,0,0.03);
    padding: 30px 20px;
    align-self: start;
    animation: fadeInUp 0.6s ease-out both;
}

.customer-profile-brief {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.customer-profile-brief .avatar {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, var(--brand-primary), #7344be);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    font-weight: 700;
    box-shadow: 0 8px 16px rgba(95, 51, 168, 0.2);
}

.customer-profile-brief .info strong {
    display: block;
    font-size: 1.15rem;
    color: #1a1a1a;
    font-weight: 600;
}

.customer-profile-brief .info span {
    font-family: 'Inter', sans-serif;
    font-size: 0.9rem;
    color: var(--text-soft);
}

.customer-nav {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.customer-nav a {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    border-radius: 12px;
    color: var(--text-soft);
    font-weight: 500;
    font-family: 'Inter', sans-serif;
    text-decoration: none;
    transition: all 0.3s ease;
}

.customer-nav a:hover {
    background: rgba(95, 51, 168, 0.04);
    color: var(--brand-primary);
}

.customer-nav a.active {
    background: var(--brand-primary);
    color: #fff;
    box-shadow: 0 4px 12px rgba(95, 51, 168, 0.2);
}

.customer-nav a.logout-link {
    color: #e53935;
    margin-top: 20px;
}

.customer-nav a.logout-link:hover {
    background: rgba(229, 57, 53, 0.1);
}

/* Main Content */
.customer-content {
    animation: fadeInUp 0.6s ease-out 0.1s both;
}

.customer-page-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 20px;
    letter-spacing: -0.02em;
}

/* Returns specific styles */
.policy-notice {
    background: rgba(95, 51, 168, 0.05);
    border-left: 4px solid var(--brand-primary);
    padding: 16px 20px;
    border-radius: 0 12px 12px 0;
    margin-bottom: 30px;
}

.policy-notice p {
    margin: 0;
    font-family: 'Inter', sans-serif;
    color: var(--text);
    font-size: 1rem;
}

.returns-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.return-item-card {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,1);
    box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 24px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.return-item-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(95, 51, 168, 0.08);
}

.return-item-card.expired {
    opacity: 0.7;
    background: #f9f9f9;
}

.return-item-image {
    width: 80px;
    height: 80px;
    background: #f4f4f4;
    border-radius: 12px;
    padding: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.return-item-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.return-item-details {
    flex: 1;
}

.return-item-name {
    font-weight: 700;
    font-size: 1.15rem;
    color: var(--text);
    margin-bottom: 6px;
}

.return-item-meta {
    font-family: 'Inter', sans-serif;
    font-size: 0.9rem;
    color: var(--text-soft);
    margin-bottom: 4px;
}

.return-item-actions {
    flex-shrink: 0;
}

.return-btn {
    padding: 12px 24px;
    background: linear-gradient(135deg, var(--brand-primary), #7344be);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 4px 15px rgba(95, 51, 168, 0.3);
}

.return-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(95, 51, 168, 0.4);
}

.expired-notice {
    font-family: 'Inter', sans-serif;
    color: #e53935;
    font-weight: 600;
    font-size: 0.95rem;
    background: rgba(229, 57, 53, 0.1);
    padding: 10px 16px;
    border-radius: 10px;
}

/* Modal styles injected in head/body */
.mock-modal {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(255, 255, 255, 0.4);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}
.mock-modal-content {
    background: #fff;
    padding: 40px;
    border-radius: 24px;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.1);
    font-family: 'Outfit', sans-serif;
}
.mock-modal-content h3 {
    margin-top: 0;
    color: var(--brand-primary-dark);
    font-size: 1.5rem;
    font-weight: 700;
}
.mock-modal-content .form-group {
    margin-bottom: 20px;
}
.mock-modal-content label {
    display: block;
    font-family: 'Inter', sans-serif;
    font-weight: 500;
    font-size: 0.95rem;
    color: var(--text-soft);
    margin-bottom: 8px;
}
.mock-modal-content .form-select,
.mock-modal-content .form-textarea {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid rgba(0,0,0,0.1);
    border-radius: 12px;
    font-family: 'Inter', sans-serif;
    font-size: 1rem;
    outline: none;
}
.mock-modal-content .form-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
}
.mock-modal-content .form-textarea {
    resize: vertical;
}

.modal-submit-btn {
    padding: 14px 24px;
    background: linear-gradient(135deg, var(--brand-primary), #7344be);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(95, 51, 168, 0.3);
}

.modal-cancel-btn {
    padding: 14px 24px;
    background: #fff;
    border: 1px solid var(--line);
    color: var(--text);
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
}

@media (max-width: 900px) {
    .customer-layout { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
    .return-item-card { flex-direction: column; align-items: flex-start; }
}
</style>

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
                                    <button class="return-btn" onclick="openReturnModal('<?= $item['product'] ?>')">Return / Replace</button>
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
                <button type="submit" class="modal-submit-btn">Submit Request</button>
                <button type="button" class="modal-cancel-btn" onclick="closeReturnModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

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
