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

<style>
.arts-contact .container { max-width: 1140px; margin: 0 auto; padding: 0 24px; }

.arts-contact .ct-hero {
    background: linear-gradient(135deg, #6d28d9 0%, #8b5cf6 100%);
    color: #ffffff;
    padding: 64px 0 76px;
    text-align: center;
}
.arts-contact .ct-hero h1 {
    font-size: 2.2rem;
    margin: 0 0 12px;
    font-weight: 700;
}
.arts-contact .ct-hero p {
    margin: 0;
    color: rgba(255,255,255,0.92);
    font-size: 1.02rem;
}

.arts-contact .ct-wrap {
    padding: 56px 0 72px;
}
.arts-contact .ct-grid {
    display: grid;
    grid-template-columns: 1fr 1.3fr;
    gap: 28px;
    align-items: start;
    margin-top: -70px;
}

.arts-contact .ct-card {
    background: #ffffff;
    border-radius: 18px;
    box-shadow: 0 12px 30px rgba(76,29,149,0.12);
    border: 1px solid #efe9fb;
}

.arts-contact .ct-info { padding: 32px 28px; }
.arts-contact .ct-info h2 {
    margin: 0 0 22px;
    font-size: 1.3rem;
    color: #201a2b;
}
.arts-contact .ct-info-item {
    display: flex;
    gap: 14px;
    margin-bottom: 22px;
}
.arts-contact .ct-info-item:last-child { margin-bottom: 0; }
.arts-contact .ct-info-icon {
    flex-shrink: 0;
    width: 40px; height: 40px;
    border-radius: 10px;
    background: #f5f3ff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
}
.arts-contact .ct-info-item strong {
    display: block;
    font-size: 0.92rem;
    color: #201a2b;
    margin-bottom: 3px;
}
.arts-contact .ct-info-item span {
    display: block;
    color: #6b7280;
    font-size: 0.9rem;
    line-height: 1.5;
}

.arts-contact .ct-form { padding: 32px 28px; }
.arts-contact .ct-form h2 {
    margin: 0 0 22px;
    font-size: 1.3rem;
    color: #201a2b;
}
.arts-contact .form-group { margin-bottom: 18px; }
.arts-contact .form-group label {
    display: block;
    font-size: 0.88rem;
    font-weight: 600;
    color: #372a4d;
    margin-bottom: 6px;
}
.arts-contact .form-input,
.arts-contact .form-textarea {
    width: 100%;
    box-sizing: border-box;
    padding: 11px 14px;
    border: 1px solid #e2dcf0;
    border-radius: 10px;
    font-size: 0.94rem;
    font-family: inherit;
    color: #201a2b;
    background: #faf9fb;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.arts-contact .form-input:focus,
.arts-contact .form-textarea:focus {
    outline: none;
    border-color: #8b5cf6;
    box-shadow: 0 0 0 3px rgba(139,92,246,0.18);
    background: #ffffff;
}
.arts-contact .form-textarea { resize: vertical; min-height: 110px; }

.arts-contact .primary-button {
    background: #6d28d9;
    color: #ffffff;
    border: none;
    padding: 12px 28px;
    border-radius: 10px;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.15s ease, transform 0.15s ease;
}
.arts-contact .primary-button:hover {
    background: #5b21b6;
    transform: translateY(-1px);
}
.arts-contact .contact-form-note {
    margin: 12px 0 0;
    font-size: 0.82rem;
    color: #9ca3af;
}

@media (max-width: 820px) {
    .arts-contact .ct-grid { grid-template-columns: 1fr; margin-top: -50px; }
}
@media (max-width: 480px) {
    .arts-contact .ct-hero { padding: 52px 0 96px; }
    .arts-contact .ct-hero h1 { font-size: 1.7rem; }
    .arts-contact .ct-info, .arts-contact .ct-form { padding: 26px 20px; }
}
</style>

<main class="arts-contact">

    <section class="ct-hero">
        <div class="container">
            <h1>Contact Us</h1>
            <p>Reach out for order help, product questions, or general feedback.</p>
        </div>
    </section>

    <section class="ct-wrap">
        <div class="container">
            <div class="ct-grid">
                <div class="ct-card ct-info">
                    <h2>Shop Information</h2>
                    <div class="ct-info-item">
                        <span class="ct-info-icon">📍</span>
                        <div>
                            <strong>Address</strong>
                            <span>123 Arts Avenue, Stationery District, Metropolis 10001</span>
                        </div>
                    </div>
                    <div class="ct-info-item">
                        <span class="ct-info-icon">📞</span>
                        <div>
                            <strong>Phone</strong>
                            <span>+1 (555) 123-4567</span>
                        </div>
                    </div>
                    <div class="ct-info-item">
                        <span class="ct-info-icon">✉️</span>
                        <div>
                            <strong>Email</strong>
                            <span>support@arts-shop.example.com</span>
                        </div>
                    </div>
                    <div class="ct-info-item">
                        <span class="ct-info-icon">🕒</span>
                        <div>
                            <strong>Business Hours</strong>
                            <span>Monday – Friday: 9:00 AM – 6:00 PM</span>
                            <span>Saturday: 10:00 AM – 4:00 PM</span>
                            <span>Sunday: Closed</span>
                        </div>
                    </div>
                </div>

                <div class="ct-card ct-form">
                    <h2>Send a Message</h2>
                    <?php if ($success): ?>
                        <div style="background: #c6f6d5; color: #22543d; padding: 12px; border-radius: 8px; margin-bottom: 16px;">Thank you! Your feedback has been submitted.</div>
                    <?php elseif ($error): ?>
                        <div style="background: #fed7d7; color: #9b2c2c; padding: 12px; border-radius: 8px; margin-bottom: 16px;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                    <form method="post">
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