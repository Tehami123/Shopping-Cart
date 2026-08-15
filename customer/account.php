<?php
$pageTitle = 'Account Details - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
?>

<main class="customer-page">
    <div class="container">
        
        <div class="customer-layout">
            
            <!-- Customer Navigation Sidebar -->
            <aside class="customer-sidebar">
                <div class="customer-profile-brief">
                    <div class="avatar">JD</div>
                    <div class="info">
                        <strong>Jane Doe</strong>
                        <span>jane@example.com</span>
                    </div>
                </div>
                <nav class="customer-nav">
                    <a href="index.php">Dashboard</a>
                    <a href="orders.php">My Orders</a>
                    <a href="returns.php">Returns & Replacements</a>
                    <a href="account.php" class="active">Account Details</a>
                    <a href="<?= $basePath ?>/auth/login.php" class="logout-link">Logout</a>
                </nav>
            </aside>
            
            <!-- Main Content -->
            <div class="customer-content">
                <div class="customer-header-actions">
                    <h1 class="customer-page-title">Account Details</h1>
                    <button type="button" class="secondary-button" id="editProfileBtn">Edit Profile</button>
                </div>
                
                <form action="#" method="POST" id="accountForm" class="customer-form">
                    
                    <section class="customer-form-section">
                        <h3>Personal Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">First Name</label>
                                <input type="text" id="firstName" name="firstName" class="form-input" value="Jane" disabled>
                            </div>
                            <div class="form-group">
                                <label for="lastName">Last Name</label>
                                <input type="text" id="lastName" name="lastName" class="form-input" value="Doe" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" class="form-input" value="jane@example.com" disabled>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" class="form-input" value="+1 (555) 123-4567" disabled>
                            </div>
                        </div>
                    </section>
                    
                    <section class="customer-form-section">
                        <h3>Address Information</h3>
                        <div class="form-group">
                            <label for="address">Street Address</label>
                            <textarea id="address" name="address" class="form-textarea" rows="2" disabled>123 Shopping Avenue, Suite 100</textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city" class="form-input" value="Metropolis" disabled>
                            </div>
                            <div class="form-group">
                                <label for="postalCode">Postal Code</label>
                                <input type="text" id="postalCode" name="postalCode" class="form-input" value="10001" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="country">Country</label>
                            <select id="country" name="country" class="form-select" disabled>
                                <option value="US" selected>United States</option>
                                <option value="UK">United Kingdom</option>
                                <option value="CA">Canada</option>
                                <option value="PK">Pakistan</option>
                            </select>
                        </div>
                    </section>
                    
                    <div class="form-actions" id="saveActions" style="display: none;">
                        <button type="submit" class="primary-button">Save Changes</button>
                        <button type="button" class="text-button" id="cancelEditBtn">Cancel</button>
                    </div>
                    
                </form>

                <hr class="section-divider">

                <!-- Password Change Section -->
                <section class="customer-form-section">
                    <h3>Change Password</h3>
                    <form action="#" method="POST" id="passwordForm" class="customer-form">
                        <div class="form-group">
                            <label for="currentPassword">Current Password</label>
                            <input type="password" id="currentPassword" name="currentPassword" class="form-input" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="newPassword">New Password</label>
                                <input type="password" id="newPassword" name="newPassword" class="form-input" required minlength="8">
                            </div>
                            <div class="form-group">
                                <label for="confirmNewPassword">Confirm New Password</label>
                                <input type="password" id="confirmNewPassword" name="confirmNewPassword" class="form-input" required minlength="8">
                            </div>
                        </div>
                        <button type="submit" class="secondary-button">Update Password</button>
                    </form>
                </section>
                
            </div>
        </div>
        
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editBtn = document.getElementById('editProfileBtn');
    const cancelBtn = document.getElementById('cancelEditBtn');
    const saveActions = document.getElementById('saveActions');
    const accountForm = document.getElementById('accountForm');
    const inputs = accountForm.querySelectorAll('input, select, textarea');
    
    // Toggle Edit Mode
    function toggleEdit(enabled) {
        inputs.forEach(input => {
            input.disabled = !enabled;
        });
        if (enabled) {
            editBtn.style.display = 'none';
            saveActions.style.display = 'flex';
        } else {
            editBtn.style.display = 'block';
            saveActions.style.display = 'none';
        }
    }
    
    editBtn.addEventListener('click', () => toggleEdit(true));
    cancelBtn.addEventListener('click', () => toggleEdit(false));
    
    // Mock Form Submission
    accountForm.addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Profile saved successfully! (Frontend Mock)');
        toggleEdit(false);
    });

    const passwordForm = document.getElementById('passwordForm');
    passwordForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const pwd = document.getElementById('newPassword').value;
        const confirm = document.getElementById('confirmNewPassword').value;
        if (pwd !== confirm) {
            alert('New passwords do not match.');
            return;
        }
        alert('Password updated successfully! (Frontend Mock)');
        passwordForm.reset();
    });
});
</script>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
