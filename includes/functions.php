<?php
require_once __DIR__ . '/../config/database.php';

function ensure_db_connection(): PDO
{
    if (!isset($GLOBALS['conn']) || !($GLOBALS['conn'] instanceof PDO)) {
        require_once __DIR__ . '/../config/database.php';
    }

    return $GLOBALS['conn'];
}

function redirect_to(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function format_currency(float $amount): string
{
    return '$' . number_format($amount, 2, '.', ',');
}

function normalize_product_stock_label(int $stock): string
{
    if ($stock <= 0) {
        return 'Out of Stock';
    }

    if ($stock <= 5) {
        return 'Low Stock';
    }

    return 'In Stock';
}

function get_product_categories(): array
{
    $db = ensure_db_connection();
    $stmt = $db->query('SELECT category_id, name FROM categories ORDER BY name ASC');
    $rows = $stmt->fetchAll();

    $categories = [];
    foreach ($rows as $row) {
        $categories[] = [
            'category_id' => (int) $row['category_id'],
            'name' => $row['name'],
        ];
    }

    return $categories;
}

function get_admin_dashboard_stats(): array
{
    $db = ensure_db_connection();
    $stmt = $db->query(
        'SELECT
            (SELECT COUNT(*) FROM products) AS total_products,
            (SELECT COUNT(*) FROM products WHERE stock <= 5 AND status = "active") AS low_stock_products,
            (SELECT COUNT(*) FROM orders) AS total_orders,
            (SELECT COUNT(*) FROM orders WHERE status = "pending") AS pending_orders,
            (SELECT COUNT(*) FROM customers) AS total_customers,
            (SELECT COUNT(*) FROM users WHERE role = "employee" AND status = "active") AS total_employees'
    );
    $stats = $stmt->fetch();

    return [
        'total_products' => (int) ($stats['total_products'] ?? 0),
        'low_stock_products' => (int) ($stats['low_stock_products'] ?? 0),
        'total_orders' => (int) ($stats['total_orders'] ?? 0),
        'pending_orders' => (int) ($stats['pending_orders'] ?? 0),
        'total_customers' => (int) ($stats['total_customers'] ?? 0),
        'total_employees' => (int) ($stats['total_employees'] ?? 0),
    ];
}

function legacy_art_id_to_product_lookup(string $value): ?array
{
    $value = trim($value);
    if ($value === '') {
        return null;
    }

    $db = ensure_db_connection();

    if (preg_match('/^ART\d+$/i', $value)) {
        $number = (int) preg_replace('/[^0-9]/', '', $value);
        $candidateProductId = $number - 1000;
        if ($candidateProductId > 0) {
            $stmt = $db->prepare(
                'SELECT p.*, c.name AS category_name FROM products p INNER JOIN categories c ON c.category_id = p.category_id WHERE p.product_id = :product_id AND p.status = :status LIMIT 1'
            );
            $stmt->execute([':product_id' => $candidateProductId, ':status' => 'active']);
            $row = $stmt->fetch();
            if ($row) {
                return $row;
            }
        }
    }

    if (preg_match('/^\d{7}$/', $value)) {
        $stmt = $db->prepare(
            'SELECT p.*, c.name AS category_name FROM products p INNER JOIN categories c ON c.category_id = p.category_id WHERE p.full_product_id = :full_product_id AND p.status = :status LIMIT 1'
        );
        $stmt->execute([':full_product_id' => $value, ':status' => 'active']);
        $row = $stmt->fetch();
        if ($row) {
            return $row;
        }
    }

    if (preg_match('/^\d+$/', $value)) {
        $stmt = $db->prepare(
            'SELECT p.*, c.name AS category_name FROM products p INNER JOIN categories c ON c.category_id = p.category_id WHERE p.product_id = :product_id AND p.status = :status LIMIT 1'
        );
        $stmt->execute([':product_id' => (int) $value, ':status' => 'active']);
        return $stmt->fetch() ?: null;
    }

    return null;
}

function get_product_by_id($productId): ?array
{
    $row = legacy_art_id_to_product_lookup((string) $productId);
    if (!$row) {
        return null;
    }

    return [
        'product_id' => (int) $row['product_id'],
        'full_product_id' => $row['full_product_id'],
        'product_code' => $row['product_code'],
        'product_number' => $row['product_number'],
        'category_id' => (int) $row['category_id'],
        'category_name' => $row['category_name'],
        'name' => $row['name'],
        'description' => $row['description'],
        'price' => format_currency((float) $row['price']),
        'price_numeric' => (float) $row['price'],
        'stock' => (int) $row['stock'],
        'stock_count' => (int) $row['stock'],
        'stock_label' => normalize_product_stock_label((int) $row['stock']),
        'image_url' => $row['image_url'] ?: '/Shopping%20Cart/assets/images/stationery.svg',
        'status' => $row['status'],
    ];
}

function get_all_products(?string $category = null, ?string $keyword = null): array
{
    $db = ensure_db_connection();

    $sql = 'SELECT p.*, c.name AS category_name FROM products p INNER JOIN categories c ON c.category_id = p.category_id WHERE p.status = :status';
    $params = [':status' => 'active'];

    if ($category !== null && $category !== '' && strtolower($category) !== 'all') {
        $sql .= ' AND c.name = :category';
        $params[':category'] = trim($category);
    }

        if ($keyword !== null && trim($keyword) !== '') {
            $sql .= ' AND (p.name LIKE :name_term OR p.description LIKE :description_term OR c.name LIKE :category_term)';
            $keywordTerm = '%' . trim($keyword) . '%';
            $params[':name_term'] = $keywordTerm;
            $params[':description_term'] = $keywordTerm;
            $params[':category_term'] = $keywordTerm;
    }

    $sql .= ' ORDER BY p.product_id ASC';

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $products = [];
    foreach ($rows as $row) {
        $products[] = [
            'product_id' => (int) $row['product_id'],
            'full_product_id' => $row['full_product_id'],
            'id' => $row['full_product_id'],
            'category_id' => (int) $row['category_id'],
            'category' => $row['category_name'],
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => format_currency((float) $row['price']),
            'price_numeric' => (float) $row['price'],
            'stock' => normalize_product_stock_label((int) $row['stock']),
            'stock_count' => (int) $row['stock'],
            'stock_label' => normalize_product_stock_label((int) $row['stock']),
            'image' => $row['image_url'] ?: '/Shopping%20Cart/assets/images/stationery.svg',
            'badge' => ((int) $row['stock'] > 0 && ((int) $row['product_id'] % 3) === 1) ? 'New' : '',
            'rating' => min(5, max(3, 3 + ((int) $row['product_id'] % 3))),
            'reviews' => 30 + ((int) $row['product_id'] * 11) % 120,
            'oldPrice' => ((int) $row['product_id'] % 4 === 0) ? format_currency((float) $row['price'] * 1.2) : null,
        ];
    }

    return $products;
}

function search_products(?string $keyword = null, ?string $category = null): array
{
    return get_all_products($category, $keyword);
}

function validate_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validate_password(string $password): bool
{
    return strlen($password) >= 8;
}

function normalize_category_name(string $category): string
{
    $map = [
        'dolls' => 'Dolls & Toys',
        'files' => 'Files & Folders',
        'beauty' => 'Beauty',
        'beauty products' => 'Beauty',
        'gift articles' => 'Gift Articles',
        'greeting cards' => 'Greeting Cards',
        'stationery' => 'Stationery',
        'handbags' => 'Handbags',
        'wallets' => 'Wallets',
    ];

    $normalized = strtolower(trim($category));
    return $map[$normalized] ?? trim($category);
}

function format_product_id(string $code, string $number): string
{
    return strtoupper(trim($code)) . str_pad(trim($number), 5, '0', STR_PAD_LEFT);
}

function ensure_cart_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 3600,
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        session_start();
    }
}

