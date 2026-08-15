# Arts Online Shopping Cart - Technical Architecture (Simplified)
## Practical Student Project Design | 10-Day Development Timeline

---

## 1. FOLDER STRUCTURE

```
arts-online-shop/
│
├── config/
│   └── database.php          # PDO database connection
│
├── includes/
│   ├── header.php            # HTML page header
│   ├── navbar.php            # Navigation (context-aware by role)
│   ├── footer.php            # HTML page footer
│   ├── auth.php              # Login/logout/session functions
│   ├── rbac.php              # Role-based access control checks
│   └── functions.php         # Utility functions (search, cart, orders, etc.)
│
├── auth/
│   ├── login.php             # Login page
│   ├── register.php          # Customer registration
│   └── logout.php            # Logout handler
│
├── customer/
│   ├── index.php             # Customer dashboard
│   ├── orders.php            # View order history & tracking
│   ├── returns.php           # Request returns/replacements
│   └── account.php           # Manage profile
│
├── employee/
│   ├── index.php             # Employee dashboard
│   ├── orders.php            # View & process orders
│   ├── dispatch.php          # Dispatch orders
│   └── delivery.php          # Update delivery status
│
├── admin/
│   ├── index.php             # Admin dashboard
│   ├── products.php          # Product management (CRUD)
│   ├── inventory.php         # Manage stock
│   ├── orders.php            # View all orders
│   ├── customers.php         # Customer management
│   ├── employees.php         # Employee management
│   ├── payments.php          # Payment verification
│   ├── returns.php           # Return request processing
│   └── feedback.php          # Feedback management
│
├── assets/
│   ├── css/
│   │   └── style.css         # Main stylesheet
│   ├── js/
│   │   ├── main.js           # Common JavaScript
│   │   └── cart.js           # Cart interactions
│   └── images/
│       └── products/         # Product images
│
├── index.php                 # Home page (product browsing)
├── products.php              # All products with pagination
├── product.php               # Single product detail
├── search.php                # Product search
├── cart.php                  # Shopping cart view
├── checkout.php              # Checkout & order creation
│
├── database.sql              # Database schema
├── README.md                 # Project documentation
├── .gitignore                # Git ignore rules
└── .git/                     # Git repository
```

---

## 2. FOLDER RESPONSIBILITIES

