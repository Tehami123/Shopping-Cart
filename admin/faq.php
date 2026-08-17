<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_admin();

$pageTitle = 'Manage FAQ - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';

$activePage = 'faq.php';
$adminNav = [
    'index.php' => 'Dashboard', 'products.php' => 'Products', 'inventory.php' => 'Inventory',
    'orders.php' => 'Orders', 'customers.php' => 'Customers', 'employees.php' => 'Employees',
    'payments.php' => 'Payments', 'returns.php' => 'Returns', 'feedback.php' => 'Feedback', 'faq.php' => 'FAQ'
];
?>
<main class="customer-page admin-page">
    <div class="container">
        <div class="customer-layout">
            <aside class="customer-sidebar">
                <div class="customer-profile-brief" style="background: var(--brand-primary-dark); color: white;">
                    <div class="info"><strong style="color:white;">Admin Portal</strong></div>
                </div>
                <nav class="customer-nav">
                    <?php foreach ($adminNav as $url => $label): ?>
                        <a href="<?= $url ?>" <?= $activePage === $url ? 'class="active"' : '' ?>><?= $label ?></a>
                    <?php endforeach; ?>
                    <a href="<?= $basePath ?>/auth/login.php" class="logout-link">Logout</a>
                </nav>
            </aside>
            <div class="customer-content">
                <div class="customer-header-actions">
                    <h1 class="customer-page-title">Manage FAQ</h1>
                    <button class="primary-button" onclick="document.getElementById('addFaqModal').style.display='flex'">Add FAQ</button>
                </div>
                
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th class="col-medium">Question</th>
                                <th class="col-wide">Answer</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="col-top">What is the return policy?</td>
                                <td class="col-top">You can return items within 7 days of delivery.</td>
                                <td class="col-top"><span class="status-badge status-delivered">Published</span></td>
                                <td class="col-top">
                                    <button class="text-button">Edit</button> |
                                    <button class="text-button danger">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<div id="addFaqModal" class="mock-modal" style="display:none;">
    <div class="mock-modal-content">
        <h3>Add FAQ</h3>
        <form onsubmit="event.preventDefault(); alert('FAQ Added!'); this.closest('.mock-modal').style.display='none';">
            <div class="form-group"><label>Question</label><input type="text" class="form-input"></div>
            <div class="form-group"><label>Answer</label><textarea class="form-textarea" rows="4"></textarea></div>
            <div class="form-group"><label>Status</label><select class="form-select"><option>Published</option><option>Draft</option></select></div>
            <div class="mock-modal-actions">
                <button type="submit" class="primary-button">Save FAQ</button>
                <button type="button" class="secondary-button" onclick="this.closest('.mock-modal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