function get_cart(): array
{
    ensure_cart_session();
    return $_SESSION['cart'] ?? [];
}

function save_cart(array $cart): void
{
    ensure_cart_session();
    $_SESSION['cart'] = $cart;
}

function normalize_cart_product_id($productId): ?string
{
    $raw = trim((string) $productId);
    if ($raw === '') {
        return null;
    }

    $lookup = legacy_art_id_to_product_lookup($raw);
    if ($lookup !== null) {
        return (string) $lookup['full_product_id'];
    }

    if (preg_match('/^\d{7}$/', $raw) === 1) {
        return $raw;
    }

    if (preg_match('/^\d+$/', $raw) === 1) {
        $numeric = (int) $raw;
        $product = legacy_art_id_to_product_lookup((string) $numeric);
        if ($product !== null) {
            return (string) $product['full_product_id'];
        }
    }

    return null;
}

function add_to_cart($productId, int $quantity = 1): array
{
    $normalizedId = normalize_cart_product_id($productId);
    if ($normalizedId === null) {
        return ['success' => false, 'message' => 'Product could not be found.'];
    }

    $product = get_product_by_id($normalizedId);
    if ($product === null) {
        return ['success' => false, 'message' => 'Product could not be found.'];
    }

    $availableStock = (int) ($product['stock'] ?? 0);
    $requestedQty = max(1, $quantity);
    if ($availableStock <= 0) {
        return ['success' => false, 'message' => 'This product is currently out of stock.'];
    }

    if ($requestedQty > $availableStock) {
        return ['success' => false, 'message' => 'Only ' . $availableStock . ' item(s) are available.'];
    }

    $cart = get_cart();
    $existingQty = (int) ($cart[$normalizedId]['quantity'] ?? 0);
    $newQty = $existingQty + $requestedQty;
    if ($newQty > $availableStock) {
        $newQty = $availableStock;
    }

    $cart[$normalizedId] = [
        'product_id' => $normalizedId,
        'quantity' => $newQty,
    ];

    save_cart($cart);

    return ['success' => true, 'message' => 'Product added to cart.', 'quantity' => $newQty];
}

