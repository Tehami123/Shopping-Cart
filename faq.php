<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'FAQ - Arts';
$basePath = '/Shopping%20Cart';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$db = get_db_connection();
$faqs = get_all_published_faqs();
if (empty($faqs)) {
    $faqs = [
        ['question' => 'What is the return policy?', 'answer' => 'You can request a return or replacement within 7 days of delivery.'],
        ['question' => 'How long does delivery take?', 'answer' => 'Standard delivery typically takes 3–5 business days.'],
    ];
}
?>

<main class="faq-page">
    <div class="container">
        <header class="faq-header">
            <h1>Frequently Asked Questions</h1>
            <p>Find answers about orders, delivery, returns, payments, and your account.</p>
        </header>

        <div class="faq-list">
            <?php foreach ($faqs as $faq): ?>
                <details class="faq-item">
                    <summary><?= htmlspecialchars($faq['question'], ENT_QUOTES, 'UTF-8') ?></summary>
                    <div class="faq-answer"><?= htmlspecialchars($faq['answer'], ENT_QUOTES, 'UTF-8') ?></div>
                </details>
            <?php endforeach; ?>
        </div>

        <div class="faq-contact-banner">
            <p>Still have a question? We are happy to help.</p>
            <a href="<?= $basePath ?>/auth/login.php" class="primary-button">Sign In to Contact Support</a>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
