<?php
$pageTitle = 'Privacy Policy - Arts';
$basePath = '/Shopping%20Cart';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<style>
.arts-privacy .container { max-width: 900px; margin: 0 auto; padding: 0 24px; }

.arts-privacy .pv-hero {
    background: linear-gradient(135deg, #6d28d9 0%, #8b5cf6 100%);
    color: #ffffff;
    padding: 60px 0 56px;
    text-align: center;
}
.arts-privacy .pv-hero h1 {
    font-size: 2.1rem;
    margin: 0 0 10px;
    font-weight: 700;
}
.arts-privacy .pv-hero p {
    margin: 0;
    color: rgba(255,255,255,0.92);
    font-size: 1rem;
}

.arts-privacy .pv-links {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    padding: 24px 24px 0;
    max-width: 900px;
    margin: 0 auto;
}
.arts-privacy .pv-links a {
    text-decoration: none;
    background: #f5f3ff;
    color: #5b21b6;
    font-size: 0.84rem;
    font-weight: 600;
    padding: 7px 15px;
    border-radius: 999px;
    border: 1px solid #ede9fe;
    transition: background 0.15s ease, color 0.15s ease;
}
.arts-privacy .pv-links a:hover {
    background: #6d28d9;
    color: #ffffff;
}

.arts-privacy .pv-body {
    padding: 32px 0 64px;
}
.arts-privacy .pv-section {
    background: #ffffff;
    border: 1px solid #ece7f7;
    border-left: 4px solid #8b5cf6;
    border-radius: 14px;
    padding: 26px 28px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(76,29,149,0.05);
    scroll-margin-top: 24px;
}
.arts-privacy .pv-section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0 0 12px;
}
.arts-privacy .pv-icon {
    width: 34px; height: 34px;
    border-radius: 9px;
    background: #f5f3ff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
}
.arts-privacy .pv-section h2 {
    margin: 0;
    font-size: 1.15rem;
    color: #201a2b;
}
.arts-privacy .pv-section p {
    color: #4b5563;
    line-height: 1.7;
    font-size: 0.96rem;
    margin: 0 0 10px;
}
.arts-privacy .pv-section p:last-child { margin-bottom: 0; }
.arts-privacy .pv-section ul {
    margin: 0 0 10px;
    padding-left: 20px;
    color: #4b5563;
    line-height: 1.7;
    font-size: 0.96rem;
}
.arts-privacy .pv-section a {
    color: #6d28d9;
    font-weight: 600;
    text-decoration: none;
}
.arts-privacy .pv-section a:hover { text-decoration: underline; }

@media (max-width: 600px) {
    .arts-privacy .pv-hero { padding: 48px 0 44px; }
    .arts-privacy .pv-hero h1 { font-size: 1.6rem; }
    .arts-privacy .pv-section { padding: 20px 20px; }
}
</style>

<main class="arts-privacy">

    <section class="pv-hero">
        <div class="container">
            <h1>Privacy Policy</h1>
            <p>A simple privacy notice for the Arts Online Shopping Cart student project.</p>
        </div>
    </section>

    <nav class="pv-links">
        <a href="#introduction">Introduction</a>
        <a href="#information-we-collect">Information We Collect</a>
        <a href="#how-we-use-information">How We Use It</a>
        <a href="#account-order">Account &amp; Orders</a>
        <a href="#data-protection">Data Protection</a>
        <a href="#third-party">Third-Party Services</a>
        <a href="#contact-section">Contact</a>
    </nav>

    <div class="pv-body">
        <div class="container">

            <section id="introduction" class="pv-section">
                <div class="pv-section-title">
                    <span class="pv-icon">📄</span>
                    <h2>Introduction</h2>
                </div>
                <p>
                    This privacy policy applies to the Arts Online Shopping Cart application developed as
                    an Aptech student eProject. It explains how customer information is handled within
                    this application.
                </p>
                <p>
                    This is a demonstration project, not a commercial production website. The policy
                    is kept simple for educational purposes.
                </p>
            </section>

            <section id="information-we-collect" class="pv-section">
                <div class="pv-section-title">
                    <span class="pv-icon">🗃️</span>
                    <h2>Information We Collect</h2>
                </div>
                <p>When you use this application, the following types of information may be collected:</p>
                <ul>
                    <li>Account details — name, email, phone, and login credentials when you register</li>
                    <li>Order information — products ordered, delivery address, payment method, and order status</li>
                    <li>Customer support data — feedback, return requests, and contact messages you submit</li>
                </ul>
            </section>

            <section id="how-we-use-information" class="pv-section">
                <div class="pv-section-title">
                    <span class="pv-icon">⚙️</span>
                    <h2>How We Use Information</h2>
                </div>
                <p>
                    Customer information is collected solely for account management, order processing,
                    delivery tracking, payment verification, returns handling, and customer support
                    within this shopping application.
                </p>
                <p>
                    Information is not used outside the intended shopping and order-management purposes
                    of this project. Data is not sold to third parties and is not used for external
                    marketing in this student application.
                </p>
            </section>

            <section id="account-order" class="pv-section">
                <div class="pv-section-title">
                    <span class="pv-icon">📦</span>
                    <h2>Account and Order Information</h2>
                </div>
                <p>
                    Your account and order details are used only to fulfill and manage your purchases —
                    such as processing orders, tracking deliveries, and handling returns — within this
                    application. This information is tied to your account and is not shared beyond what
                    is needed for these purposes.
                </p>
            </section>

            <section id="data-protection" class="pv-section">
                <div class="pv-section-title">
                    <span class="pv-icon">🔒</span>
                    <h2>Data Protection</h2>
                </div>
                <p>
                    Account and order information is stored in the application's database and is accessible
                    only through authorized roles — customers (their own data), employees (order workflows),
                    and administrators (system management).
                </p>
                <p>
                    When backend functionality is fully implemented, access will be protected by login
                    authentication and role-based permissions.
                </p>
            </section>

            <section id="third-party" class="pv-section">
                <div class="pv-section-title">
                    <span class="pv-icon">🔗</span>
                    <h2>Third-Party Services</h2>
                </div>
                <p>
                    This student project does not currently integrate any third-party analytics, advertising,
                    or payment services. If such features are added in a future version, this policy will
                    be updated to reflect that.
                </p>
            </section>

            <section class="pv-section">
                <div class="pv-section-title">
                    <span class="pv-icon">👤</span>
                    <h2>Your Responsibility</h2>
                </div>
                <p>
                    Please keep your login credentials secure. Do not share your account password with
                    others. If you believe your account has been accessed without permission, contact
                    us through the <a href="<?= $basePath ?>/contact.php">Contact Us</a> page.
                </p>
            </section>

            <section id="contact-section" class="pv-section">
                <div class="pv-section-title">
                    <span class="pv-icon">✉️</span>
                    <h2>Contact</h2>
                </div>
                <p>
                    If you have any questions about this privacy policy, please reach out through our
                    <a href="<?= $basePath ?>/contact.php">Contact Us</a> page.
                </p>
            </section>

        </div>
    </div>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>