function update_cart_quantity($productId, int $quantity): array
{
    $normalizedId = normalize_cart_product_id($productId);
    if ($normalizedId === null) {
        return ['success' => false, 'message' => 'Product could not be found.'];
    }

    $product = get_product_by_id($normalizedId);
    if ($product === null) {
        return ['success' => false, 'message' => 'Product could not be found.'];
    }

    $availableStock = (int) ($product['stock'] ?? 0);
    $newQty = max(1, $quantity);
    if ($newQty > $availableStock) {
        $newQty = $availableStock;
    }

    $cart = get_cart();
    if ($availableStock <= 0) {
        unset($cart[$normalizedId]);
        save_cart($cart);
        return ['success' => false, 'message' => 'This product is currently out of stock.'];
    }

    $cart[$normalizedId] = [
        'product_id' => $normalizedId,
        'quantity' => $newQty,
    ];

    save_cart($cart);

    return ['success' => true, 'message' => 'Cart updated.', 'quantity' => $newQty];
}

function remove_from_cart($productId): void
{
    $normalizedId = normalize_cart_product_id($productId);
    if ($normalizedId === null) {
        return;
    }

    $cart = get_cart();
    unset($cart[$normalizedId]);
    save_cart($cart);
}

function clear_cart(): void
{
    save_cart([]);
}

function get_cart_items_with_details(): array
{
    $items = [];
    foreach (get_cart() as $productId => $entry) {
        $normalizedId = normalize_cart_product_id($productId);
        if ($normalizedId === null) {
            continue;
        }

        $product = get_product_by_id($normalizedId);
        if ($product === null) {
            continue;
        }

        $quantity = max(1, (int) ($entry['quantity'] ?? 1));
        $stockLimit = (int) ($product['stock'] ?? 0);
        if ($stockLimit <= 0) {
            continue;
        }
        if ($quantity > $stockLimit) {
            $quantity = $stockLimit;
        }

        $items[] = [
            'id' => $normalizedId,
            'product_id' => (int) ($product['product_id'] ?? 0),
            'full_product_id' => $normalizedId,
            'name' => $product['name'],
            'price' => (float) ($product['price_numeric'] ?? 0),
            'quantity' => $quantity,
            'image' => $product['image_url'],
            'stock' => $stockLimit,
        ];
    }

    return $items;
}

function get_cart_totals(): array
{
    $items = get_cart_items_with_details();
    $subtotal = 0.0;
    foreach ($items as $item) {
        $subtotal += ((float) $item['price']) * (int) $item['quantity'];
    }

    $shipping = ($subtotal > 0 && $subtotal < 50) ? 5.0 : 0.0;
    $total = $subtotal + $shipping;

    return [
        'items' => $items,
        'subtotal' => $subtotal,
        'shipping' => $shipping,
        'total' => $total,
    ];
}

