<?php
function render_employee_sidebar(array $employeeNav, string $activePage, string $basePath): void
{
    ?>
    <aside class="employee-sidebar">
        <div class="employee-sidebar-brand">
            <span class="employee-brand-mark">A</span>
            <div>
                <strong>Arts Operations</strong>
                <span>Employee workspace</span>
            </div>
        </div>
        <div class="employee-sidebar-label">Your workflow</div>
        <nav class="employee-nav" aria-label="Employee navigation">
            <?php foreach ($employeeNav as $url => $label): ?>
                <a href="<?= $url ?>" class="<?= $activePage === $url ? 'is-active' : '' ?>">
                    <span class="employee-nav-marker" aria-hidden="true"></span>
                    <span><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="employee-sidebar-footer">
            <div class="employee-user-chip"><span class="employee-user-avatar">E</span><span><strong>Employee Portal</strong><small>Operations access</small></span></div>
            <a href="<?= $basePath ?>/auth/logout.php" class="employee-logout">Log out</a>
        </div>
    </aside>
    <?php
}

function render_employee_page_header(string $title, string $description, string $eyebrow = 'Operations workspace'): void
{
    ?>
    <header class="employee-page-header">
        <div>
            <span class="employee-eyebrow"><?= htmlspecialchars($eyebrow, ENT_QUOTES, 'UTF-8') ?></span>
            <h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
            <p><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></p>
        </div>
    </header>
    <?php
}
