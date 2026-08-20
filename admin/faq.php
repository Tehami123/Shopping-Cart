<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

$pageTitle = 'Manage FAQ - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';

$db = get_db_connection();
$activePage = 'faq.php';
$adminNav = [
    'index.php' => 'Dashboard', 'products.php' => 'Products', 'inventory.php' => 'Inventory',
    'orders.php' => 'Orders', 'customers.php' => 'Customers', 'employees.php' => 'Employees',
    'payments.php' => 'Payments', 'returns.php' => 'Returns', 'feedback.php' => 'Feedback', 'faq.php' => 'FAQ'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_faq'])) {
        $question = trim((string) ($_POST['question'] ?? ''));
        $answer = trim((string) ($_POST['answer'] ?? ''));
        $status = in_array($_POST['status'] ?? '', ['published', 'draft'], true) ? $_POST['status'] : 'draft';
        if ($question !== '' && $answer !== '') {
            $db->prepare('INSERT INTO faqs (question, answer, status, display_order) VALUES (:question, :answer, :status, :display_order)')->execute([
                ':question' => $question,
                ':answer' => $answer,
                ':status' => $status,
                ':display_order' => 0,
            ]);
        }
    }

    if (isset($_POST['delete_faq'])) {
        $faqId = (int) ($_POST['faq_id'] ?? 0);
        if ($faqId > 0) {
            $db->prepare('DELETE FROM faqs WHERE faq_id = :faq_id')->execute([':faq_id' => $faqId]);
        }
    }
}

$faqs = get_all_faqs_for_admin('all');
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
                            <?php foreach ($faqs as $faq): ?>
                                <tr>
                                    <td class="col-top"><?= htmlspecialchars($faq['question'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="col-top"><?= htmlspecialchars($faq['answer'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="col-top"><span class="status-badge <?= get_status_badge_class($faq['status'], 'faq') ?>"><?= ucfirst(htmlspecialchars($faq['status'], ENT_QUOTES, 'UTF-8')) ?></span></td>
                                    <td class="col-top">
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="faq_id" value="<?= (int) $faq['faq_id'] ?>">
                                            <input type="hidden" name="delete_faq" value="1">
                                            <button type="submit" class="text-button danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
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
        <form method="POST">
            <input type="hidden" name="save_faq" value="1">
            <div class="form-group"><label>Question</label><input type="text" name="question" class="form-input" required></div>
            <div class="form-group"><label>Answer</label><textarea name="answer" class="form-textarea" rows="4" required></textarea></div>
            <div class="form-group"><label>Status</label><select name="status" class="form-select"><option value="published">Published</option><option value="draft">Draft</option></select></div>
            <div class="mock-modal-actions">
                <button type="submit" class="primary-button">Save FAQ</button>
                <button type="button" class="secondary-button" onclick="this.closest('.mock-modal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