function generate_order_number(string $deliveryType, string $firstProductId, int $orderId): string
{
    $map = [
        'standard' => '1',
        'express' => '2',
        'pickup' => '3',
    ];

    $deliveryDigit = $map[$deliveryType] ?? '1';
    $normalizedProductId = normalize_cart_product_id($firstProductId) ?? $firstProductId;
    $orderNumber = sprintf('%s%s%08d', $deliveryDigit, $normalizedProductId, $orderId);

    return substr($orderNumber, 0, 16);
}

function get_customer_id_for_user(int $userId): ?int
{
    $db = get_db_connection();
    $stmt = $db->prepare('SELECT customer_id FROM customers WHERE user_id = :user_id LIMIT 1');
    $stmt->execute([':user_id' => $userId]);
    $row = $stmt->fetch();

    return $row ? (int) $row['customer_id'] : null;
}

function get_customer_order_history(int $customerId): array
{
    $db = get_db_connection();
    $stmt = $db->prepare(
        'SELECT order_id, order_number, order_date, total_amount, status, payment_method, payment_status, delivery_type FROM orders WHERE customer_id = :customer_id ORDER BY order_id DESC'
    );
    $stmt->execute([':customer_id' => $customerId]);

    return $stmt->fetchAll();
}

function get_status_badge_class(string $status, string $group = 'order'): string
{
    $status = strtolower(trim($status));

    if ($group === 'payment') {
        $map = [
            'pending' => 'payment-pending',
            'cleared' => 'payment-paid',
            'failed' => 'status-cancelled',
        ];
        return $map[$status] ?? 'payment-pending';
    }

    if ($group === 'return') {
        $map = [
            'requested' => 'payment-pending',
            'approved' => 'status-processing',
            'rejected' => 'status-cancelled',
            'completed' => 'status-delivered',
        ];
        return $map[$status] ?? 'payment-pending';
    }

    if ($group === 'feedback') {
        $map = [
            'new' => 'payment-pending',
            'reviewed' => 'status-delivered',
        ];
        return $map[$status] ?? 'payment-pending';
    }

    if ($group === 'faq') {
        $map = [
            'draft' => 'payment-pending',
            'published' => 'status-delivered',
        ];
        return $map[$status] ?? 'payment-pending';
    }

    $map = [
        'pending' => 'payment-pending',
        'confirmed' => 'status-processing',
        'processing' => 'status-processing',
        'dispatched' => 'status-processing',
        'delivered' => 'status-delivered',
        'cancelled' => 'status-cancelled',
    ];

    return $map[$status] ?? 'payment-pending';
}

