<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_customer();

$pageTitle = 'Order Details - Arts';
$basePath = '/Shopping-Cart';
$userId = current_user_id();
$customerId = get_customer_id_for_user((int) $userId);

if ($customerId === null) {
    redirect_to($basePath . '/auth/login.php');
}

$orderId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($orderId <= 0) {
    redirect_to('orders.php');
}

$orderData = get_order_by_id_for_customer($orderId, $customerId);
if (!$orderData) {
    // Order not found or does not belong to customer
    redirect_to('orders.php');
}

$order = $orderData['order'];
$items = $orderData['items'];
$profile = get_customer_profile($customerId);

$payment = str_replace('_', ' ', $order['payment_status']);
$status = str_replace('_', ' ', $order['status']);
$paymentMethod = str_replace('_', ' ', $order['payment_method']);
$deliveryType = str_replace('_', ' ', $order['delivery_type']);

$badgeClassForStatus = [
    'pending'    => 'ca-badge--warning',
    'confirmed'  => 'ca-badge--info',
    'dispatched' => 'ca-badge--info',
    'delivered'  => 'ca-badge--success',
    'cancelled'  => 'ca-badge--danger',
];
$badgeClassForPayment = [
    'paid'        => 'ca-badge--success',
    'pending'     => 'ca-badge--warning',
    'refunded'    => 'ca-badge--neutral',
    'unpaid'      => 'ca-badge--warning',
    'failed'      => 'ca-badge--danger',
];

$paymentBadgeClass = $badgeClassForPayment[$order['payment_status']] ?? 'ca-badge--neutral';
$statusBadgeClass = $badgeClassForStatus[$order['status']] ?? 'ca-badge--neutral';
$canCancel = in_array($order['status'], ['pending', 'confirmed'], true);

$returnMessage = '';
$returnAlertType = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_return'])) {
    $orderItemId = (int) ($_POST['order_item_id'] ?? 0);
    $returnTypeValue = in_array($_POST['return_type'] ?? '', ['return', 'replacement'], true) ? $_POST['return_type'] : '';
    $reason = trim((string) ($_POST['reason'] ?? ''));
    $description = trim((string) ($_POST['description'] ?? ''));

    $itemBelongsToOrder = false;
    foreach ($items as $lineItem) {
        if ((int) $lineItem['order_item_id'] === $orderItemId) {
            $itemBelongsToOrder = true;
            break;
        }
    }

    if ($orderItemId > 0 && $itemBelongsToOrder && $returnTypeValue !== '' && $reason !== '') {
        if (get_return_request_for_order_item($orderItemId, $customerId)) {
            $returnMessage = 'A return has already been requested for this item.';
            $returnAlertType = 'error';
        } elseif (submit_customer_return_request($orderItemId, $customerId, $returnTypeValue, $reason, $description)) {
            $returnMessage = 'Return request submitted successfully.';
            $returnAlertType = 'success';
        } else {
            $returnMessage = 'This item is not eligible for return. Returns are only available for delivered items within 7 days.';
            $returnAlertType = 'error';
        }
    } else {
        $returnMessage = 'Please provide all required information.';
        $returnAlertType = 'error';
    }
}

$returnsByItem = [];
foreach (get_customer_return_requests($customerId) as $existingReturn) {
    if ((int) $existingReturn['order_id'] === $orderId) {
        $returnsByItem[(int) $existingReturn['order_item_id']] = $existingReturn;
    }
}
$orderReturnEligible = ($order['status'] === 'delivered' && is_within_return_window($order['delivery_date'] ?? null));

// Handle order cancellation
$cancelMessage = '';
$cancelType = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order_id'])) {
    $orderIdToCancel = (int) ($_POST['cancel_order_id'] ?? 0);

    if ($orderIdToCancel === $orderId) {
        if (cancel_order($orderIdToCancel, $customerId)) {
            $cancelMessage = 'Order cancelled successfully.';
            $cancelType = 'success';
            // Refresh order data
            $orderData = get_order_by_id_for_customer($orderId, $customerId);
            $order = $orderData['order'];
            $items = $orderData['items'];
            $canCancel = false;
            $status = 'cancelled';
            $statusBadgeClass = $badgeClassForStatus['cancelled'];
        } else {
            $cancelMessage = 'Unable to cancel this order. It may have already been dispatched.';
            $cancelType = 'error';
        }
    }
}

require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
?>

