<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

$pageTitle = 'Manage Customers - Arts';
$basePath = '/Shopping%20Cart';
require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/navbar.php';
require_once dirname(__DIR__) . '/includes/admin-shell.php';

$activePage = 'customers.php';
$adminNav = [
    'index.php' => 'Dashboard', 'products.php' => 'Products', 'inventory.php' => 'Inventory',
    'orders.php' => 'Orders', 'customers.php' => 'Customers', 'employees.php' => 'Employees',
    'payments.php' => 'Payments', 'returns.php' => 'Returns', 'feedback.php' => 'Feedback', 'faq.php' => 'FAQ'
];
$db = get_db_connection();
$customers = $db->query(
    'SELECT c.customer_id, c.first_name, c.last_name, c.phone, c.city, c.created_at, u.email, u.status
     FROM customers c
     INNER JOIN users u ON u.user_id = c.user_id
     ORDER BY c.customer_id DESC'
)->fetchAll();
?>
<main class="admin-app">
    <div class="admin-layout">
        <?php render_admin_sidebar($adminNav, $activePage, $basePath); ?>
        <section class="admin-main">
            <?php render_admin_page_header('Customers', 'A clear directory of customer accounts, contact details, and account status.', 'Customer workspace'); ?>
                
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Customer</th><th>Email</th><th>Phone</th><th>Registered Date</th><th>Status</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customers as $customer): ?>
                                <tr>
                                    <td><a href="customer-details.php?id=<?= (int)$customer['customer_id'] ?>" style="text-decoration:none; color:inherit;"><strong class="admin-primary-cell"><?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name'], ENT_QUOTES, 'UTF-8') ?></strong></a><br><small class="admin-table-muted"><?= htmlspecialchars($customer['city'], ENT_QUOTES, 'UTF-8') ?></small></td>
                                    <td><a class="admin-table-link" href="mailto:<?= htmlspecialchars($customer['email'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($customer['email'], ENT_QUOTES, 'UTF-8') ?></a></td>
                                    <td><?= htmlspecialchars($customer['phone'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= date('d M Y', strtotime($customer['created_at'])) ?></td>
                                    <td><span class="status-badge <?= $customer['status'] === 'active' ? 'status-delivered' : 'status-cancelled' ?>"><?= ucfirst(htmlspecialchars($customer['status'], ENT_QUOTES, 'UTF-8')) ?></span></td>
                                    <td><a href="customer-details.php?id=<?= (int)$customer['customer_id'] ?>" class="text-button" style="text-decoration:none;">View</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
        </section>
    </div>
</main>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

