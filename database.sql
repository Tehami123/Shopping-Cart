-- ============================================================
-- Arts Online Shopping Cart
-- Database Foundation (Phase 3 — Step 1)
-- Database: arts_shop
-- Environment: XAMPP / MySQL / MariaDB
-- ============================================================

CREATE DATABASE IF NOT EXISTS arts_shop
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE arts_shop;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS returns;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS feedback;
DROP TABLE IF EXISTS faqs;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS employees;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

-- ------------------------------------------------------------
-- 1. users — all account types (customer, employee, admin)
-- ------------------------------------------------------------
CREATE TABLE users (
    user_id       INT UNSIGNED NOT NULL AUTO_INCREMENT,
    email         VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role          ENUM('customer', 'employee', 'admin') NOT NULL,
    status        ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id),
    UNIQUE KEY uq_users_email (email),
    KEY idx_users_role (role),
    KEY idx_users_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 2. customers — customer profile (1:1 with users)
-- ------------------------------------------------------------
CREATE TABLE customers (
    customer_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id     INT UNSIGNED NOT NULL,
    first_name  VARCHAR(100) NOT NULL,
    last_name   VARCHAR(100) NOT NULL,
    phone       VARCHAR(20) NOT NULL,
    address     TEXT NOT NULL,
    city        VARCHAR(50) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    country     VARCHAR(50) NOT NULL DEFAULT 'Pakistan',
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (customer_id),
    UNIQUE KEY uq_customers_user_id (user_id),
    KEY idx_customers_name (last_name, first_name),
    CONSTRAINT fk_customers_user
        FOREIGN KEY (user_id) REFERENCES users (user_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 3. employees — employee profile (1:1 with users)
-- ------------------------------------------------------------
CREATE TABLE employees (
    employee_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id     INT UNSIGNED NOT NULL,
    first_name  VARCHAR(100) NOT NULL,
    last_name   VARCHAR(100) NOT NULL,
    hire_date   DATE NOT NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (employee_id),
    UNIQUE KEY uq_employees_user_id (user_id),
    KEY idx_employees_name (last_name, first_name),
    CONSTRAINT fk_employees_user
        FOREIGN KEY (user_id) REFERENCES users (user_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 4. categories — product categories
-- ------------------------------------------------------------
CREATE TABLE categories (
    category_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name        VARCHAR(100) NOT NULL,
    description TEXT NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (category_id),
    UNIQUE KEY uq_categories_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 5. products — catalog and inventory (single stock source)
-- 7-digit ID = product_code (2) + product_number (5)
-- ------------------------------------------------------------
CREATE TABLE products (
    product_id      INT UNSIGNED NOT NULL AUTO_INCREMENT,
    product_code    CHAR(2) NOT NULL,
    product_number  CHAR(5) NOT NULL,
    full_product_id CHAR(7) AS (CONCAT(product_code, product_number)) STORED,
    category_id     INT UNSIGNED NOT NULL,
    name            VARCHAR(255) NOT NULL,
    description     TEXT NULL,
    price           DECIMAL(10, 2) NOT NULL,
    stock           INT NOT NULL DEFAULT 0,
    image_url       VARCHAR(255) NULL,
    status          ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (product_id),
    UNIQUE KEY uq_products_full_product_id (full_product_id),
    UNIQUE KEY uq_products_code_number (product_code, product_number),
    KEY idx_products_category_id (category_id),
    KEY idx_products_status (status),
    KEY idx_products_name (name),
    CONSTRAINT fk_products_category
        FOREIGN KEY (category_id) REFERENCES categories (category_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT chk_products_product_code
        CHECK (product_code REGEXP '^[0-9]{2}$'),
    CONSTRAINT chk_products_product_number
        CHECK (product_number REGEXP '^[0-9]{5}$'),
    CONSTRAINT chk_products_price
        CHECK (price >= 0),
    CONSTRAINT chk_products_stock
        CHECK (stock >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 6. orders — customer orders and payment/delivery workflow
-- 16-digit order_number = delivery digit (1) + 7-digit product ID + 8-digit sequence
-- Sequence must be generated in PHP using order_id (NOT COUNT(*)).
-- Example after insert with order_id = 42 and first product 0100001, standard delivery:
--   1 + 0100001 + 00000042 => 1010000100000042
-- ------------------------------------------------------------
CREATE TABLE orders (
    order_id        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    order_number    CHAR(16) NOT NULL,
    customer_id     INT UNSIGNED NOT NULL,
    order_date      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total_amount    DECIMAL(10, 2) NOT NULL,
    status          ENUM('pending', 'confirmed', 'dispatched', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending',
    payment_method  ENUM('credit_card', 'cheque', 'vpn', 'pay_on_delivery') NOT NULL,
    payment_status  ENUM('pending', 'cleared', 'failed') NOT NULL DEFAULT 'pending',
    delivery_type   ENUM('standard', 'express', 'pickup') NOT NULL,
    dispatch_date   DATETIME NULL,
    delivery_date   DATE NULL,
    notes           TEXT NULL,
    created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (order_id),
    UNIQUE KEY uq_orders_order_number (order_number),
    KEY idx_orders_customer_id (customer_id),
    KEY idx_orders_status (status),
    KEY idx_orders_payment_status (payment_status),
    KEY idx_orders_order_date (order_date),
    KEY idx_orders_delivery_date (delivery_date),
    CONSTRAINT fk_orders_customer
        FOREIGN KEY (customer_id) REFERENCES customers (customer_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT chk_orders_total_amount
        CHECK (total_amount >= 0),
    CONSTRAINT chk_orders_order_number
        CHECK (order_number REGEXP '^[0-9]{16}$')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 7. order_items — line items for each order
-- ------------------------------------------------------------
CREATE TABLE order_items (
    order_item_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    order_id      INT UNSIGNED NOT NULL,
    product_id    INT UNSIGNED NOT NULL,
    quantity      INT NOT NULL,
    unit_price    DECIMAL(10, 2) NOT NULL,
    subtotal      DECIMAL(10, 2) AS (quantity * unit_price) STORED,
    PRIMARY KEY (order_item_id),
    KEY idx_order_items_order_id (order_id),
    KEY idx_order_items_product_id (product_id),
    CONSTRAINT fk_order_items_order
        FOREIGN KEY (order_id) REFERENCES orders (order_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_order_items_product
        FOREIGN KEY (product_id) REFERENCES products (product_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT chk_order_items_quantity
        CHECK (quantity > 0),
    CONSTRAINT chk_order_items_unit_price
        CHECK (unit_price >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 8. returns — return/replacement requests (7-day rule enforced in PHP)
-- ------------------------------------------------------------
CREATE TABLE returns (
    return_id     INT UNSIGNED NOT NULL AUTO_INCREMENT,
    order_id      INT UNSIGNED NOT NULL,
    order_item_id INT UNSIGNED NOT NULL,
    customer_id   INT UNSIGNED NOT NULL,
    return_type   ENUM('return', 'replacement') NOT NULL,
    reason        VARCHAR(255) NOT NULL,
    description   TEXT NULL,
    request_date  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status        ENUM('requested', 'approved', 'rejected', 'completed') NOT NULL DEFAULT 'requested',
    approved_by   INT UNSIGNED NULL,
    approval_date DATETIME NULL,
    notes         TEXT NULL,
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (return_id),
    KEY idx_returns_order_id (order_id),
    KEY idx_returns_order_item_id (order_item_id),
    KEY idx_returns_customer_id (customer_id),
    KEY idx_returns_status (status),
    CONSTRAINT fk_returns_order
        FOREIGN KEY (order_id) REFERENCES orders (order_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_returns_order_item
        FOREIGN KEY (order_item_id) REFERENCES order_items (order_item_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_returns_customer
        FOREIGN KEY (customer_id) REFERENCES customers (customer_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_returns_approved_by
        FOREIGN KEY (approved_by) REFERENCES users (user_id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 9. feedback — customer feedback submissions
-- ------------------------------------------------------------
CREATE TABLE feedback (
    feedback_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    customer_id INT UNSIGNED NOT NULL,
    message     TEXT NOT NULL,
    status      ENUM('new', 'reviewed') NOT NULL DEFAULT 'new',
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reviewed_at DATETIME NULL,
    reviewed_by INT UNSIGNED NULL,
    PRIMARY KEY (feedback_id),
    KEY idx_feedback_customer_id (customer_id),
    KEY idx_feedback_status (status),
    KEY idx_feedback_created_at (created_at),
    CONSTRAINT fk_feedback_customer
        FOREIGN KEY (customer_id) REFERENCES customers (customer_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_feedback_reviewed_by
        FOREIGN KEY (reviewed_by) REFERENCES users (user_id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 10. faqs — FAQ content managed by admin
-- ------------------------------------------------------------
CREATE TABLE faqs (
    faq_id        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    question      VARCHAR(500) NOT NULL,
    answer        TEXT NOT NULL,
    status        ENUM('published', 'draft') NOT NULL DEFAULT 'draft',
    display_order INT NOT NULL DEFAULT 0,
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (faq_id),
    KEY idx_faqs_status (status),
    KEY idx_faqs_display_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SEED DATA — categories and products only
-- ============================================================

INSERT INTO categories (name, description) VALUES
('Stationery', 'Notebooks, pens, journals, and everyday office essentials.'),
('Gift Articles', 'Thoughtful decorative pieces and gift sets for every occasion.'),
('Greeting Cards', 'Cards for birthdays, celebrations, and special moments.'),
('Dolls & Toys', 'Soft toys, dolls, and playful favorites for all ages.'),
('Files & Folders', 'Folders and filing solutions to keep documents organized.'),
('Handbags', 'Everyday handbags that pair style with practicality.'),
('Wallets', 'Compact, durable wallets for everyday carry.'),
('Beauty', 'A simple selection of everyday beauty essentials.');

INSERT INTO products (
    product_code, product_number, category_id, name, description,
    price, stock, image_url, status
) VALUES
-- Stationery (category_id = 1, code 01)
('01', '00001', 1, 'Lavender Dream Journal',
 'Capture your thoughts in an elegantly designed hardcover journal with premium dotted paper.',
 24.00, 45, '/Shopping%20Cart/assets/images/stationery.svg', 'active'),
('01', '00002', 1, 'Classic Notebook',
 'A classic everyday notebook for notes, lists, and quick sketches.',
 16.00, 80, '/Shopping%20Cart/assets/images/stationery.svg', 'active'),
('01', '00003', 1, 'Premium Writing Set',
 'A curated pen set ideal for students and gift giving.',
 32.00, 12, '/Shopping%20Cart/assets/images/stationery.svg', 'active'),

-- Gift Articles (category_id = 2, code 02)
('02', '00001', 2, 'Ceramic Gift Box',
 'Beautifully crafted ceramic gift box with a secure lid.',
 28.00, 30, '/Shopping%20Cart/assets/images/gifts.svg', 'active'),
('02', '00002', 2, 'Decorative Gift Set',
 'A curated selection of decorative items, gift wrapped and ready to give.',
 45.00, 20, '/Shopping%20Cart/assets/images/gifts.svg', 'active'),

-- Greeting Cards (category_id = 3, code 03)
('03', '00001', 3, 'Botanical Watercolor Card',
 'Send a thoughtful message with a premium watercolor greeting card.',
 5.50, 100, '/Shopping%20Cart/assets/images/cards.svg', 'active'),
('03', '00002', 3, 'Birthday Greeting Card',
 'Celebrate birthdays in style with gold foil details and premium paper.',
 4.50, 75, '/Shopping%20Cart/assets/images/cards.svg', 'active'),

-- Dolls & Toys (category_id = 4, code 04)
('04', '00001', 4, 'Soft Plush Doll',
 'A cuddly companion for all ages with embroidered safety details.',
 22.00, 35, '/Shopping%20Cart/assets/images/toys.svg', 'active'),
('04', '00002', 4, 'Mini Teddy Bear',
 'A pocket-sized teddy bear perfect for small gifts and collections.',
 14.00, 0, '/Shopping%20Cart/assets/images/toys.svg', 'active'),

-- Files & Folders (category_id = 5, code 05)
('05', '00001', 5, 'Document File Set',
 'Durable document files for home, school, and office organization.',
 12.00, 60, '/Shopping%20Cart/assets/images/stationery.svg', 'active'),
('05', '00002', 5, 'Premium Office File',
 'Premium office file with reinforced edges and clear label slot.',
 18.50, 40, '/Shopping%20Cart/assets/images/stationery.svg', 'active'),

-- Handbags (category_id = 6, code 06)
('06', '00001', 6, 'Casual Handbag',
 'Everyday handbag with practical compartments and a comfortable strap.',
 48.00, 18, '/Shopping%20Cart/assets/images/gifts.svg', 'active'),

-- Wallets (category_id = 7, code 07)
('07', '00001', 7, 'Classic Leather Wallet',
 'Compact leather wallet with card slots and a secure coin pocket.',
 26.00, 25, '/Shopping%20Cart/assets/images/gifts.svg', 'active'),

-- Beauty (category_id = 8, code 08)
('08', '00001', 8, 'Everyday Lip Care Set',
 'Simple lip care essentials for daily use and gifting.',
 15.00, 50, '/Shopping%20Cart/assets/images/gifts.svg', 'active');