| Folder | Purpose |
|--------|---------|
| **config/** | Database connection via PDO |
| **includes/** | Reusable HTML & PHP components |
| **auth/** | Login/registration/logout flows |
| **customer/** | Customer-specific features |
| **employee/** | Employee order management |
| **admin/** | Admin system management |
| **assets/** | CSS, JavaScript, product images |

---

## 3. REUSABLE INCLUDES & FUNCTIONS

### Reusable Include Files

**header.php** - Common page header with CSS, page title, meta tags

**navbar.php** - Navigation bar that changes based on user role:
```
Guest:    Home | Products | Search | Login | Register
Customer: Home | Products | Search | My Orders | Cart | Account | Logout
Employee: Orders | Dispatch | Delivery | Logout
Admin:    Dashboard | Products | Orders | Customers | Employees | Logout
```

**footer.php** - Common page footer with copyright, links

### Core Functions (in includes/functions.php)

#### Product Functions
```php
get_all_products($page = 1, $per_page = 12)
get_product_by_id($product_id)
search_products($keyword)
get_products_by_category($category_id)
format_product_id($code, $number)  // Format 7-digit ID
```

#### Cart Functions (Session-based)
```php
add_to_cart($product_id, $quantity)
remove_from_cart($product_id)
get_cart()
get_cart_total()
clear_cart()
cart_item_count()
```

#### Order Functions
```php
create_order($customer_id, $payment_method, $delivery_type)
get_order_by_id($order_id)
get_customer_orders($customer_id)
can_cancel_order($order_id, $customer_id)  // Before dispatch
generate_order_number($delivery_type, $first_product_id)
```

#### Return Functions
```php
can_request_return($order_id, $customer_id)  // Within 7 days
request_return($order_id, $order_item_id, $return_type, $reason)
```

#### Validation Functions
```php
validate_email($email)
validate_password($password)  // Min 8 chars, alphanumeric
validate_product_id($id)  // 7-digit format
```

#### Helper Functions
```php
format_currency($amount)
format_date($datetime)
redirect_to($page)
die_with_error($message)
```

### Auth Functions (in includes/auth.php)

```php
session_start_custom()        // Initialize secure session
login_user($email, $password) // Authenticate & create session
logout_user()                 // Destroy session
is_logged_in()               // Check session active
current_user_id()            // Get user_id from session
current_user_role()          // Get role from session
get_current_user()           // Fetch user data from DB
```

### RBAC Functions (in includes/rbac.php)

```php
is_customer()               // Check if user role = 'customer'
is_employee()               // Check if user role = 'employee'
is_admin()                  // Check if user role = 'admin'
require_login()             // Redirect if not logged in
require_role($role)         // Redirect if wrong role
```

### Middleware Include Guards

Each protected page starts with:

```php
<?php session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/rbac.php';

// For customer-only pages
if (!is_logged_in() || !is_customer()) {
    http_response_code(403);
    die('Access Denied');
}
?>
```

---

## 4. AUTHENTICATION & SESSION ARCHITECTURE

### Session Strategy

Login creates session with:
```php
$_SESSION['user_id']        // Primary key for user lookups
$_SESSION['user_email']     // For display purposes
$_SESSION['user_role']      // 'customer', 'employee', or 'admin'
$_SESSION['login_time']     // Timestamp for timeout tracking
```

### Security Measures

1. **config/database.php** sets session configuration:
   - `session.cookie_httponly = true` (prevent JS access)
   - `session.cookie_secure = true` (HTTPS only in production)
   - `session.gc_maxlifetime = 3600` (1-hour timeout)

2. **Password Security**
   - Store: `password_hash($password, PASSWORD_BCRYPT)`
   - Verify: `password_verify($input, $hash)`
   - Minimum: 8 characters, alphanumeric

3. **Session Validation**
   - Check session exists before accessing
   - Verify user_id still exists in database
   - Optional: verify session timeout

4. **CSRF Protection** (on forms)
   - Generate token: `$_SESSION['csrf_token'] = bin2hex(random_bytes(32))`
   - Include in forms: `<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">`
   - Verify on submit: `if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) { die('CSRF'); }`

---

## 5. ROLE-BASED ACCESS CONTROL

### Three Roles

```
Customer:  Browse, search, cart, checkout, orders, returns, feedback
Employee:  View orders, dispatch, delivery updates, password change
Admin:     Full system access (products, inventory, customers, employees, orders, payments, returns)
```

### Access Control Method

Use middleware guards at top of each protected page:

```php
// customer/orders.php
<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/rbac.php';

if (!is_logged_in() || !is_customer()) {
    http_response_code(403);
    die('Access Denied');
}
// Page content
?>
```

### Navigation Awareness

navbar.php shows different links based on `current_user_role()`:

```php
<?php
$role = $_SESSION['user_role'] ?? null;

if (!$role):  // Guest
    // Home | Products | Search | Login | Register
elseif ($role === 'customer'):  // Customer
    // Home | Products | Search | My Orders | Cart | Account | Logout
elseif ($role === 'employee'):  // Employee
    // Orders | Dispatch | Delivery | Logout
elseif ($role === 'admin'):  // Admin
    // Dashboard | Products | Inventory | Orders | Customers | Employees | Logout
endif;
?>
```

---

## 6. DATABASE DESIGN

### Core Tables (8 total)

#### 1. **users** - All user types
```sql
users (
    user_id           INT PRIMARY KEY AUTO_INCREMENT
    email             VARCHAR(255) UNIQUE NOT NULL
    password_hash     VARCHAR(255) NOT NULL
    role              ENUM('customer', 'employee', 'admin') NOT NULL
    status            ENUM('active', 'inactive') DEFAULT 'active'
    created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE
)
```

#### 2. **customers** - Customer profile (extends users)
```sql
customers (
    customer_id       INT PRIMARY KEY AUTO_INCREMENT
    user_id           INT UNIQUE NOT NULL (FK: users.user_id)
    first_name        VARCHAR(100) NOT NULL
    last_name         VARCHAR(100) NOT NULL
    phone             VARCHAR(20) NOT NULL
    address           TEXT NOT NULL
    city              VARCHAR(50) NOT NULL
    postal_code       VARCHAR(20) NOT NULL
    country           VARCHAR(50) NOT NULL
    created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
```

#### 3. **employees** - Employee profile (extends users)
```sql
employees (
    employee_id       INT PRIMARY KEY AUTO_INCREMENT
    user_id           INT UNIQUE NOT NULL (FK: users.user_id)
    first_name        VARCHAR(100) NOT NULL
    last_name         VARCHAR(100) NOT NULL
    hire_date         DATE NOT NULL
    created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
```

#### 4. **categories** - Product categories
```sql
categories (
    category_id       INT PRIMARY KEY AUTO_INCREMENT
    name              VARCHAR(100) NOT NULL UNIQUE
    description       TEXT
    created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
```

#### 5. **products** - Product catalog (single source of truth for stock)
```sql
products (
    product_id        INT PRIMARY KEY AUTO_INCREMENT
    product_code      VARCHAR(2) NOT NULL          -- 2-digit code
    product_number    VARCHAR(5) NOT NULL          -- 5-digit number
    full_product_id   VARCHAR(7) UNIQUE            -- Concatenated: code + number
    category_id       INT NOT NULL (FK: categories.category_id)
    name              VARCHAR(255) NOT NULL
    description       TEXT
    price             DECIMAL(10, 2) NOT NULL
    stock             INT NOT NULL DEFAULT 0       -- Single source of truth
    image_url         VARCHAR(255)
    status            ENUM('active', 'inactive') DEFAULT 'active'
    created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE
)
```

#### 6. **orders** - Customer orders
```sql
orders (
    order_id          INT PRIMARY KEY AUTO_INCREMENT
    order_number      VARCHAR(16) UNIQUE NOT NULL  -- 16-digit format: D + 7-digit-ID + 8-digit-sequence
    customer_id       INT NOT NULL (FK: customers.customer_id)
    order_date        TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    total_amount      DECIMAL(10, 2) NOT NULL
    status            ENUM('pending', 'confirmed', 'dispatched', 
                           'delivered', 'cancelled') DEFAULT 'pending'
    payment_method    ENUM('credit_card', 'cheque', 'vpn', 'pay_on_delivery') NOT NULL
    payment_status    ENUM('pending', 'cleared', 'failed') DEFAULT 'pending'
    delivery_type     ENUM('standard', 'express', 'pickup') NOT NULL
    dispatch_date     DATETIME                      -- NULL until dispatched
    delivery_date     DATE                          -- Actual delivery date
    notes             TEXT
    created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE
)
```

#### 7. **order_items** - Items in each order
```sql
order_items (
    order_item_id     INT PRIMARY KEY AUTO_INCREMENT
    order_id          INT NOT NULL (FK: orders.order_id)
    product_id        INT NOT NULL (FK: products.product_id)
    quantity          INT NOT NULL
    unit_price        DECIMAL(10, 2) NOT NULL      -- Price at time of order
    subtotal          DECIMAL(10, 2) GENERATED AS (quantity * unit_price)
)
```

#### 8. **returns** - Return/replacement requests
```sql
returns (
    return_id         INT PRIMARY KEY AUTO_INCREMENT
    order_id          INT NOT NULL (FK: orders.order_id)
    order_item_id     INT NOT NULL (FK: order_items.order_item_id)
    customer_id       INT NOT NULL (FK: customers.customer_id)
    return_type       ENUM('return', 'replacement') NOT NULL
    reason            VARCHAR(255) NOT NULL
    description       TEXT
    request_date      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    status            ENUM('requested', 'approved', 'rejected', 'completed') DEFAULT 'requested'
    approved_by       INT (FK: users.user_id)
    approval_date     DATETIME
    notes             TEXT
    created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
```

### Why This Design?

- **No separate `payments` table**: Payment status tracked in `orders.payment_status`
- **No separate `inventory` table**: Use `products.stock` as single source of truth
- **No separate `deliveries` table**: Dispatch and delivery dates tracked in `orders`
- **No `feedback` table**: Not a priority for 10-day project (can be added later)
- **No `faqs` table**: Not a priority for 10-day project (can be added later)
- **Session-based cart**: Simpler than database carts for student project
- **Minimal tables**: Focus on core business requirements

---

## 7. DATA RELATIONSHIPS

```
                        users (center)
                          |
                /--------+---------\
               /          |         \
          customers    employees   (admin via users)
               |
               |
            orders
               |
        /------+------\
       /       |       \
    order_   order_   returns
    items   items
      |
   products
```

### Key Relationships

| Relationship | Type | Notes |
|--------------|------|-------|
| users ↔ customers | 1:1 | Customer profile |
| users ↔ employees | 1:1 | Employee profile |
| customers ↔ orders | 1:N | Customer places orders |
| orders ↔ order_items | 1:N | Order contains items |
| products ↔ order_items | 1:N | Product in multiple orders |
| orders ↔ returns | 1:N | Order can have multiple returns |
| order_items ↔ returns | 1:N | Each return links to specific item |

---

## 8. 7-DIGIT PRODUCT ID IMPLEMENTATION

### Structure
```
Position 1-2: Product Code (2 digits)    → products.product_code
Position 3-7: Product Number (5 digits)  → products.product_number
Combined:    Full Product ID (7 digits)  → products.full_product_id
```

### Database Implementation

```sql
-- In products table:
product_code      VARCHAR(2) NOT NULL DEFAULT '00'      -- e.g., '12'
product_number    VARCHAR(5) NOT NULL DEFAULT '00000'   -- e.g., '00345'
full_product_id   VARCHAR(7) UNIQUE GENERATED AS        -- Result: '1200345'
                  (CONCAT(product_code, product_number))

-- Index for fast lookups
CREATE INDEX idx_full_product_id ON products(full_product_id);
```

### PHP Usage

```php
// Generate 7-digit ID from code and number
function format_product_id($code, $number) {
    return sprintf("%02d%05d", $code, $number);
}

// Parse 7-digit ID back to components
function parse_product_id($full_id) {
    return [
        'code' => substr($full_id, 0, 2),
        'number' => substr($full_id, 2, 5)
    ];
}

// Validate 7-digit format
function is_valid_product_id($id) {
    return preg_match('/^\d{7}$/', $id) === 1;
}
```

---

## 9. 16-DIGIT ORDER NUMBER IMPLEMENTATION

### Structure
```
Position 1:      Delivery Type (1 digit)
                 1 = Standard
                 2 = Express
                 3 = Pickup
Position 2-8:    Product ID (7 digits, from first order item)
Position 9-16:   Order Sequence (8 digits, YYYYMMDD + counter)
```

### Example
```
Order Number:  1 + 1200345 + 20240815 + 00001
              [D] [Product ID] [Date] [Count]
Result:        1120034520240815000001  (NO - this is 22 digits!)

Corrected:     1 + 1200345 + 00000001
              [D] [Product ID] [Sequence]
Result:        112003450000001  (15 digits - still wrong!)

CORRECT FORMAT (16 digits exactly):
              1 + 1200345 + 00000001
Need 8-digit sequence portion.

Actually reading spec: "8-digit order sequence number"
1 (delivery) + 1200345 (product ID) + 00000001 (8-digit sequence) = 16 digits total
Result: 1120034500000001
```

### Database Implementation

```sql
-- In orders table:
order_number  VARCHAR(16) UNIQUE NOT NULL

-- Example: 1120034500000042
-- Breakdown:
--   1       = Delivery type (standard=1, express=2, pickup=3)
--  1200345  = Product ID (from first order item)
-- 00000042  = 8-digit sequence counter (daily or global)
```

### PHP Generation

```php
function generate_order_number($delivery_type, $first_product_id) {
    // delivery_type: 1=standard, 2=express, 3=pickup
    // first_product_id: 7-digit product ID
    
    $conn = get_db_connection();
    
    // Get count of orders created today
    $query = "SELECT COUNT(*) as count FROM orders WHERE DATE(order_date) = CURDATE()";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $daily_count = $result['count'] + 1;
    
    // Format: D + 7-digit-product-id + 8-digit-sequence
    $order_number = sprintf(
        "%d%s%08d",
        $delivery_type,           // 1 digit
        $first_product_id,        // 7 digits
        $daily_count              // 8 digits (leading zeros)
    );
    
    return $order_number;
}

// Usage in checkout:
$first_product_id = get_first_product_from_cart();  // Returns 7-digit ID
$delivery_type = $_POST['delivery_type'];            // 1, 2, or 3
$order_number = generate_order_number($delivery_type, $first_product_id);
// Result: "1120034500000001" for first order on day with product 1200345
```

### Validation

```php
function is_valid_order_number($order_number) {
    return preg_match('/^\d{16}$/', $order_number) === 1;
}
```

---

## 10. BUSINESS RULES ENFORCEMENT

### Rule 1: Browse Without Login
- Pages `index.php`, `products.php`, `product.php`, `search.php` do NOT include auth middleware
- "Add to Cart" button shows "Login to continue" if not authenticated

### Rule 2: Login Required for Checkout
- `checkout.php` includes middleware:
  ```php
  if (!is_logged_in() || !is_customer()) { die('Access Denied'); }
  ```

### Rule 3: Card/Cheque Payment Must Be Cleared Before Dispatch
- In checkout after payment submission:
  ```php
  if (in_array($payment_method, ['credit_card', 'cheque'])) {
      $payment_status = 'pending';  // Wait for admin verification
  } else {
      $payment_status = 'pending';  // VPP/COD paid later
  }
  ```
- Employee cannot dispatch order unless:
  ```php
  if ($order['payment_method'] === 'credit_card' || $order['payment_method'] === 'cheque') {
      if ($order['payment_status'] !== 'cleared') {
          die('Cannot dispatch: Payment not cleared');
      }
  }
  ```

### Rule 4: VPP/Pay-on-Delivery Paid at Delivery
- Payment status stays 'pending' until delivery
- Employee marks payment as 'cleared' in delivery confirmation

### Rule 5: Cancel Order Only Before Dispatch
```php
function can_cancel_order($order_id, $customer_id) {
    $query = "SELECT dispatch_date FROM orders 
              WHERE order_id = ? AND customer_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$order_id, $customer_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $order && is_null($order['dispatch_date']);
}
```

### Rule 6: Return Within 7 Days of Delivery
```php
function can_request_return($order_id, $customer_id) {
    $query = "SELECT delivery_date FROM orders 
              WHERE order_id = ? AND customer_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$order_id, $customer_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order || is_null($order['delivery_date'])) {
        return false;  // Not delivered yet
    }
    
    $delivery_date = strtotime($order['delivery_date']);
    $deadline = strtotime('+7 days', $delivery_date);
    
    return time() <= $deadline;
}
```

### Rule 7: Admin Only Creates Employees
- Only `is_admin()` users can access `admin/employees.php`

---

## 11. SIMPLIFIED IMPLEMENTATION ROADMAP

### Phase 1: Foundation (Days 1-2)
- [ ] Create database and tables
- [ ] Set up `config/database.php` with PDO
- [ ] Create reusable includes (header, navbar, footer, auth, rbac, functions)
- [ ] Create `index.php` home page

### Phase 2: Authentication (Day 2-3)
- [ ] Create `auth/login.php` 
- [ ] Create `auth/register.php` (customers only)
- [ ] Create `auth/logout.php`
- [ ] Test login flow

### Phase 3: Products (Day 3-4)
- [ ] Create `products.php` (list all)
- [ ] Create `product.php` (single detail)
- [ ] Create `search.php`
- [ ] Admin `admin/products.php` (CRUD)

### Phase 4: Shopping Cart (Day 4-5)
- [ ] Create `cart.php` (session-based)
- [ ] Create cart functions in `includes/functions.php`
- [ ] Add to cart button on products
- [ ] Cart icon with item count

### Phase 5: Checkout & Orders (Day 5-6)
- [ ] Create `checkout.php`
- [ ] Generate 16-digit order number
- [ ] Create order in database
- [ ] Create order_items from cart
- [ ] Clear cart after order

### Phase 6: Customer Features (Day 6-7)
- [ ] Create `customer/index.php` dashboard
- [ ] Create `customer/orders.php` (view orders, track)
- [ ] Create `customer/returns.php` (request return)
- [ ] Create `customer/account.php` (profile)

### Phase 7: Employee Features (Day 7-8)
- [ ] Create `employee/index.php` dashboard
- [ ] Create `employee/orders.php` (list orders to process)
- [ ] Create `employee/dispatch.php` (dispatch order)
- [ ] Create `employee/delivery.php` (update delivery status)

### Phase 8: Admin Features (Day 8-9)
- [ ] Create `admin/index.php` dashboard
- [ ] Create `admin/products.php` (manage products)
- [ ] Create `admin/inventory.php` (manage stock)
- [ ] Create `admin/customers.php` (view customers)
- [ ] Create `admin/employees.php` (manage employees)
- [ ] Create `admin/payments.php` (verify payments)
- [ ] Create `admin/orders.php` (view all orders)
- [ ] Create `admin/returns.php` (process returns)

### Phase 9: Testing & Polish (Day 9-10)
- [ ] Test complete workflows
- [ ] Fix bugs
- [ ] Add error handling
- [ ] Test RBAC
- [ ] Final documentation

---

## 12. TECHNOLOGY DETAILS

### Database Connection (config/database.php)
```php
$db_host = 'localhost';
$db_name = 'arts_shop';
$db_user = 'root';
$db_pass = '';

try {
    $conn = new PDO(
        "mysql:host=$db_host;dbname=$db_name",
        $db_user,
        $db_pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}

// Session security settings
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0);  // Set to 1 for HTTPS
ini_set('session.gc_maxlifetime', 3600);
```

### Prepared Statements (Always)
```php
// ALWAYS use prepared statements - never concatenate SQL
$query = "SELECT * FROM users WHERE email = ? AND role = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$email, $role]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
```

### Password Hashing
```php
// Store
$hash = password_hash($password, PASSWORD_BCRYPT);

// Verify
if (password_verify($input, $hash)) {
    // Correct password
}
```

### Cart Storage (Session-based)
```php
// Initialize cart in session
$_SESSION['cart'] = [];

// Add to cart
$_SESSION['cart'][$product_id] = $quantity;

// Get cart
$cart = $_SESSION['cart'] ?? [];

// Clear cart
unset($_SESSION['cart']);
```

---

## 13. SECURITY PRACTICES

✅ **Implemented**
- Password hashing with bcrypt
- Prepared statements (PDO)
- Session-based authentication
- Role-based access control
- CSRF token validation on forms
- HTTPOnly session cookies

❌ **NOT Implemented (Complexity)**
- Rate limiting
- Two-factor authentication
- API authentication
- File upload validation (no file uploads required)
- Input sanitization HTML escaping (use htmlspecialchars() on output)

---

## 14. KEY SIMPLIFICATIONS FROM ENTERPRISE VERSION

| Removed | Why |
|---------|-----|
| OOP classes (User, Product, Order, etc.) | Plain PHP is simpler for students |
| API layer (api/ folder) | Not needed; forms handle all requests |
| Connection pooling | Not needed for single user/Apache |
| Audit logging | Use logs only if time permits |
| CSV/PDF reporting | Time constraint; basic HTML reports only |
| Caching layer | Not needed for small dataset |
| Stored procedures | Plain SQL is simpler |
| Separate inventory table | Use products.stock single source |
| Separate payments table | Track in orders table |
| Separate deliveries table | Track in orders table |
| FAQs and feedback tables | Lower priority; add after core |

---

## 15. WHAT'S PRIORITIZED

✅ Core Requirements
- ✅ Three user roles with proper access control
- ✅ Product browsing without login
- ✅ Shopping cart
- ✅ Order placement with payment methods
- ✅ Order tracking and dispatch
- ✅ 7-day return window
- ✅ 16-digit order number
- ✅ Admin/employee user management
- ✅ Payment status verification

⏸️ Can Be Added Later (if time permits)
- Feedback/review system
- FAQ management
- Advanced reporting
- Order cancellation (basic version can be added)
- Email notifications

---

## ARCHITECTURE SUMMARY

**Database:** 8 core tables with clear relationships
**Backend:** Plain PHP with PDO prepared statements
**Frontend:** HTML/CSS/JavaScript with Bootstrap
**Authentication:** Session-based with role checking
**Cart:** Session-based (simplest approach)
**Order Numbers:** 16-digit format (1 + 7-digit product + 8-digit sequence)
**Product IDs:** 7-digit format (2-digit code + 5-digit number)

**Goal:** Complete, working eProject in 10 days with proper architecture and security.

---

**Revised:** August 15, 2024 | **Status:** Ready for Student Implementation
