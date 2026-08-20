<?php
$pageTitle = 'Contact Us - Arts';
$basePath = '/Shopping%20Cart';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (current_user_role() !== 'customer') {
        $error = 'You must be logged in as a customer to submit feedback.';
    } else {
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        if (empty($message)) {
            $error = 'Message is required.';
        } else {
            $db = get_db_connection();
            $stmt = $db->prepare('SELECT customer_id FROM customers WHERE user_id = :user_id');
            $stmt->execute([':user_id' => current_user_id()]);
            $customerId = $stmt->fetchColumn();
            
            if ($customerId) {
                $finalMessage = $subject ? "Subject: $subject\n\n$message" : $message;
                $stmt = $db->prepare('INSERT INTO feedback (customer_id, message) VALUES (:customer_id, :message)');
                $stmt->execute([':customer_id' => $customerId, ':message' => $finalMessage]);
                $success = true;
            } else {
                $error = 'Customer profile not found.';
            }
        }
    }
}
?>

<main class="contact-page">

    <section class="contact-hero">
        <div class="container">
            <h1>Contact Us</h1>
            <p>Reach out for order help, product questions, or general feedback.</p>
        </div>
    </section>

    <section class="contact-section">
        <div class="container">
            <div class="contact-layout">

                <div class="contact-card">
                    <h2>Shop Information</h2>
                    <div class="contact-info-item">
                        <span class="contact-info-icon">📍</span>
                        <div>
                            <strong>Address</strong>
                            <span>123 Arts Avenue, Stationery District, Metropolis 10001</span>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <span class="contact-info-icon">📞</span>
                        <div>
                            <strong>Phone</strong>
                            <span>+1 (555) 123-4567</span>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <span class="contact-info-icon">✉️</span>
                        <div>
                            <strong>Email</strong>
                            <span>support@arts-shop.example.com</span>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <span class="contact-info-icon">🕒</span>
                        <div>
                            <strong>Business Hours</strong>
                            <span>Monday – Friday: 9:00 AM – 6:00 PM</span>
                            <span>Saturday: 10:00 AM – 4:00 PM</span>
                            <span>Sunday: Closed</span>
                        </div>
                    </div>

                    <div class="contact-tip">
                        <span class="contact-tip-icon">💡</span>
                        <p>Already placed an order? You can check its status any time from your customer dashboard's order tracking page.</p>
                    </div>
                </div>

                <div class="contact-card">
                    <h2>Send a Message</h2>
                    <?php if ($success): ?>
                        <div class="contact-message success">Thank you! Your feedback has been submitted.</div>
                    <?php elseif ($error): ?>
                        <div class="contact-message error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                    <form method="post" class="contact-form">
                        <div class="form-group">
                            <label for="contact-name">Full Name</label>
                            <input type="text" id="contact-name" name="name" class="form-input" placeholder="Your name" required>
                        </div>
                        <div class="form-group">
                            <label for="contact-email">Email Address</label>
                            <input type="email" id="contact-email" name="email" class="form-input" placeholder="you@example.com" required>
                        </div>
                        <div class="form-group">
                            <label for="contact-subject">Subject</label>
                            <input type="text" id="contact-subject" name="subject" class="form-input" placeholder="How can we help?" required>
                        </div>
                        <div class="form-group">
                            <label for="contact-message">Message</label>
                            <textarea id="contact-message" name="message" class="form-textarea" rows="5" placeholder="Write your message here..." required></textarea>
                        </div>
                        <button type="submit" class="primary-button">Send Message</button>
                    </form>
                </div>

            </div>
        </div>
    </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>