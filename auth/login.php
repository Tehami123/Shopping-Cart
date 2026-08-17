<?php
require_once dirname(__DIR__) . '/includes/auth.php';

$pageTitle = 'Log In - Arts';
$basePath = '/Shopping%20Cart';

if (is_logged_in()) {
    $role = current_user_role();
    if ($role === 'admin') {
        redirect_to($basePath . '/admin/index.php');
    }
    if ($role === 'employee') {
        redirect_to($basePath . '/employee/index.php');
    }
    redirect_to($basePath . '/customer/index.php');
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $message = 'Please enter both email and password.';
    } else {
        $user = login_user($email, $password);
        if ($user === null) {
            $message = 'Invalid email or password.';
        } else {
            $role = $user['role'];
            if ($role === 'admin') {
                redirect_to($basePath . '/admin/index.php');
            }
            if ($role === 'employee') {
                redirect_to($basePath . '/employee/index.php');
            }
            redirect_to($basePath . '/customer/index.php');
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
.auth-container { position: relative; z-index: 1; width: 100%; max-width: 480px; animation: fadeInUp 0.6s ease-out both; }
.auth-card { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(24px); border-radius: 24px; border: 1px solid rgba(255,255,255,1); box-shadow: 0 20px 50px rgba(0,0,0,0.05); padding: 40px; }
.auth-header { text-align: center; margin-bottom: 30px; }
.auth-title { font-size: 2.2rem; font-weight: 700; color: #1a1a1a; margin: 0 0 10px; letter-spacing: -0.02em; }
.auth-subtitle { font-family: 'Inter', sans-serif; font-size: 1.05rem; color: var(--text-soft); }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; font-family: 'Inter', sans-serif; font-weight: 500; font-size: 0.95rem; color: var(--text-soft); margin-bottom: 8px; }
.form-group .required { color: #ff4d4d; }
.form-input { width: 100%; padding: 14px 16px; border: 1px solid rgba(0,0,0,0.1); border-radius: 12px; font-family: 'Inter', sans-serif; font-size: 1rem; color: var(--text); background: #fff; transition: all 0.3s ease; outline: none; }
.form-input:focus { border-color: rgba(95, 51, 168, 0.4); box-shadow: 0 0 0 4px rgba(95, 51, 168, 0.05); }
.checkbox-group { display: flex; align-items: center; }
.remember-me { display: flex; align-items: center; cursor: pointer; }
.remember-me input { width: 18px; height: 18px; margin-right: 10px; accent-color: var(--brand-primary); cursor: pointer; }
.remember-me span { font-family: 'Inter', sans-serif; font-size: 0.95rem; color: var(--text-soft); }
.auth-submit-btn { display: block; width: 100%; text-align: center; padding: 16px; margin-top: 30px; background: linear-gradient(135deg, var(--brand-primary), #7344be); color: #fff; border-radius: 12px; font-weight: 600; font-size: 1.1rem; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 10px 25px rgba(95, 51, 168, 0.3); border: none; cursor: pointer; }
.auth-submit-btn:hover { transform: translateY(-2px); box-shadow: 0 15px 35px rgba(95, 51, 168, 0.4); }
.auth-links { text-align: center; margin-top: 30px; font-family: 'Inter', sans-serif; font-size: 0.95rem; color: var(--text-soft); }
.auth-links p { margin-bottom: 12px; }
.auth-links a { color: var(--brand-primary); font-weight: 600; text-decoration: none; transition: color 0.2s; }
.auth-links a:hover { color: var(--brand-primary-dark); }
.alert-box { margin-bottom: 20px; padding: 12px 14px; border-radius: 10px; background: rgba(229,57,53,0.08); color: #8a1c1c; font-family: 'Inter', sans-serif; font-weight: 500; }
@media (max-width: 480px) { .auth-card { padding: 30px 20px; } }
</style>

<main class="auth-page">
    <div class="container auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1 class="auth-title">Welcome Back</h1>
                <p class="auth-subtitle">Sign in to your Arts account to continue</p>
            </div>

            <?php if ($message !== ''): ?>
                <div class="alert-box"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form" id="loginForm">
                <div class="form-group">
                    <label for="email">Email Address <span class="required">*</span></label>
                    <input type="email" id="email" name="email" class="form-input" required placeholder="name@example.com" autofocus value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <input type="password" id="password" name="password" class="form-input" required placeholder="••••••••">
                </div>
                
                <div class="form-group checkbox-group">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" value="1">
                        <span>Remember me on this device</span>
                    </label>
                </div>
                
                <button type="submit" class="auth-submit-btn">Log In</button>
            </form>
            
            <div class="auth-links">
                <p>Don't have an account? <a href="<?= $basePath ?>/auth/register.php">Create an account</a></p>
                <p><a href="<?= $basePath ?>/products.php" class="text-link">← Continue Shopping</a></p>
            </div>
        </div>
    </div>
</main>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
