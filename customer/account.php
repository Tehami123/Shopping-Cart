<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_customer();

$pageTitle = 'Account Details - Arts';
$basePath = '/Shopping%20Cart';
$userId = current_user_id();
$customerId = get_customer_id_for_user((int) $userId);

$message = '';
$messageType = 'info'; // 'success', 'error', 'info'
$profile = null;

if ($customerId !== null) {
    $profile = get_customer_profile($customerId);
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    if ($customerId === null || $profile === null) {
        $message = 'Profile not found.';
        $messageType = 'error';
    } else {
        $firstName = trim($_POST['firstName'] ?? '');
        $lastName = trim($_POST['lastName'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $postalCode = trim($_POST['postalCode'] ?? '');
        $country = trim($_POST['country'] ?? '');
        
        if ($firstName === '' || $lastName === '' || $phone === '' || $address === '' || $city === '' || $postalCode === '' || $country === '') {
            $message = 'All fields are required.';
            $messageType = 'error';
        } else {
            $updated = update_customer_profile($customerId, [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => $phone,
                'address' => $address,
                'city' => $city,
                'postal_code' => $postalCode,
                'country' => $country
            ]);
            
            if ($updated) {
                $message = 'Your profile has been updated successfully.';
                $messageType = 'success';
                // Refresh profile data
                $profile = get_customer_profile($customerId);
            } else {
                $message = 'Failed to update profile. Please try again.';
                $messageType = 'error';
            }
        }
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    if ($customerId === null) {
        $message = 'Authentication required.';
        $messageType = 'error';
    } else {
        $currentPassword = $_POST['currentPassword'] ?? '';
        $newPassword = $_POST['newPassword'] ?? '';
        $confirmPassword = $_POST['confirmNewPassword'] ?? '';
        
        if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
            $message = 'All password fields are required.';
            $messageType = 'error';
        } else if ($newPassword !== $confirmPassword) {
            $message = 'New passwords do not match.';
            $messageType = 'error';
        } else if (!validate_password($newPassword)) {
            $message = 'Password must be at least 8 characters long.';
            $messageType = 'error';
        } else {
            // Verify current password
            $currentUser = current_user();
            if ($currentUser === null) {
                $message = 'Authentication failed.';
                $messageType = 'error';
            } else {
                $db = get_db_connection();
                $stmt = $db->prepare('SELECT password_hash FROM users WHERE user_id = :user_id LIMIT 1');
                $stmt->execute([':user_id' => $userId]);
                $user = $stmt->fetch();
                
                if ($user && password_verify($currentPassword, $user['password_hash'])) {
                    if (update_customer_password($userId, $newPassword)) {
                        $message = 'Your password has been changed successfully.';
                        $messageType = 'success';
                    } else {
                        $message = 'Failed to update password. Please try again.';
                        $messageType = 'error';
                    }
                } else {
                    $message = 'Current password is incorrect.';
                    $messageType = 'error';
                }
            }
        }
    }
}

require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap');

/* Premium Aesthetics for Customer Dashboard */
.customer-page {
    font-family: 'Outfit', sans-serif;
    background: #fdfcff;
    position: relative;
    overflow-x: hidden;
    padding: 40px 0 80px;
    min-height: calc(100vh - 200px);
}

.customer-page::before {
    content: '';
    position: absolute;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(95,51,168,0.06) 0%, transparent 70%);
    top: -100px;
    left: -100px;
    border-radius: 50%;
    filter: blur(60px);
    z-index: 0;
    pointer-events: none;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.customer-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 40px;
    position: relative;
    z-index: 1;
}

/* Sidebar */
.customer-sidebar {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,1);
    box-shadow: 0 15px 35px rgba(0,0,0,0.03);
    padding: 30px 20px;
    align-self: start;
    animation: fadeInUp 0.6s ease-out both;
}

