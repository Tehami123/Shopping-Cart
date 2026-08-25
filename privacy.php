<?php
$pageTitle = 'Privacy Policy - Arts';
$basePath = '/Shopping-Cart';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

/**
 * Inline SVG icon set for the Privacy Policy page.
 * Replaces emoji placeholders with icons that match the Arts brand
 * (single-color line icons, inherit currentColor, 24x24 viewBox).
 * Kept inline (rather than as separate asset files) so the page has
 * no extra network requests and the icons always match pv-icon's color.
 */
$pvIcons = [
    'intro' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M7 2.5h7.5L19 7v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 7 20V4a1.5 1.5 0 0 1 1.5-1.5Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
        <path d="M14.5 2.5V7H19" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
        <path d="M10 11.5h5M10 14.5h5M10 17.5h3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
    </svg>',

    'collect' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M4 6c0-1.38 3.58-2.5 8-2.5s8 1.12 8 2.5-3.58 2.5-8 2.5S4 7.38 4 6Z" stroke="currentColor" stroke-width="1.6"/>
        <path d="M4 6v12c0 1.38 3.58 2.5 8 2.5s8-1.12 8-2.5V6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
        <path d="M4 12c0 1.38 3.58 2.5 8 2.5s8-1.12 8-2.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
    </svg>',

    'use' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 8.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Z" stroke="currentColor" stroke-width="1.6"/>
        <path d="M19.4 13.5c.06-.49.1-.99.1-1.5s-.04-1.01-.1-1.5l2.03-1.58a.5.5 0 0 0 .12-.64l-1.92-3.32a.5.5 0 0 0-.6-.22l-2.39.96a7.4 7.4 0 0 0-1.3-.75l-.36-2.54a.5.5 0 0 0-.5-.41h-3.84a.5.5 0 0 0-.5.41l-.36 2.54c-.47.2-.9.45-1.3.75l-2.39-.96a.5.5 0 0 0-.6.22L3.55 8.28a.5.5 0 0 0 .12.64L5.7 10.5c-.06.49-.1.99-.1 1.5s.04 1.01.1 1.5l-2.03 1.58a.5.5 0 0 0-.12.64l1.92 3.32c.13.22.4.31.6.22l2.39-.96c.4.3.83.55 1.3.75l.36 2.54c.04.25.25.41.5.41h3.84c.25 0 .46-.16.5-.41l.36-2.54c.47-.2.9-.45 1.3-.75l2.39.96c.2.09.47 0 .6-.22l1.92-3.32a.5.5 0 0 0-.12-.64L19.4 13.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
    </svg>',

    'orders' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M3.5 7.5 12 3l8.5 4.5-8.5 4.5-8.5-4.5Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
        <path d="M3.5 7.5v9L12 21l8.5-4.5v-9" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
        <path d="M12 12v9" stroke="currentColor" stroke-width="1.6"/>
    </svg>',

    'protect' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 2.75 5 5.5v5.75c0 4.6 2.94 8.32 7 9.75 4.06-1.43 7-5.15 7-9.75V5.5L12 2.75Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
        <path d="M9.25 12.25 11.25 14.25 15 10.25" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>',

    'thirdparty' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M9.5 14.5 14.5 9.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
        <path d="M11 7.5 12.4 6.1a3.2 3.2 0 0 1 4.5 4.5L15.5 12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
        <path d="M13 16.5 11.6 17.9a3.2 3.2 0 1 1-4.5-4.5L8.5 12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
    </svg>',

    'responsibility' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="12" cy="8" r="3.25" stroke="currentColor" stroke-width="1.6"/>
        <path d="M5 20c0-3.5 3.13-6 7-6s7 2.5 7 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
    </svg>',

    'contact' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="3" y="5.5" width="18" height="13" rx="1.8" stroke="currentColor" stroke-width="1.6"/>
        <path d="M3.5 6.5 12 13l8.5-6.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>',
];
?>

<style>
.arts-privacy {
    --pv-primary: #6d28d9;
    --pv-primary-light: #8b5cf6;
    --pv-primary-bg: #f5f3ff;
    --pv-primary-border: #ede9fe;
    --pv-lavender-start: #efeafd;
    --pv-lavender-end: #d9cdf7;
    --pv-ink: #201a2b;
    --pv-text: #4b5563;
    background: #faf9fc;
}

.arts-privacy .container { max-width: 900px; margin: 0 auto; padding: 0 24px; }