function get_all_orders_for_admin(?string $status = null, ?string $deliveryType = null): array
{
    $db = get_db_connection();
    $sql = 'SELECT o.*, CONCAT(c.first_name, " ", c.last_name) AS customer_name
        FROM orders o
        INNER JOIN customers c ON c.customer_id = o.customer_id';
    $params = [];

    if ($status !== null && $status !== '' && strtolower($status) !== 'all') {
        $sql .= ' WHERE o.status = :status';
        $params[':status'] = strtolower(trim($status));
    }

    if ($deliveryType !== null && $deliveryType !== '' && strtolower($deliveryType) !== 'all') {
        if (empty($params)) {
            $sql .= ' WHERE';
        } else {
            $sql .= ' AND';
        }
        $sql .= ' o.delivery_type = :delivery_type';
        $params[':delivery_type'] = strtolower(trim($deliveryType));
    }

    $sql .= ' ORDER BY o.order_id DESC';

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function get_all_returns_for_admin(): array
{
    $db = get_db_connection();
    $sql = 'SELECT r.*, o.order_number, CONCAT(c.first_name, " ", c.last_name) AS customer_name,
            p.name AS product_name, oi.quantity, oi.unit_price
        FROM returns r
        INNER JOIN orders o ON o.order_id = r.order_id
        INNER JOIN customers c ON c.customer_id = r.customer_id
        INNER JOIN order_items oi ON oi.order_item_id = r.order_item_id
        INNER JOIN products p ON p.product_id = oi.product_id
        ORDER BY r.return_id DESC';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function get_all_feedback_for_admin(): array
{
    $db = get_db_connection();
    $stmt = $db->prepare(
        'SELECT f.*, CONCAT(c.first_name, " ", c.last_name) AS customer_name
        FROM feedback f
        INNER JOIN customers c ON c.customer_id = f.customer_id
        ORDER BY f.feedback_id DESC'
    );
    $stmt->execute();
    return $stmt->fetchAll();
}

function get_all_faqs_for_admin(?string $status = null): array
{
    $db = get_db_connection();
    $sql = 'SELECT * FROM faqs';
    $params = [];

    if ($status !== null && $status !== '' && strtolower($status) !== 'all') {
        $sql .= ' WHERE status = :status';
        $params[':status'] = strtolower(trim($status));
    }

    $sql .= ' ORDER BY display_order ASC, faq_id DESC';

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function get_all_published_faqs(): array
{
    $db = get_db_connection();
    $stmt = $db->prepare('SELECT * FROM faqs WHERE status = :status ORDER BY display_order ASC, faq_id DESC');
    $stmt->execute([':status' => 'published']);
    return $stmt->fetchAll();
}

function format_return_status_label(string $status): string
{
    $map = [
        'requested' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'completed' => 'Completed',
    ];

    $normalized = strtolower(trim($status));
    return $map[$normalized] ?? ucfirst($normalized);
}

function is_within_return_window(?string $deliveryDate): bool
{
    if ($deliveryDate === null || trim($deliveryDate) === '') {
        return false;
    }

    $delivered = strtotime(date('Y-m-d', strtotime($deliveryDate)));
    $today = strtotime(date('Y-m-d'));
    if ($delivered === false || $today === false) {
        return false;
    }

    $daysSinceDelivery = (int) floor(($today - $delivered) / 86400);
    return $daysSinceDelivery >= 0 && $daysSinceDelivery <= 7;
}

function get_customer_eligible_return_items(int $customerId): array
{
    $db = get_db_connection();
    $stmt = $db->prepare(
        'SELECT oi.order_item_id, o.order_id, o.order_number, o.delivery_date, p.name AS product_name, p.image_url,
                oi.quantity, oi.unit_price
        FROM orders o
        INNER JOIN order_items oi ON oi.order_id = o.order_id
        INNER JOIN products p ON p.product_id = oi.product_id
        LEFT JOIN returns r ON r.order_item_id = oi.order_item_id AND r.customer_id = :return_customer_id
        WHERE o.customer_id = :order_customer_id
          AND o.status = :status
          AND o.delivery_date IS NOT NULL
          AND DATEDIFF(CURDATE(), o.delivery_date) BETWEEN 0 AND 7
          AND r.return_id IS NULL
        ORDER BY o.delivery_date DESC'
    );
    $stmt->execute([
        ':return_customer_id' => $customerId,
        ':order_customer_id' => $customerId,
        ':status' => 'delivered',
    ]);
    return $stmt->fetchAll();
}

function get_return_request_for_order_item(int $orderItemId, int $customerId): ?array
{
    $db = get_db_connection();
    $stmt = $db->prepare(
        'SELECT r.* FROM returns r
        WHERE r.order_item_id = :order_item_id AND r.customer_id = :customer_id
        LIMIT 1'
    );
    $stmt->execute([
        ':order_item_id' => $orderItemId,
        ':customer_id' => $customerId,
    ]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function can_request_return_for_item(int $orderItemId, int $customerId): bool
{
    $db = get_db_connection();
    $stmt = $db->prepare(
        'SELECT o.status, o.delivery_date
        FROM order_items oi
        INNER JOIN orders o ON o.order_id = oi.order_id
        WHERE oi.order_item_id = :order_item_id AND o.customer_id = :customer_id
        LIMIT 1'
    );
    $stmt->execute([
        ':order_item_id' => $orderItemId,
        ':customer_id' => $customerId,
    ]);
    $row = $stmt->fetch();
    if (!$row || $row['status'] !== 'delivered' || !is_within_return_window($row['delivery_date'] ?? null)) {
        return false;
    }

    return get_return_request_for_order_item($orderItemId, $customerId) === null;
}

function submit_customer_return_request(
    int $orderItemId,
    int $customerId,
    string $returnType,
    string $reason,
    string $description = ''
): bool {
    $returnType = in_array($returnType, ['return', 'replacement'], true) ? $returnType : '';
    $reason = trim($reason);
    $description = trim($description);

    if ($orderItemId <= 0 || $customerId <= 0 || $returnType === '' || $reason === '') {
        return false;
    }

    if (!can_request_return_for_item($orderItemId, $customerId)) {
        return false;
    }

    $db = get_db_connection();
    $stmt = $db->prepare(
        'SELECT oi.order_id
        FROM order_items oi
        INNER JOIN orders o ON o.order_id = oi.order_id
        WHERE oi.order_item_id = :order_item_id AND o.customer_id = :customer_id
        LIMIT 1'
    );
    $stmt->execute([
        ':order_item_id' => $orderItemId,
        ':customer_id' => $customerId,
    ]);
    $row = $stmt->fetch();
    if (!$row) {
        return false;
    }

    try {
        $insert = $db->prepare(
            'INSERT INTO returns (order_id, order_item_id, customer_id, return_type, reason, description, status, request_date)
            VALUES (:order_id, :order_item_id, :customer_id, :return_type, :reason, :description, :status, CURRENT_TIMESTAMP)'
        );
        return $insert->execute([
            ':order_id' => (int) $row['order_id'],
            ':order_item_id' => $orderItemId,
            ':customer_id' => $customerId,
            ':return_type' => $returnType,
            ':reason' => substr($reason, 0, 255),
            ':description' => $description !== '' ? $description : null,
            ':status' => 'requested',
        ]);
    } catch (Exception $e) {
        return false;
    }
}

function get_customer_return_requests(int $customerId): array
{
    $db = get_db_connection();
    $stmt = $db->prepare(
        'SELECT r.*, o.order_number, p.name AS product_name
        FROM returns r
        INNER JOIN orders o ON o.order_id = r.order_id
        INNER JOIN order_items oi ON oi.order_item_id = r.order_item_id
        INNER JOIN products p ON p.product_id = oi.product_id
        WHERE r.customer_id = :customer_id
        ORDER BY r.request_date DESC'
    );
    $stmt->execute([':customer_id' => $customerId]);
    return $stmt->fetchAll();
}

function get_order_line_items_for_admin(int $orderId): array
{
    $db = get_db_connection();
    $stmt = $db->prepare(
        'SELECT oi.*, p.name AS product_name, p.full_product_id
        FROM order_items oi
        INNER JOIN products p ON p.product_id = oi.product_id
        WHERE oi.order_id = :order_id
        ORDER BY oi.order_item_id ASC'
    );
    $stmt->execute([':order_id' => $orderId]);
    return $stmt->fetchAll();
}

function get_order_by_id_for_admin(int $orderId): ?array
{
    $db = get_db_connection();
    $stmt = $db->prepare(
        'SELECT o.*, CONCAT(c.first_name, " ", c.last_name) AS customer_name
        FROM orders o
        INNER JOIN customers c ON c.customer_id = o.customer_id
        WHERE o.order_id = :order_id LIMIT 1'
    );
    $stmt->execute([':order_id' => $orderId]);
    $order = $stmt->fetch();
    return $order ?: null;
}

// ========== CUSTOMER PROFILE FUNCTIONS ==========

function get_customer_profile(int $customerId): ?array
{
    $db = get_db_connection();
    $stmt = $db->prepare(
        'SELECT c.*, u.email FROM customers c INNER JOIN users u ON u.user_id = c.user_id WHERE c.customer_id = :customer_id LIMIT 1'
    );
    $stmt->execute([':customer_id' => $customerId]);
    $profile = $stmt->fetch();
    return $profile ?: null;
}

function update_customer_profile(int $customerId, array $data): bool
{
    $db = get_db_connection();
    
    $updates = [];
    $params = [':customer_id' => $customerId];
    
    if (isset($data['first_name'])) {
        $updates[] = 'first_name = :first_name';
        $params[':first_name'] = trim($data['first_name']);
    }
    
    if (isset($data['last_name'])) {
        $updates[] = 'last_name = :last_name';
        $params[':last_name'] = trim($data['last_name']);
    }
    
    if (isset($data['phone'])) {
        $updates[] = 'phone = :phone';
        $params[':phone'] = trim($data['phone']);
    }
    
    if (isset($data['address'])) {
        $updates[] = 'address = :address';
        $params[':address'] = trim($data['address']);
    }
    
    if (isset($data['city'])) {
        $updates[] = 'city = :city';
        $params[':city'] = trim($data['city']);
    }
    
    if (isset($data['postal_code'])) {
        $updates[] = 'postal_code = :postal_code';
        $params[':postal_code'] = trim($data['postal_code']);
    }
    
    if (isset($data['country'])) {
        $updates[] = 'country = :country';
        $params[':country'] = trim($data['country']);
    }
    
    if (empty($updates)) {
        return false;
    }
    
    $sql = 'UPDATE customers SET ' . implode(', ', $updates) . ' WHERE customer_id = :customer_id';
    $stmt = $db->prepare($sql);
    
    try {
        return $stmt->execute($params);
    } catch (Exception $e) {
        return false;
    }
}

function update_customer_password(int $userId, string $newPassword): bool
{
    $db = get_db_connection();
    $stmt = $db->prepare('UPDATE users SET password_hash = :password_hash WHERE user_id = :user_id');
    
    try {
        return $stmt->execute([
            ':password_hash' => password_hash($newPassword, PASSWORD_BCRYPT),
            ':user_id' => $userId
        ]);
    } catch (Exception $e) {
        return false;
    }
}

// ========== ORDER FUNCTIONS ==========

function can_cancel_order(int $orderId, int $customerId): bool
{
    $db = get_db_connection();
    $stmt = $db->prepare(
        'SELECT status FROM orders WHERE order_id = :order_id AND customer_id = :customer_id LIMIT 1'
    );
    $stmt->execute([':order_id' => $orderId, ':customer_id' => $customerId]);
    $order = $stmt->fetch();
    
    if (!$order) {
        return false;
    }
    
    // Customers may cancel pending, confirmed, or processing orders they own.
    // Dispatched, delivered, and cancelled orders cannot be cancelled.
    return in_array($order['status'], ['pending', 'confirmed', 'processing'], true);
}

function cancel_order(int $orderId, int $customerId): bool
{
    $db = get_db_connection();
    
    // Verify ownership and check if cancellable
    if (!can_cancel_order($orderId, $customerId)) {
        return false;
    }
    
    try {
        $db->beginTransaction();
        
        // Update order status to cancelled (ownership already verified)
        $stmt = $db->prepare(
            'UPDATE orders SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE order_id = :order_id AND customer_id = :customer_id'
        );
        $stmt->execute([
            ':status' => 'cancelled',
            ':order_id' => $orderId,
            ':customer_id' => $customerId,
        ]);
        
        // Restore stock for all items in the order
        $stmt = $db->prepare(
            'SELECT product_id, quantity FROM order_items WHERE order_id = :order_id'
        );
        $stmt->execute([':order_id' => $orderId]);
        $items = $stmt->fetchAll();
        
        foreach ($items as $item) {
            $stmt = $db->prepare(
                'UPDATE products SET stock = stock + :quantity WHERE product_id = :product_id'
            );
            $stmt->execute([':quantity' => (int) $item['quantity'], ':product_id' => (int) $item['product_id']]);
        }
        
        $db->commit();
        return true;
    } catch (Exception $e) {
        $db->rollBack();
        return false;
    }
}

function get_order_by_id_for_customer(int $orderId, int $customerId): ?array
{
    $db = get_db_connection();
    $stmt = $db->prepare(
        'SELECT o.* FROM orders o WHERE o.order_id = :order_id AND o.customer_id = :customer_id LIMIT 1'
    );
    $stmt->execute([':order_id' => $orderId, ':customer_id' => $customerId]);
    $order = $stmt->fetch();
    
    if (!$order) {
        return null;
    }
    
    // Get line items
    $stmt = $db->prepare(
        'SELECT oi.order_item_id, oi.quantity, oi.unit_price, oi.subtotal, p.name AS product_name, p.full_product_id FROM order_items oi INNER JOIN products p ON p.product_id = oi.product_id WHERE oi.order_id = :order_id'
    );
    $stmt->execute([':order_id' => $orderId]);
    $items = $stmt->fetchAll();
    
    return [
        'order' => $order,
        'items' => $items
    ];
}