.customer-profile-brief {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.customer-profile-brief .avatar {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, var(--brand-primary), #7344be);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    font-weight: 700;
    box-shadow: 0 8px 16px rgba(95, 51, 168, 0.2);
}

.customer-profile-brief .info strong {
    display: block;
    font-size: 1.15rem;
    color: #1a1a1a;
    font-weight: 600;
}

.customer-profile-brief .info span {
    font-family: 'Inter', sans-serif;
    font-size: 0.9rem;
    color: var(--text-soft);
}

.customer-nav {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.customer-nav a {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    border-radius: 12px;
    color: var(--text-soft);
    font-weight: 500;
    font-family: 'Inter', sans-serif;
    text-decoration: none;
    transition: all 0.3s ease;
}

.customer-nav a:hover {
    background: rgba(95, 51, 168, 0.04);
    color: var(--brand-primary);
}

.customer-nav a.active {
    background: var(--brand-primary);
    color: #fff;
    box-shadow: 0 4px 12px rgba(95, 51, 168, 0.2);
}

.customer-nav a.logout-link {
    color: #e53935;
    margin-top: 20px;
}

.customer-nav a.logout-link:hover {
    background: rgba(229, 57, 53, 0.1);
}

/* Main Content */
.customer-content {
    animation: fadeInUp 0.6s ease-out 0.1s both;
}

.customer-header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.customer-page-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
    letter-spacing: -0.02em;
}

/* Account specific styles */
.customer-form-section {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,1);
    box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    padding: 32px;
    margin-bottom: 30px;
}

.customer-form-section h3 {
    margin: 0 0 24px;
    font-size: 1.4rem;
    font-weight: 700;
    color: #1a1a1a;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-family: 'Inter', sans-serif;
    font-weight: 500;
    font-size: 0.95rem;
    color: var(--text-soft);
    margin-bottom: 8px;
}

.form-input, .form-textarea, .form-select {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid rgba(0,0,0,0.1);
    border-radius: 12px;
    font-family: 'Inter', sans-serif;
    font-size: 1rem;
    color: var(--text);
    background: #fff;
    transition: all 0.3s ease;
    outline: none;
}

.form-input:disabled, .form-textarea:disabled, .form-select:disabled {
    background: #f9f9f9;
    color: #888;
    cursor: not-allowed;
}

.form-input:not(:disabled):focus, .form-textarea:not(:disabled):focus, .form-select:not(:disabled):focus {
    border-color: rgba(95, 51, 168, 0.4);
    box-shadow: 0 0 0 4px rgba(95, 51, 168, 0.05);
}

.form-textarea {
    resize: vertical;
}

.form-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
}

.form-actions {
    display: flex;
    gap: 16px;
    margin-top: 20px;
}

.primary-button {
    padding: 14px 28px;
    background: linear-gradient(135deg, var(--brand-primary), #7344be);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.05rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 8px 20px rgba(95, 51, 168, 0.3);
}

.primary-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(95, 51, 168, 0.4);
}

