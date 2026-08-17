# PROJECT STATUS

Project:
Arts Online Shopping Cart

Technology:
PHP + MySQL + Apache

Current Phase:
Phase 3 — Backend + Database (Step 2: Database Connection **COMPLETE**)

Overall Progress:
~100% frontend / ~30% total project

Last Updated:
2026-08-17

---

# COMPLETED

### Phase 1 Blueprint Freeze
Status: IMPLEMENTED
- Requirements, architecture, database plan, RBAC, and 10-day plan frozen
- Files: PROJECT_STATUS.md, TECHNICAL_ARCHITECTURE.md

### Phase 2 — Frontend/UI Implementation
Status: **COMPLETE (FROZEN — do not redesign)**

All public, customer, admin, employee, and informational pages are implemented with a consistent design system. No backend functionality is connected yet.

**Public pages:**
- index.php, products.php, product.php, search.php, cart.php, checkout.php
- faq.php, about.php, contact.php, privacy.php

**Auth pages:**
- auth/login.php, auth/register.php

**Customer pages:**
- customer/index.php, customer/orders.php, customer/account.php, customer/returns.php

**Admin pages:**
- admin/index.php, admin/products.php, admin/inventory.php, admin/orders.php
- admin/customers.php, admin/employees.php, admin/payments.php, admin/returns.php
- admin/feedback.php, admin/faq.php

**Employee pages:**
- employee/index.php, employee/orders.php, employee/dispatch.php, employee/delivery.php

**Shared layout/assets:**
- includes/header.php, includes/navbar.php, includes/footer.php, includes/product-card.php
- assets/css/style.css

### Phase 2 Final Task — Informational Pages
Status: IMPLEMENTED

**Pages created:**
- about.php — business introduction, product categories, mission/service
- contact.php — shop contact details, business hours, frontend-only contact form
- privacy.php — simple student-project privacy policy

**Files modified:**
- includes/footer.php — updated Company/Support footer links
- assets/css/style.css — added informational page layout classes

### Phase 3 — Backend Step 1: Database Foundation
Status: **IMPLEMENTED + TESTED**

**Files created:**
- database.sql — full schema, constraints, indexes, and seed data

**Database name:** `arts_shop`

**Tables created (10):**
1. `users` — all account types (customer, employee, admin)
2. `customers` — customer profile (1:1 with users)
3. `employees` — employee profile (1:1 with users)
4. `categories` — product categories
5. `products` — catalog with 7-digit product ID structure
6. `orders` — orders with payment/delivery workflow fields
7. `order_items` — line items per order
8. `returns` — return/replacement requests
9. `feedback` — customer feedback submissions
10. `faqs` — FAQ content for admin/public pages

**Relationships (foreign keys):**
- `customers.user_id` → `users.user_id` (CASCADE)
- `employees.user_id` → `users.user_id` (CASCADE)
- `products.category_id` → `categories.category_id` (RESTRICT)
- `orders.customer_id` → `customers.customer_id` (RESTRICT)
- `order_items.order_id` → `orders.order_id` (CASCADE)
- `order_items.product_id` → `products.product_id` (RESTRICT)
- `returns.order_id` → `orders.order_id` (RESTRICT)
- `returns.order_item_id` → `order_items.order_item_id` (RESTRICT)
- `returns.customer_id` → `customers.customer_id` (RESTRICT)
- `returns.approved_by` → `users.user_id` (SET NULL)
- `feedback.customer_id` → `customers.customer_id` (CASCADE)
- `feedback.reviewed_by` → `users.user_id` (SET NULL)

**Key constraints and indexes:**
- 7-digit product ID: `product_code` (2 digits) + `product_number` (5 digits) → generated `full_product_id` (UNIQUE)
- CHECK constraints on product code/number format, price ≥ 0, stock ≥ 0
- 16-digit `order_number` with CHECK `^[0-9]{16}$` (generated in PHP using `order_id`, not COUNT(*))
- Payment tracked in `orders.payment_method` / `orders.payment_status` (no external payment tables)
- Payment methods: credit_card, cheque, vpn (VPP), pay_on_delivery
- Order statuses: pending, confirmed, dispatched, delivered, cancelled
- Return statuses: requested, approved, rejected, completed
- FAQ statuses: published, draft
- Feedback statuses: new, reviewed
- Indexes on foreign keys, status fields, order dates, product names, and lookup columns

**Seed data added:**
- 8 categories: Stationery, Gift Articles, Greeting Cards, Dolls & Toys, Files & Folders, Handbags, Wallets, Beauty
- 14 products (1–3 per category) with realistic names/prices aligned to frontend mock catalog
- Product IDs use category-based 2-digit codes (01–08) + 5-digit sequence
- No user/order/feedback/faq seed rows (backend logic not implemented yet)

**Tests actually performed:**
- [x] MariaDB import via XAMPP: `mysql -u root -e "SOURCE .../database.sql"` — **passed**
- [x] Verified all 10 tables exist in `arts_shop`
- [x] Verified seed counts: 8 categories, 14 products
- [x] Verified generated `full_product_id` values (e.g. `0100001`, `0800001`)
- [x] Verified 12 foreign key constraints in `information_schema`
- [x] Verified CHECK constraint rejects invalid `product_code` — **passed**
- [x] Verified UNIQUE constraint rejects duplicate product code/number — **passed**

