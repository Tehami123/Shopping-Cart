<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

$pageTitle = 'Feedback - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
require_once dirname(__DIR__) . '/includes/admin-shell.php';

$db = get_db_connection();
$activePage = 'feedback.php';
$adminNav = [
    'index.php' => 'Dashboard', 'products.php' => 'Products', 'inventory.php' => 'Inventory',
    'orders.php' => 'Orders', 'customers.php' => 'Customers', 'employees.php' => 'Employees',
    'payments.php' => 'Payments', 'returns.php' => 'Returns', 'feedback.php' => 'Feedback', 'faq.php' => 'FAQ'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_feedback'])) {
    $feedbackId = (int) ($_POST['feedback_id'] ?? 0);
    if ($feedbackId > 0) {
        $db->prepare('UPDATE feedback SET status = :status, reviewed_at = NOW(), reviewed_by = :reviewed_by WHERE feedback_id = :feedback_id')->execute([
            ':status' => 'reviewed',
            ':reviewed_by' => current_user_id(),
            ':feedback_id' => $feedbackId,
        ]);
    }
}

$feedback = get_all_feedback_for_admin();
?>
<main class="admin-app">
    <div class="admin-layout">
        <?php render_admin_sidebar($adminNav, $activePage, $basePath); ?>
        <section class="admin-main">
            <?php render_admin_page_header('Feedback', 'Read customer messages, keep context visible, and close the loop when reviewed.', 'Inbox workspace'); ?>

                <?php if (empty($feedback)): ?>
                    <div class="admin-empty-state"><span class="admin-empty-mark">F</span><h2>Your inbox is clear</h2><p>Customer feedback will appear here when visitors send a message through the contact form.</p><a href="<?= $basePath ?>/contact.php" class="secondary-button">Open contact page</a></div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Customer</th><th>Date</th><th class="col-wide">Feedback</th><th>Status</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($feedback as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['customer_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= date('d M Y', strtotime($item['created_at'])) ?></td>
                                    <td><?= htmlspecialchars($item['message'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><span class="status-badge <?= get_status_badge_class($item['status'], 'feedback') ?>"><?= ucfirst(htmlspecialchars($item['status'], ENT_QUOTES, 'UTF-8')) ?></span></td>
                                    <td>
                                        <?php if ($item['status'] !== 'reviewed'): ?>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="feedback_id" value="<?= (int) $item['feedback_id'] ?>">
                                                <input type="hidden" name="review_feedback" value="1">
                                                <button type="submit" class="text-button">Mark Reviewed</button>
                                            </form>
                                        <?php else: ?>
                                            <span>Reviewed</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
        </section>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

