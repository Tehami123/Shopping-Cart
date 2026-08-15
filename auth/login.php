<?php
$pageTitle = 'Log In - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
?>

<main class="auth-page">
    <div class="container auth-container">
        
        <div class="auth-card">
            <div class="auth-header">
                <h1 class="auth-title">Welcome Back</h1>
                <p class="auth-subtitle">Sign in to your Arts account to continue</p>
            </div>
            
            <!-- Placeholder for error/success messages -->
            <!-- <div class="auth-message error">Invalid email or password.</div> -->
            
            <form action="#" method="POST" class="auth-form" id="loginForm">
                
                <div class="form-group">
                    <label for="email">Email Address <span class="required">*</span></label>
                    <input type="email" id="email" name="email" class="form-input" required placeholder="name@example.com" autofocus>
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
                
                <button type="submit" class="primary-button auth-submit-btn">Log In</button>
                
            </form>
            
            <div class="auth-links">
                <p>Don't have an account? <a href="<?= $basePath ?>/auth/register.php">Create an account</a></p>
                <p><a href="<?= $basePath ?>/products.php" class="text-link">← Continue Shopping</a></p>
            </div>
        </div>
        
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mock UI behavior for frontend
    const loginForm = document.getElementById('loginForm');
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.textContent;
        btn.textContent = 'Logging in...';
        btn.disabled = true;
        
        setTimeout(() => {
            alert("Login Simulation! (Frontend Mock)");
            btn.textContent = originalText;
            btn.disabled = false;
            // window.location.href = '<?= $basePath ?>/index.php'; // Mock redirect
        }, 800);
    });
});
</script>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
