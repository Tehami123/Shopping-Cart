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
        $sql .= ' AND (p.name LIKE :term OR p.description LIKE :term OR c.name LIKE :term)';
        $params[':term'] = '%' . trim($keyword) . '%';
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

function get_customer_eligible_return_items(int $customerId): array
{
    $db = get_db_connection();
    $stmt = $db->prepare(
        'SELECT oi.order_item_id, o.order_id, o.order_number, o.delivery_date, p.name AS product_name, p.image_url,
                oi.quantity, oi.unit_price
        FROM orders o
        INNER JOIN order_items oi ON oi.order_id = o.order_id
        INNER JOIN products p ON p.product_id = oi.product_id
        LEFT JOIN returns r ON r.order_item_id = oi.order_item_id AND r.customer_id = :customer_id
        WHERE o.customer_id = :customer_id
          AND o.status = :status
          AND o.delivery_date IS NOT NULL
          AND r.return_id IS NULL
        ORDER BY o.delivery_date DESC'
    );
    $stmt->execute([
        ':customer_id' => $customerId,
        ':status' => 'delivered',
    ]);
    return $stmt->fetchAll();
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
