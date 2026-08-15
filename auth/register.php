<?php
$pageTitle = 'Create Account - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
?>

<main class="auth-page">
    <div class="container auth-container">
        
        <div class="auth-card auth-card-large">
            <div class="auth-header">
                <h1 class="auth-title">Create an Account</h1>
                <p class="auth-subtitle">Join Arts to enjoy a seamless shopping experience.</p>
            </div>
            
            <!-- Placeholder for error/success messages -->
            <!-- <div class="auth-message success">Account created successfully!</div> -->
            
            <form action="#" method="POST" class="auth-form" id="registerForm">
                
                <h3 class="auth-form-section-title">Personal Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name <span class="required">*</span></label>
                        <input type="text" id="firstName" name="firstName" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name <span class="required">*</span></label>
                        <input type="text" id="lastName" name="lastName" class="form-input" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number <span class="required">*</span></label>
                        <input type="tel" id="phone" name="phone" class="form-input" required>
                    </div>
                </div>
                
                <h3 class="auth-form-section-title">Address Information</h3>
                <div class="form-group">
                    <label for="address">Street Address <span class="required">*</span></label>
                    <textarea id="address" name="address" class="form-textarea" required rows="2"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City <span class="required">*</span></label>
                        <input type="text" id="city" name="city" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="postalCode">Postal Code <span class="required">*</span></label>
                        <input type="text" id="postalCode" name="postalCode" class="form-input" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="country">Country <span class="required">*</span></label>
                    <select id="country" name="country" class="form-select" required>
                        <option value="" disabled selected>Select Country</option>
                        <option value="US">United States</option>
                        <option value="UK">United Kingdom</option>
                        <option value="CA">Canada</option>
                        <option value="PK">Pakistan</option>
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
                
                <button type="submit" class="primary-button auth-submit-btn">Create Account</button>
                
            </form>
            
            <div class="auth-links">
                <p>Already have an account? <a href="<?= $basePath ?>/auth/login.php">Log In</a></p>
                <p><a href="<?= $basePath ?>/products.php" class="text-link">← Continue Shopping</a></p>
            </div>
        </div>
        
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mock UI behavior for frontend
    const registerForm = document.getElementById('registerForm');
    const pwd = document.getElementById('password');
    const pwdConfirm = document.getElementById('confirmPassword');
    
    registerForm.addEventListener('submit', function(e) {
        if (pwd.value !== pwdConfirm.value) {
            e.preventDefault();
            alert("Passwords do not match. Please try again.");
            return;
        }
        
        e.preventDefault(); // Prevent actual submission for frontend phase
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.textContent;
        btn.textContent = 'Creating account...';
        btn.disabled = true;
        
        setTimeout(() => {
            alert("Registration Simulation! (Frontend Mock)");
            btn.textContent = originalText;
            btn.disabled = false;
        }, 800);
    });
});
</script>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
