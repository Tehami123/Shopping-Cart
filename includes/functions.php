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