.secondary-button {
    padding: 14px 28px;
    background: #fff;
    border: 1px solid var(--brand-primary);
    color: var(--brand-primary);
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.05rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.secondary-button:hover {
    background: var(--brand-soft);
}

.text-button {
    background: none;
    border: none;
    color: var(--text-soft);
    font-weight: 600;
    font-size: 1.05rem;
    cursor: pointer;
    padding: 14px 20px;
}

.text-button:hover {
    color: var(--text);
}

.section-divider {
    border: 0;
    height: 1px;
    background: rgba(0,0,0,0.06);
    margin: 40px 0;
}

@media (max-width: 900px) {
    .customer-layout { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
    .form-row { grid-template-columns: 1fr; gap: 0; }
    .customer-header-actions { flex-direction: column; align-items: flex-start; gap: 16px; }
}
</style>

<main class="customer-page">
    <div class="container">
        
        <div class="customer-layout">
            
            <!-- Customer Navigation Sidebar -->
            <aside class="customer-sidebar">
                <div class="customer-profile-brief">
                    <div class="avatar"><?php if ($profile) { echo htmlspecialchars(strtoupper(substr($profile['first_name'], 0, 1) . substr($profile['last_name'], 0, 1)), ENT_QUOTES, 'UTF-8'); } else { echo 'U'; } ?></div>
                    <div class="info">
                        <strong><?php if ($profile) { echo htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name'], ENT_QUOTES, 'UTF-8'); } else { echo 'User'; } ?></strong>
                        <span><?php if ($profile) { echo htmlspecialchars($profile['email'], ENT_QUOTES, 'UTF-8'); } ?></span>
                    </div>
                </div>
                <nav class="customer-nav">
                    <a href="index.php">Dashboard</a>
                    <a href="orders.php">My Orders</a>
                    <a href="returns.php">Returns & Replacements</a>
                    <a href="account.php" class="active">Account Details</a>
                    <a href="<?= $basePath ?>/auth/logout.php" class="logout-link">Logout</a>
                </nav>
            </aside>
            
            <!-- Main Content -->
            <div class="customer-content">
                <div class="customer-header-actions">
                    <h1 class="customer-page-title">Account Details</h1>
                    <button type="button" class="secondary-button" id="editProfileBtn">Edit Profile</button>
                </div>
                
                <?php if ($message !== ''): ?>
                    <div class="alert-box" style="<?php if ($messageType === 'success') { echo 'background: rgba(76, 175, 80, 0.1); color: #2e7d32;'; } elseif ($messageType === 'error') { echo 'background: rgba(229, 57, 53, 0.1); color: #c62828;'; } ?>">
                        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>
                
                <form action="account.php" method="POST" id="accountForm" class="customer-form">
                    <input type="hidden" name="update_profile" value="1">
                    
                    <section class="customer-form-section">
                        <h3>Personal Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">First Name</label>
                                <input type="text" id="firstName" name="firstName" class="form-input" value="<?php if ($profile) { echo htmlspecialchars($profile['first_name'], ENT_QUOTES, 'UTF-8'); } ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="lastName">Last Name</label>
                                <input type="text" id="lastName" name="lastName" class="form-input" value="<?php if ($profile) { echo htmlspecialchars($profile['last_name'], ENT_QUOTES, 'UTF-8'); } ?>" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" class="form-input" value="<?php if ($profile) { echo htmlspecialchars($profile['email'], ENT_QUOTES, 'UTF-8'); } ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" class="form-input" value="<?php if ($profile) { echo htmlspecialchars($profile['phone'], ENT_QUOTES, 'UTF-8'); } ?>" disabled>
                            </div>
                        </div>
                    </section>
                    
                    <section class="customer-form-section">
                        <h3>Address Information</h3>
                        <div class="form-group">
                            <label for="address">Street Address</label>
                            <textarea id="address" name="address" class="form-textarea" rows="2" disabled><?php if ($profile) { echo htmlspecialchars($profile['address'], ENT_QUOTES, 'UTF-8'); } ?></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city" class="form-input" value="<?php if ($profile) { echo htmlspecialchars($profile['city'], ENT_QUOTES, 'UTF-8'); } ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="postalCode">Postal Code</label>
                                <input type="text" id="postalCode" name="postalCode" class="form-input" value="<?php if ($profile) { echo htmlspecialchars($profile['postal_code'], ENT_QUOTES, 'UTF-8'); } ?>" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="country">Country</label>
                            <select id="country" name="country" class="form-select" disabled>
                                <option value="US" <?php if ($profile && $profile['country'] === 'US') { echo 'selected'; } ?>>United States</option>
                                <option value="UK" <?php if ($profile && $profile['country'] === 'UK') { echo 'selected'; } ?>>United Kingdom</option>
                                <option value="CA" <?php if ($profile && $profile['country'] === 'CA') { echo 'selected'; } ?>>Canada</option>
                                <option value="PK" <?php if ($profile && $profile['country'] === 'PK') { echo 'selected'; } ?>>Pakistan</option>
                                <option value="Other" <?php if ($profile && !in_array($profile['country'], ['US', 'UK', 'CA', 'PK'], true)) { echo 'selected'; } ?>>Other</option>
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
                    <form action="account.php" method="POST" id="passwordForm" class="customer-form">
                        <input type="hidden" name="update_password" value="1">
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
    const inputs = accountForm.querySelectorAll('input:not([type="hidden"]), select, textarea');
    
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
    cancelBtn.addEventListener('click', () => {
        accountForm.reset();
        toggleEdit(false);
    });
});
</script>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
