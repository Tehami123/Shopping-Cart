<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$pageTitle = 'Create Account - Arts';
$basePath = '/Shopping-Cart';

if (is_logged_in()) {
    redirect_to($basePath . '/customer/index.php');
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $postalCode = trim($_POST['postalCode'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    if ($firstName === '' || $lastName === '' || $email === '' || $phone === '' || $address === '' || $city === '' || $postalCode === '' || $country === '' || $password === '' || $confirmPassword === '') {
        $errors[] = 'Please fill in all required fields.';
    }

    if (!validate_email($email)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (!validate_password($password)) {
        $errors[] = 'Password must be at least 8 characters long.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $db = get_db_connection();
        $check = $db->prepare('SELECT user_id FROM users WHERE email = :email LIMIT 1');
        $check->execute([':email' => $email]);
        if ($check->fetch()) {
            $errors[] = 'An account with this email already exists.';
        }
    }

    if (empty($errors)) {
        $db->beginTransaction();

        try {
            $stmt = $db->prepare('INSERT INTO users (email, password_hash, role, status) VALUES (:email, :password_hash, :role, :status)');
            $stmt->execute([
                ':email' => $email,
                ':password_hash' => password_hash($password, PASSWORD_BCRYPT),
                ':role' => 'customer',
                ':status' => 'active'
            ]);

            $userId = (int) $db->lastInsertId();

            $customerStmt = $db->prepare(
                'INSERT INTO customers (user_id, first_name, last_name, phone, address, city, postal_code, country) VALUES (:user_id, :first_name, :last_name, :phone, :address, :city, :postal_code, :country)'
            );
            $customerStmt->execute([
                ':user_id' => $userId,
                ':first_name' => $firstName,
                ':last_name' => $lastName,
                ':phone' => $phone,
                ':address' => $address,
                ':city' => $city,
                ':postal_code' => $postalCode,
                ':country' => $country,
            ]);

            $db->commit();
            $registered = true;
        } catch (Exception $e) {
            $db->rollBack();
            $errors[] = 'Registration failed. Please try again.';
        }
    }
}

require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap');

.auth-page { font-family: 'Outfit', sans-serif; background: #fdfcff; position: relative; overflow-x: hidden; padding: 80px 0; min-height: calc(100vh - 200px); display: flex; align-items: center; justify-content: center; }
.auth-page::before { content: ''; position: absolute; width: 600px; height: 600px; background: radial-gradient(circle, rgba(95,51,168,0.08) 0%, transparent 70%); top: 50%; left: 50%; transform: translate(-50%, -50%); border-radius: 50%; filter: blur(60px); z-index: 0; pointer-events: none; }
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
.auth-container { position: relative; z-index: 1; width: 100%; max-width: 600px; animation: fadeInUp 0.6s ease-out both; }
.auth-card { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(24px); border-radius: 24px; border: 1px solid rgba(255,255,255,1); box-shadow: 0 20px 50px rgba(0,0,0,0.05); padding: 40px; }
.auth-header { text-align: center; margin-bottom: 30px; }
.auth-title { font-size: 2.2rem; font-weight: 700; color: #1a1a1a; margin: 0 0 10px; letter-spacing: -0.02em; }
.auth-subtitle { font-family: 'Inter', sans-serif; font-size: 1.05rem; color: var(--text-soft); }
.auth-form-section-title { font-size: 1.25rem; font-weight: 600; color: #1a1a1a; margin: 30px 0 20px; padding-bottom: 10px; border-bottom: 1px solid rgba(0,0,0,0.05); }
.auth-form-section-title:first-child { margin-top: 0; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; font-family: 'Inter', sans-serif; font-weight: 500; font-size: 0.95rem; color: var(--text-soft); margin-bottom: 8px; }
.form-group .required { color: #ff4d4d; }
.form-input, .form-textarea, .form-select { width: 100%; padding: 14px 16px; border: 1px solid rgba(0,0,0,0.1); border-radius: 12px; font-family: 'Inter', sans-serif; font-size: 1rem; color: var(--text); background: #fff; transition: all 0.3s ease; outline: none; }
.form-textarea { resize: vertical; }
.form-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 16px center; }
.form-input:focus, .form-textarea:focus, .form-select:focus { border-color: rgba(95, 51, 168, 0.4); box-shadow: 0 0 0 4px rgba(95, 51, 168, 0.05); }
.remember-me { display: flex; align-items: center; cursor: pointer; }
.remember-me input { width: 18px; height: 18px; margin-right: 10px; accent-color: var(--brand-primary); cursor: pointer; }
.remember-me span { font-family: 'Inter', sans-serif; font-size: 0.95rem; color: var(--text-soft); }
.remember-me a { color: var(--brand-primary); text-decoration: none; }
.remember-me a:hover { text-decoration: underline; }
.auth-submit-btn { display: block; width: 100%; text-align: center; padding: 16px; margin-top: 30px; background: linear-gradient(135deg, var(--brand-primary), #7344be); color: #fff; border-radius: 12px; font-weight: 600; font-size: 1.1rem; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 10px 25px rgba(95, 51, 168, 0.3); border: none; cursor: pointer; }
.auth-submit-btn:hover { transform: translateY(-2px); box-shadow: 0 15px 35px rgba(95, 51, 168, 0.4); }
.auth-links { text-align: center; margin-top: 30px; font-family: 'Inter', sans-serif; font-size: 0.95rem; color: var(--text-soft); }
.auth-links p { margin-bottom: 12px; }
.auth-links a { color: var(--brand-primary); font-weight: 600; text-decoration: none; transition: color 0.2s; }
.auth-links a:hover { color: var(--brand-primary-dark); }
.alert-box { margin-bottom: 20px; padding: 12px 14px; border-radius: 10px; background: rgba(76,175,80,0.08); color: #1b5e20; font-family: 'Inter', sans-serif; font-weight: 500; }
.error-box { margin-bottom: 20px; padding: 12px 14px; border-radius: 10px; background: rgba(229,57,53,0.08); color: #8a1c1c; font-family: 'Inter', sans-serif; font-weight: 500; }
@media (max-width: 600px) { .form-row { grid-template-columns: 1fr; gap: 0; } .auth-card { padding: 30px 20px; } }
</style>

<main class="auth-page">
    <div class="container auth-container">
        <div class="auth-card auth-card-large">
            <div class="auth-header">
                <h1 class="auth-title">Create an Account</h1>
                <p class="auth-subtitle">Join Arts to enjoy a seamless shopping experience.</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="error-box"><?php foreach ($errors as $error): ?><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?><br><?php endforeach; ?></div>
            <?php elseif (!empty($registered)): ?>
                <div class="alert-box">Registration successful. You can now <a href="<?= $basePath ?>/auth/login.php">log in</a>.</div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form" id="registerForm">
                <h3 class="auth-form-section-title">Personal Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name <span class="required">*</span></label>
                        <input type="text" id="firstName" name="firstName" class="form-input" required value="<?= htmlspecialchars($_POST['firstName'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name <span class="required">*</span></label>
                        <input type="text" id="lastName" name="lastName" class="form-input" required value="<?= htmlspecialchars($_POST['lastName'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-input" required value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number <span class="required">*</span></label>
                        <input type="tel" id="phone" name="phone" class="form-input" required value="<?= htmlspecialchars($_POST['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                </div>
                
                <h3 class="auth-form-section-title">Address Information</h3>
                <div class="form-group">
                    <label for="address">Street Address <span class="required">*</span></label>
                    <textarea id="address" name="address" class="form-textarea" required rows="2"><?= htmlspecialchars($_POST['address'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City <span class="required">*</span></label>
                        <input type="text" id="city" name="city" class="form-input" required value="<?= htmlspecialchars($_POST['city'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="form-group">
                        <label for="postalCode">Postal Code <span class="required">*</span></label>
                        <input type="text" id="postalCode" name="postalCode" class="form-input" required value="<?= htmlspecialchars($_POST['postalCode'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="country">Country <span class="required">*</span></label>
                    <select id="country" name="country" class="form-select" required>
                        <option value="" disabled <?= empty($_POST['country'] ?? '') ? 'selected' : '' ?>>Select Country</option>
                        <option value="US" <?= (($_POST['country'] ?? '') === 'US') ? 'selected' : '' ?>>United States</option>
                        <option value="UK" <?= (($_POST['country'] ?? '') === 'UK') ? 'selected' : '' ?>>United Kingdom</option>
                        <option value="CA" <?= (($_POST['country'] ?? '') === 'CA') ? 'selected' : '' ?>>Canada</option>
                        <option value="PK" <?= (($_POST['country'] ?? '') === 'PK') ? 'selected' : '' ?>>Pakistan</option>
                    </select>
                </div>
                
                <h3 class="auth-form-section-title">Security</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password <span class="required">*</span></label>
                        <input type="password" id="password" name="password" class="form-input" required minlength="8">
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password <span class="required">*</span></label>
                        <input type="password" id="confirmPassword" name="confirmPassword" class="form-input" required minlength="8">
                    </div>
                </div>
                
                <div class="form-group auth-terms">
                    <label class="remember-me">
                        <input type="checkbox" name="terms" required>
                        <span>I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</span>
                    </label>
                </div>
                
                <button type="submit" class="auth-submit-btn">Create Account</button>
            </form>
            
            <div class="auth-links">
                <p>Already have an account? <a href="<?= $basePath ?>/auth/login.php">Log In</a></p>
                <p><a href="<?= $basePath ?>/products.php" class="text-link">← Continue Shopping</a></p>
            </div>
        </div>
    </div>
</main>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