<style>
    /* Page-scoped enhancements only — no theme colors redefined, everything
       below reuses the existing CSS variables (--primary-color, --danger-color,
       --border-color, --text-light, --success-color etc.) already defined by the theme. */

    .ca-order-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }

    .ca-order-header-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .ca-icon-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        border-radius: 6px;
        padding: 0.6rem 1.1rem;
        border: 1px solid var(--border-color);
        cursor: pointer;
        transition: all 0.15s ease-in-out;
        line-height: 1;
    }

    .ca-icon-btn svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    .ca-icon-btn--outline {
        background: #fff;
        color: var(--text-color, #333);
    }

    .ca-icon-btn--outline:hover {
        background: #f8fafc;
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .ca-icon-btn--danger {
        background: var(--danger-color);
        color: #fff;
        border-color: var(--danger-color);
    }

    .ca-icon-btn--danger:hover {
        filter: brightness(0.92);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .ca-section-heading {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.15rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--text-color, #1a1a1a);
    }

    .ca-section-heading svg {
        width: 18px;
        height: 18px;
        color: var(--primary-color);
    }

    .ca-order-summary-card {
        background: #fff;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .ca-order-summary-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
        padding: 1rem 1.5rem;
        background: #f8fafc;
        border-bottom: 1px solid var(--border-color);
    }

    .ca-order-summary-topbar .ca-order-number {
        font-size: 0.9rem;
        color: var(--text-light);
    }

    .ca-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 1.5rem;
        padding: 1.5rem;
    }

    .ca-info-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .ca-info-icon {
        width: 38px;
        height: 38px;
        min-width: 38px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.04);
        color: var(--primary-color);
    }

    .ca-info-icon svg {
        width: 18px;
        height: 18px;
    }

    .ca-info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--text-light);
        font-weight: 600;
        margin-bottom: 0.35rem;
        display: block;
    }

    .ca-info-value {
        font-weight: 600;
        color: var(--text-color, #1a1a1a);
        line-height: 1.4;
    }

    .ca-info-sub {
        font-size: 0.8rem;
        color: var(--text-light);
        margin-top: 0.15rem;
        display: block;
    }

    .ca-products-card {
        background: #fff;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .ca-products-table {
        width: 100%;
        text-align: left;
        border-collapse: collapse;
    }

    .ca-products-table thead th {
        padding: 1rem;
        background: #f8fafc;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--text-light);
        font-weight: 700;
    }

    .ca-products-table tbody tr {
        border-bottom: 1px solid var(--border-color);
        transition: background 0.12s ease-in-out;
    }

    .ca-products-table tbody tr:last-child {
        border-bottom: none;
    }

    .ca-products-table tbody tr:hover {
        background: #fafbfc;
    }

    .ca-products-table td {
        padding: 1rem;
        vertical-align: middle;
    }

    .ca-product-id {
        font-size: 0.82rem;
        color: var(--text-light);
        margin-top: 0.2rem;
    }

    .ca-products-table tfoot td {
        padding: 1rem;
        background: #f8fafc;
        border-top: 1px solid var(--border-color);
        font-weight: 700;
    }

    .ca-order-total-value {
        font-size: 1.15rem;
        color: var(--primary-color);
    }

    .ca-cancel-row {
        display: flex;
        justify-content: flex-end;
        padding-bottom: 2rem;
   
    }

    @media (max-width: 640px) {
        .ca-order-header {
            flex-direction: column;
        }

        .ca-order-header-actions {
            width: 100%;
        }

        .ca-icon-btn {
            flex: 1;
            justify-content: center;
        }

        .ca-cancel-row {
            justify-content: stretch;
        }

        .ca-cancel-row form {
            width: 100%;
        }

        .ca-cancel-row .ca-icon-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

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
                    <a href="orders.php" class="active">My Orders</a>
                    <a href="returns.php">Returns &amp; Replacements</a>
                    <a href="account.php">Account Details</a>
                    <a href="<?= $basePath ?>/auth/logout.php" class="logout-link">Logout</a>
                </nav>
            </aside>

            <!-- Main Content -->
            <div class="ca-content">
                <div class="ca-order-header">
                    <div>
                        <span class="ca-eyebrow">Order Details</span>
                        <h1 class="ca-title">Order #<?= htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8') ?></h1>
                        <p class="ca-subtitle">Placed on <?= date('d M Y, h:i A', strtotime($order['order_date'])) ?></p>
                    </div>
                    <div class="ca-order-header-actions">
                        <a href="orders.php" class="ca-icon-btn ca-icon-btn--outline">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            Back to Orders
                        </a>
                    </div>
                </div>

                <?php if ($cancelMessage): ?>
                    <div class="ca-alert <?= $cancelType === 'success' ? 'ca-alert-success' : 'ca-alert-error' ?>">
                        <?= htmlspecialchars($cancelMessage, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>
                <?php if ($returnMessage): ?>
                    <div class="ca-alert <?= $returnAlertType === 'success' ? 'ca-alert-success' : 'ca-alert-error' ?>">
                        <?= htmlspecialchars($returnMessage, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <div class="ca-order-summary-card">
                    <div class="ca-order-summary-topbar">
                        <strong>Order Summary</strong>
                        <span class="ca-order-number">Order ID: #<?= (int) $order['order_id'] ?></span>
                    </div>

                    <div class="ca-info-grid">
                        <div class="ca-info-item">
                            <div class="ca-info-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            </div>
                            <div>
                                <span class="ca-info-label">Order Status</span>
                                <span class="ca-badge <?= $statusBadgeClass ?>"><?= htmlspecialchars(ucfirst($status), ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                        </div>

                        <div class="ca-info-item">
                            <div class="ca-info-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                            </div>
                            <div>
                                <span class="ca-info-label">Payment Method</span>
                                <span class="ca-info-value"><?= htmlspecialchars(ucwords($paymentMethod), ENT_QUOTES, 'UTF-8') ?></span><br>
                                <span class="ca-badge <?= $paymentBadgeClass ?>" style="margin-top: 0.4rem; display: inline-block;"><?= htmlspecialchars(ucfirst($payment), ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                        </div>

                        <div class="ca-info-item">
                            <div class="ca-info-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                            </div>
                            <div>
                                <span class="ca-info-label">Delivery</span>
                                <span class="ca-info-value"><?= htmlspecialchars(ucfirst($deliveryType), ENT_QUOTES, 'UTF-8') ?></span>
                                <?php if ($order['dispatch_date']): ?>
                                    <span class="ca-info-sub">Dispatched: <?= date('d M Y', strtotime($order['dispatch_date'])) ?></span>
                                <?php endif; ?>
                                <?php if ($order['delivery_date']): ?>
                                    <span class="ca-info-sub">Delivered: <?= date('d M Y', strtotime($order['delivery_date'])) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if ($order['notes']): ?>
                            <div class="ca-info-item">
                                <div class="ca-info-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                                </div>
                                <div>
                                    <span class="ca-info-label">Notes</span>
                                    <span class="ca-info-value" style="font-weight: 400;"><?= nl2br(htmlspecialchars($order['notes'], ENT_QUOTES, 'UTF-8')) ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <h2 class="ca-section-heading">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    Products Purchased
                </h2>
                <div class="ca-products-card">
                    <table class="ca-products-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th style="text-align: center;">Qty</th>
                                <th style="text-align: right;">Subtotal</th>
                                <th style="text-align: right;">Return</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <?php
                                $itemId = (int) $item['order_item_id'];
                                $itemReturn = $returnsByItem[$itemId] ?? null;
                                ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8') ?></strong>
                                        <div class="ca-product-id">ID: <?= htmlspecialchars($item['full_product_id'], ENT_QUOTES, 'UTF-8') ?></div>
                                    </td>
                                    <td>$<?= number_format((float)$item['unit_price'], 2) ?></td>
                                    <td style="text-align: center;"><?= (int)$item['quantity'] ?></td>
                                    <td style="text-align: right;">$<?= number_format((float)$item['subtotal'], 2) ?></td>
                                    <td style="text-align: right;">
                                        <?php if ($itemReturn): ?>
                                            <span class="ca-badge <?= $itemReturn['status'] === 'rejected' ? 'ca-badge--danger' : ($itemReturn['status'] === 'completed' ? 'ca-badge--success' : ($itemReturn['status'] === 'approved' ? 'ca-badge--info' : 'ca-badge--warning')) ?>">
                                                <?= htmlspecialchars(format_return_status_label((string) $itemReturn['status']), ENT_QUOTES, 'UTF-8') ?>
                                            </span>
                                        <?php elseif ($orderReturnEligible): ?>
                                            <button type="button" class="return-btn" onclick="openReturnModal(<?= htmlspecialchars(json_encode($item['product_name']), ENT_QUOTES, 'UTF-8') ?>, <?= $itemId ?>)">Return / Replace</button>
                                        <?php else: ?>
                                            <span class="ca-product-id">—</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" style="text-align: right;">Order Total</td>
                                <td style="text-align: right;" class="ca-order-total-value">$<?= number_format((float)$order['total_amount'], 2) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <?php if ($canCancel): ?>
                    <div class="ca-cancel-row">
                        <form method="POST">
                            <input type="hidden" name="cancel_order_id" value="<?= (int) $order['order_id'] ?>">
                            <button type="submit" style="background: red;" class="ca-icon-btn ca-icon-btn--danger cancel-order-btn" onclick="return confirm('Are you sure you want to cancel this order?');">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                Cancel Order
                            </button>
                        </form>
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

        <form method="POST">
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