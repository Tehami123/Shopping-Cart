<?php
$pageTitle = 'FAQ - Arts';
$basePath = '/Shopping%20Cart';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$faqs = [
    [
        'question' => 'What is the return policy?',
        'answer' => 'You can request a return or replacement within 7 days of delivery. Items must be unused and in original packaging. Visit your account returns page to submit a request.'
    ],
    [
        'question' => 'How long does delivery take?',
        'answer' => 'Standard delivery typically takes 3–5 business days. Express delivery is available at checkout and usually arrives within 1–2 business days depending on your location.'
    ],
    [
        'question' => 'What payment methods do you accept?',
        'answer' => 'We accept Pay on Delivery (VPP), credit card, and cheque payments. Credit card and cheque orders are processed only after payment clearance before dispatch.'
    ],
    [
        'question' => 'Can I browse products without creating an account?',
        'answer' => 'Yes. You can browse the shop, view product details, and search without logging in. An account is required to complete checkout and track orders.'
    ],
    [
        'question' => 'How do I track my order?',
        'answer' => 'After placing an order, log in to your customer dashboard and open My Orders. You will see payment status, dispatch status, and delivery updates there.'
    ],
    [
        'question' => 'Can I cancel an order?',
        'answer' => 'Orders can be cancelled before dispatch. Once an order has been dispatched, cancellation is no longer available, but you may request a return within the 7-day window.'
    ],
    [
        'question' => 'How do I contact support?',
        'answer' => 'Use the feedback form in your customer account or email our support team. We aim to respond within 1–2 business days.'
    ],
    [
        'question' => 'What is your privacy policy?',
        'answer' => 'We collect only the information needed to process orders and provide customer support. Your data is not sold to third parties. Account details are stored securely and used solely for order and service communication.'
    ],
];
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
                    <summary><?= htmlspecialchars($faq['question']) ?></summary>
                    <div class="faq-answer"><?= htmlspecialchars($faq['answer']) ?></div>
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