/* ---------- Hero ---------- */
.arts-privacy .pv-hero {
    background: linear-gradient(120deg, var(--pv-lavender-start) 0%, var(--pv-lavender-end) 100%);
    padding: 64px 0;
    position: relative;
    overflow: hidden;
}
.arts-privacy .pv-hero .container {
    max-width: 1080px;
}
.arts-privacy .pv-hero-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 48px;
}
.arts-privacy .pv-hero-text { text-align: left; max-width: 480px; }
.arts-privacy .pv-hero .pv-eyebrow {
    display: inline-block;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.09em;
    text-transform: uppercase;
    color: var(--pv-primary);
    background: #ffffff;
    border: 1px solid var(--pv-primary-border);
    padding: 5px 14px;
    border-radius: 999px;
    margin-bottom: 18px;
}
.arts-privacy .pv-hero h1 {
    font-size: 2.35rem;
    line-height: 1.18;
    margin: 0 0 14px;
    font-weight: 700;
    letter-spacing: -0.01em;
    color: var(--pv-ink);
}
.arts-privacy .pv-hero p {
    margin: 0;
    max-width: 440px;
    color: #55506b;
    font-size: 1.02rem;
    line-height: 1.65;
}

/* Hero visual */
.arts-privacy .pv-hero-visual-wrapper {
    position: relative;
    flex-shrink: 0;
}
.arts-privacy .pv-hero-visual-wrapper::before,
.arts-privacy .pv-hero-visual-wrapper::after {
    content: "";
    position: absolute;
    border-radius: 50%;
    z-index: 0;
}
.arts-privacy .pv-hero-visual-wrapper::before {
    width: 110px; height: 110px;
    background: var(--pv-primary-bg);
    top: -22px; right: -22px;
}
.arts-privacy .pv-hero-visual-wrapper::after {
    width: 70px; height: 70px;
    background: #f2effc;
    bottom: -16px; left: -16px;
}
.arts-privacy .pv-hero-visual {
    flex-shrink: 0;
    width: 360px;
    height: 270px;
    border-radius: 20px;
    background: #ffffff;
    border: 1px solid #e7defb;
    box-shadow: 0 20px 45px -20px rgba(109,40,217,0.28);
    overflow: hidden;
    position: relative;
    z-index: 1;
}
.arts-privacy .pv-hero-visual img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* ---------- Quick nav ---------- */
.arts-privacy .pv-nav-wrap {
    background: #ffffff;
    border-bottom: 1px solid #ece7f7;
    position: sticky;
    top: 0;
    z-index: 5;
}
.arts-privacy .pv-links {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    justify-content: center;
    padding: 14px 24px;
    max-width: 900px;
    margin: 0 auto;
}
.arts-privacy .pv-links a {
    text-decoration: none;
    background: var(--pv-primary-bg);
    color: #5b21b6;
    font-size: 0.82rem;
    font-weight: 600;
    padding: 7px 14px;
    border-radius: 999px;
    border: 1px solid var(--pv-primary-border);
    transition: background 0.15s ease, color 0.15s ease, border-color 0.15s ease;
    white-space: nowrap;
}
.arts-privacy .pv-links a:hover,
.arts-privacy .pv-links a:focus-visible {
    background: var(--pv-primary);
    border-color: var(--pv-primary);
    color: #ffffff;
}

/* ---------- Body ---------- */
.arts-privacy .pv-body { padding: 40px 0 72px; }

.arts-privacy .pv-updated {
    text-align: center;
    color: #8a8397;
    font-size: 0.82rem;
    margin: 0 0 28px;
}

.arts-privacy .pv-section {
    background: #ffffff;
    border: 1px solid #ece7f7;
    border-radius: 16px;
    padding: 30px 32px;
    margin-bottom: 20px;
    box-shadow: 0 1px 2px rgba(76,29,149,0.04), 0 6px 20px -14px rgba(76,29,149,0.18);
    scroll-margin-top: 84px;
}

.arts-privacy .pv-section-title {
    display: flex;
    align-items: center;
    gap: 14px;
    margin: 0 0 16px;
}
.arts-privacy .pv-icon {
    width: 42px;
    height: 42px;
    border-radius: 11px;
    background: var(--pv-primary-bg);
    border: 1px solid var(--pv-primary-border);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: var(--pv-primary);
}
.arts-privacy .pv-icon svg { width: 22px; height: 22px; display: block; }

.arts-privacy .pv-section h2 {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--pv-ink);
    letter-spacing: -0.01em;
}

.arts-privacy .pv-section p {
    color: var(--pv-text);
    line-height: 1.75;
    font-size: 0.97rem;
    margin: 0 0 12px;
}
.arts-privacy .pv-section p:last-child { margin-bottom: 0; }

