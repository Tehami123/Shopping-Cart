<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_customer();

$pageTitle = 'Returns & Replacements - Arts';
$basePath = '/Shopping%20Cart';

$userId = current_user_id();
$customerId = get_customer_id_for_user((int) $userId);
$profile = null;

if ($customerId === null) {
    redirect_to($basePath . '/auth/login.php');
}

$profile = get_customer_profile($customerId);
$returnMessage = '';
$returnType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_return'])) {
    $orderItemId = (int) ($_POST['order_item_id'] ?? 0);
    $returnTypeValue = in_array($_POST['return_type'] ?? '', ['return', 'replacement'], true) ? $_POST['return_type'] : '';
    $reason = trim((string) ($_POST['reason'] ?? ''));
    $description = trim((string) ($_POST['description'] ?? ''));

    if ($orderItemId > 0 && $returnTypeValue !== '' && $reason !== '') {
        if (get_return_request_for_order_item($orderItemId, $customerId)) {
            $returnMessage = 'A return has already been requested for this item.';
            $returnType = 'error';
        } elseif (submit_customer_return_request($orderItemId, $customerId, $returnTypeValue, $reason, $description)) {
            $returnMessage = 'Return request submitted successfully.';
            $returnType = 'success';
        } else {
            $returnMessage = 'This item is not eligible for return. Returns are only available for delivered items within 7 days.';
            $returnType = 'error';
        }
    } else {
        $returnMessage = 'Please provide all required information.';
        $returnType = 'error';
    }
}

$mockItems = get_customer_eligible_return_items($customerId);

$existingRequests = get_customer_return_requests($customerId);

// Presentational-only badge mapping for existing return requests
$returnStatusBadgeClass = [
    'requested' => 'ca-badge--warning',
    'approved'  => 'ca-badge--info',
    'rejected'  => 'ca-badge--danger',
    'completed' => 'ca-badge--success',
];

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
                    <a href="returns.php" class="active">Returns &amp; Replacements</a>
                    <a href="account.php">Account Details</a>
                    <a href="<?= $basePath ?>/auth/logout.php" class="logout-link">Logout</a>
                </nav>
            </aside>

            <!-- Main Content -->
            <div class="ca-content">
                <div class="ca-header">
                    <div>
                        <span class="ca-eyebrow">Support</span>
                        <h1 class="ca-title">Returns &amp; Replacements</h1>
                        <p class="ca-subtitle">Request a return or replacement for eligible items, and track requests you've already sent.</p>
                    </div>
                </div>

                <?php if ($returnMessage): ?>
                    <div class="ca-alert <?= $returnType === 'success' ? 'ca-alert-success' : 'ca-alert-error' ?>">
                        <?= htmlspecialchars($returnMessage, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <div class="ca-policy-notice">
                    <span>ℹ️</span>
                    <p><strong>Return Policy:</strong> You can request a return or replacement for eligible items within <strong>7 days</strong> of delivery.</p>
                </div>

                <h2 class="ca-section-title">Eligible for Return</h2>
                <div class="ca-returns-list">
                    <?php if (empty($mockItems)): ?>
                        <div class="ca-empty">
                            <div class="icon">✅</div>
                            <p><strong>No eligible items right now</strong></p>
                            <p>Items become eligible here within 7 days of delivery.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($mockItems as $item): ?>
                            <div class="ca-return-card">
                                <div class="ca-return-image">
                                    <img src="<?= htmlspecialchars($item['image_url'] ?: $basePath . '/assets/images/stationery.svg', ENT_QUOTES, 'UTF-8') ?>" alt="Product">
                                </div>
                                <div class="ca-return-details">
                                    <div class="ca-return-name"><?= htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8') ?></div>
                                    <div class="ca-return-meta">Order #<?= htmlspecialchars($item['order_number'], ENT_QUOTES, 'UTF-8') ?></div>
                                    <div class="ca-return-meta">Delivered: <?= date('d M Y', strtotime($item['delivery_date'])) ?></div>
                                </div>

                                <div class="ca-return-actions">
                                    <button class="return-btn" onclick="openReturnModal(<?= htmlspecialchars(json_encode($item['product_name']), ENT_QUOTES, 'UTF-8') ?>, <?= (int) $item['order_item_id'] ?>)">Return / Replace</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <?php if (!empty($existingRequests)): ?>
                    <h2 class="ca-section-title" style="margin-top:36px;">Your Requests</h2>
                    <div class="ca-returns-list">
                        <?php foreach ($existingRequests as $request): ?>
                            <?php $reqBadgeClass = $returnStatusBadgeClass[$request['status']] ?? 'ca-badge--neutral'; ?>
                            <div class="ca-return-card is-past">
                                <div class="ca-return-details">
                                    <div class="ca-return-name"><?= htmlspecialchars($request['product_name'], ENT_QUOTES, 'UTF-8') ?></div>
                                    <div class="ca-return-meta">Order #<?= htmlspecialchars($request['order_number'], ENT_QUOTES, 'UTF-8') ?></div>
                                    <div class="ca-return-meta"><?= htmlspecialchars(ucfirst((string) $request['return_type']), ENT_QUOTES, 'UTF-8') ?> · <?= date('d M Y', strtotime($request['request_date'])) ?></div>
                                </div>
                                <div class="ca-return-actions">
                                    <span class="ca-badge <?= $reqBadgeClass ?>"><?= htmlspecialchars(format_return_status_label((string) $request['status']), ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </div>
</main>

<div id="returnModal" class="mock-modal" style="display: none;">
    <div class="mock-modal-content">
        <h3>Request Return or Replacement</h3>
        <p>Product: <strong id="modalProductName"></strong></p>

        <form method="POST" action="returns.php">
            <input type="hidden" name="submit_return" value="1">
            <input type="hidden" id="orderItemId" name="order_item_id" value="0">
            <div class="form-group" style="margin-top: 20px;">
                <label for="returnType">Request Type</label>
                <select id="returnType" name="return_type" class="form-select" required>
                    <option value="return">Return</option>
                    <option value="replacement">Replacement</option>
                </select>
            </div>

            <div class="form-group">
                <label for="returnReason">Reason</label>
                <input type="text" id="returnReason" name="reason" class="form-input" placeholder="e.g., Defective, wrong item, etc." required>
            </div>

            <div class="form-group">
                <label for="returnComments">Additional Comments</label>
                <textarea id="returnComments" name="description" class="form-textarea" rows="3"></textarea>
            </div>

            <div class="mock-modal-actions">
                <button type="submit" class="modal-submit-btn">Submit Request</button>
                <button type="button" class="modal-cancel-btn" onclick="closeReturnModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openReturnModal(productName, orderItemId) {
    document.getElementById('modalProductName').textContent = productName;
    document.getElementById('orderItemId').value = orderItemId;
    document.getElementById('returnModal').style.display = 'flex';
}

function closeReturnModal() {
    document.getElementById('returnModal').style.display = 'none';
    document.getElementById('returnType').value = 'return';
    document.getElementById('returnReason').value = '';
    document.getElementById('returnComments').value = '';
}
</script>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>