**Known issues (non-blocking):**
- Frontend category labels differ slightly from DB seed names (e.g. frontend "Dolls" vs DB "Dolls & Toys"; frontend "Beauty Products" vs DB "Beauty"). Resolve when connecting products to DB in Backend Phase 2+.
- Frontend mock product IDs use `ART1001` format; DB uses 7-digit numeric IDs per architecture. Mapping will happen in PHP backend.
- MySQL was not running initially; started via XAMPP `mysql_start.bat` for testing.
- Resolved: `config/database.php` now created (Backend Phase 2).

### Phase 3 — Backend Step 2: Database Connection
Status: **IMPLEMENTED + TESTED**

**Files created:**
- config/database.php — shared PDO connection to `arts_shop`

**Connection method:**
- PDO via `mysql:host=localhost;dbname=arts_shop;charset=utf8mb4`
- Credentials stored only in `config/database.php` (root / empty password)
- Options: `ERRMODE_EXCEPTION`, `FETCH_ASSOC` default, emulated prepares disabled
- Exposes shared `$conn` variable on include (matches TECHNICAL_ARCHITECTURE.md)
- Provides `get_db_connection(): PDO` helper returning the same instance

**Tests actually performed:**
- [x] PHP syntax check: `php -l config/database.php` — **passed**
- [x] PHP CLI connection test via `require config/database.php` — **passed**
- [x] Confirmed connected database name: `arts_shop`
- [x] Confirmed seed data readable: 8 categories, 14 products
- [x] Confirmed `get_db_connection()` returns same PDO instance — **passed**

**Known issues (non-blocking):**
- Session ini settings from architecture doc are not in database.php yet (deferred to authentication phase)
- No pages include database.php yet (intentional — no backend wiring until next phases)

---

# IN PROGRESS

None — database connection step complete.

---

# NOT STARTED

- Authentication (login, register, logout, sessions)
- RBAC middleware (auth.php, rbac.php, functions.php)
- Product listing/search connected to database
- Cart and checkout business logic
- Order creation and 16-digit order number generation (PHP)
- Customer account/order/return workflows
- Employee dispatch/delivery workflows
- Admin CRUD and payment verification workflows
- Feedback submission (backend)
- FAQ management (backend)
- Contact form backend / email
- Security hardening
- End-to-end browser testing with live data

---

# TESTED

[ ] Not tested
[~] Partially tested
[x] Tested and passing
[!] Tested but has known issue

- Requirements alignment review: [x]
- Architecture freeze review: [x]
- Frontend structural/code review: [~] (no browser test)
- Informational pages created: [~] (files verified; browser test pending)
- Database schema import (XAMPP MariaDB 10.4.32): [x]
- Database constraints verification: [x]
- PHP PDO connection test (XAMPP PHP 8.2.12): [x]

---

# KNOWN BUGS

No blocking bugs in database foundation. See non-blocking naming/format mismatches above.

---

# DATABASE STATUS

Status: **IMPLEMENTED + TESTED**

Database name: `arts_shop`

Tables created: users, customers, employees, categories, products, orders, order_items, returns, feedback, faqs

Seed data: 8 categories, 14 products

Import command:
```
C:\xampp\mysql\bin\mysql.exe -u root -e "SOURCE C:/xampp/htdocs/Shopping Cart/database.sql"
```

---

# FILE STRUCTURE STATUS

```
Shopping Cart/
├── database.sql              # NEW — schema + seed data
├── index.php, products.php, product.php, search.php
├── cart.php, checkout.php, faq.php
├── about.php, contact.php, privacy.php
├── auth/login.php, auth/register.php
├── customer/ (index, orders, account, returns)
├── admin/ (index, products, inventory, orders, customers, employees, payments, returns, feedback, faq)
├── employee/ (index, orders, dispatch, delivery)
├── includes/ (header, navbar, footer, product-card)
├── assets/css/style.css
└── config/
    └── database.php          # PDO connection to arts_shop
```

---

# CURRENT WORK

## Currently Working On
Nothing — Step 2 (database connection) complete.

## Last Completed Task
Created and tested `config/database.php` with PDO connection to `arts_shop`.

## Project Transition State
**FRONTEND:** COMPLETE (FROZEN)
**DATABASE:** IMPLEMENTED + TESTED
**BACKEND (PHP):** CONNECTION IMPLEMENTED + TESTED
**AUTHENTICATION:** NOT IMPLEMENTED
**BUSINESS LOGIC:** NOT IMPLEMENTED

## Next Task
Backend Phase 3 — Authentication foundation (`includes/auth.php`, `auth/login.php` backend, `auth/register.php` backend, `auth/logout.php`, session setup). Do **not** implement products, cart, or checkout until auth is complete.

---

# CURRENT AI HANDOFF

**Frontend is FROZEN.** Do not redesign existing pages.

**Completed This Session (2026-08-17):**
- Backend Phase 1: `database.sql` — 10 tables, constraints, seed data (tested)
- Backend Phase 2: `config/database.php` — PDO connection (tested)

**Exact Next Recommended Action:**
Implement authentication (Backend Phase 3): session setup, `includes/auth.php`, login/register/logout handlers. Use `require_once` for `config/database.php` and the shared `$conn` variable.

**Do NOT yet implement:** products DB wiring, cart, checkout, orders, APIs, or frontend redesign.