.arts-privacy .pv-section ul {
    margin: 0 0 12px;
    padding-left: 0;
    list-style: none;
    color: var(--pv-text);
    line-height: 1.7;
    font-size: 0.97rem;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.arts-privacy .pv-section ul li {
    position: relative;
    padding-left: 22px;
}
.arts-privacy .pv-section ul li::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0.55em;
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--pv-primary-light);
}
.arts-privacy .pv-section ul li:last-child { margin-bottom: 0; }

.arts-privacy .pv-section a {
    color: var(--pv-primary);
    font-weight: 600;
    text-decoration: none;
    border-bottom: 1px solid transparent;
}
.arts-privacy .pv-section a:hover,
.arts-privacy .pv-section a:focus-visible {
    border-bottom-color: currentColor;
}

/* Callout style for the "no third-party tools yet" and "your responsibility" notes */
.arts-privacy .pv-note {
    display: flex;
    gap: 10px;
    align-items: flex-start;
    background: var(--pv-primary-bg);
    border: 1px solid var(--pv-primary-border);
    border-radius: 10px;
    padding: 12px 14px;
    margin-top: 4px;
}
.arts-privacy .pv-note p {
    margin: 0;
    color: #5b21b6;
    font-size: 0.9rem;
    line-height: 1.6;
}

/* ---------- Responsive ---------- */
@media (max-width: 860px) {
    .arts-privacy .pv-hero-inner { flex-direction: column-reverse; text-align: center; gap: 32px; }
    .arts-privacy .pv-hero-text { max-width: 520px; text-align: center; }
    .arts-privacy .pv-hero p { margin: 0 auto; }
    .arts-privacy .pv-hero-visual { width: 320px; height: 240px; margin: 0 auto; }
}

@media (max-width: 720px) {
    .arts-privacy .pv-hero { padding: 44px 0 40px; }
    .arts-privacy .pv-hero h1 { font-size: 1.7rem; }
    .arts-privacy .pv-hero p { font-size: 0.95rem; }
    .arts-privacy .pv-section { padding: 22px 20px; }
}

@media (max-width: 480px) {
    .arts-privacy .container { padding: 0 16px; }
    .arts-privacy .pv-links { padding: 12px 16px; gap: 6px; }
    .arts-privacy .pv-links a { font-size: 0.76rem; padding: 6px 11px; }
    .arts-privacy .pv-section-title { gap: 10px; }
    .arts-privacy .pv-icon { width: 36px; height: 36px; border-radius: 9px; }
    .arts-privacy .pv-icon svg { width: 19px; height: 19px; }
    .arts-privacy .pv-section h2 { font-size: 1.06rem; }
}
</style>

<main class="arts-privacy">

    <section class="pv-hero">
        <div class="container">
            <div class="pv-hero-inner">
                <div class="pv-hero-text">
                    <span class="pv-eyebrow">Legal</span>
                    <h1>Privacy Policy</h1>
                    <p>A simple privacy notice for the Arts Online Shopping Cart student project.</p>
                </div>
                <div class="pv-hero-visual-wrapper">
                    <div class="pv-hero-visual" aria-hidden="true">
                        <img src="<?= $basePath ?>/assets/images/privacy-images/privacy-hero.jpg" alt="Privacy Shield and Lock decoration">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="pv-nav-wrap">
        <nav class="pv-links" aria-label="Privacy policy sections">
            <a href="#introduction">Introduction</a>
            <a href="#information-we-collect">Information We Collect</a>
            <a href="#how-we-use-information">How We Use It</a>
            <a href="#account-order">Account &amp; Orders</a>
            <a href="#data-protection">Data Protection</a>
            <a href="#third-party">Third-Party Services</a>
            <a href="#contact-section">Contact</a>
        </nav>
    </div>

    <div class="pv-body">
        <div class="container">

            <section id="introduction" class="pv-section">
                <div class="pv-section-title">
                    <span class="pv-icon"><?= $pvIcons['intro'] ?></span>
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
                    <span class="pv-icon"><?= $pvIcons['collect'] ?></span>
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
                    <span class="pv-icon"><?= $pvIcons['use'] ?></span>
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
                    <span class="pv-icon"><?= $pvIcons['orders'] ?></span>
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
                    <span class="pv-icon"><?= $pvIcons['protect'] ?></span>
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
                    <span class="pv-icon"><?= $pvIcons['thirdparty'] ?></span>
                    <h2>Third-Party Services</h2>
                </div>
                <div class="pv-note">
                    <p>
                        This student project does not currently integrate any third-party analytics, advertising,
                        or payment services. If such features are added in a future version, this policy will
                        be updated to reflect that.
                    </p>
                </div>
            </section>

            <section class="pv-section">
                <div class="pv-section-title">
                    <span class="pv-icon"><?= $pvIcons['responsibility'] ?></span>
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
                    <span class="pv-icon"><?= $pvIcons['contact'] ?></span>
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