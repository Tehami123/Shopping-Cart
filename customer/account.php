<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_customer();

$pageTitle = 'Account Details - Arts';
$basePath = '/Shopping-Cart';
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


<main class="ca-page">
    <div class="container">

        <div class="ca-shell">

            <!-- Customer Navigation Sidebar -->
            <aside class="ca-sidebar">
                <div class="ca-profile">
                    <div class="ca-avatar"><?php if ($profile) { echo htmlspecialchars(strtoupper(substr($profile['first_name'], 0, 1) . substr($profile['last_name'], 0, 1)), ENT_QUOTES, 'UTF-8'); } else { echo 'U'; } ?></div>
                    <div class="ca-profile-info">
                        <strong><?php if ($profile) { echo htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name'], ENT_QUOTES, 'UTF-8'); } else { echo 'User'; } ?></strong>
                        <span><?php if ($profile) { echo htmlspecialchars($profile['email'], ENT_QUOTES, 'UTF-8'); } ?></span>
                    </div>
                </div>
                <nav class="ca-nav">
                    <a href="index.php">Dashboard</a>
                    <a href="orders.php">My Orders</a>
                    <a href="returns.php">Returns &amp; Replacements</a>
                    <a href="account.php" class="active">Account Details</a>
                    <a href="<?= $basePath ?>/auth/logout.php" class="logout-link">Logout</a>
                </nav>
            </aside>

            <!-- Main Content -->
            <div class="ca-content">
                <div class="ca-header">
                    <div>
                        <span class="ca-eyebrow">Account</span>
                        <h1 class="ca-title">Account Details</h1>
                        <p class="ca-subtitle">Manage your personal information, address, and password.</p>
                    </div>
                    <button type="button" class="secondary-button" id="editProfileBtn">Edit Profile</button>
                </div>

                <?php if ($message !== ''): ?>
                    <div class="ca-alert <?php
                        if ($messageType === 'success') { echo 'ca-alert-success'; }
                        elseif ($messageType === 'error') { echo 'ca-alert-error'; }
                        else { echo 'ca-alert-info'; }
                    ?>">
                        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <form action="account.php" method="POST" id="accountForm">
                    <input type="hidden" name="update_profile" value="1">

                    <section class="ca-panel">
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

                    <section class="ca-panel">
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

                    <div class="ca-form-actions" id="saveActions" style="display: none;">
                        <button type="submit" class="primary-button">Save Changes</button>
                        <button type="button" class="ca-btn-ghost" id="cancelEditBtn">Cancel</button>
                    </div>

                </form>

                <!-- Password Change Section -->
                <section class="ca-panel">
                    <h3>Change Password</h3>
                    <form action="account.php" method="POST" id="passwordForm">
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