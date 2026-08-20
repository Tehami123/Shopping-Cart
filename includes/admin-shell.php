<?php
function render_admin_sidebar(array $adminNav, string $activePage, string $basePath): void
{
    ?>
    <aside class="admin-sidebar">
        <div class="admin-sidebar-brand">
            <span class="admin-brand-mark">A</span>
            <div>
                <strong>Arts Admin</strong>
                <span>Store operations</span>
            </div>
        </div>
        <div class="admin-sidebar-label">Workspace</div>
        <nav class="admin-nav" aria-label="Admin navigation">
            <?php foreach ($adminNav as $url => $label): ?>
                <a href="<?= $url ?>" class="<?= $activePage === $url ? 'is-active' : '' ?>">
                    <span class="admin-nav-marker" aria-hidden="true"></span>
                    <span><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="admin-sidebar-footer">
            <div class="admin-user-chip">
                <span class="admin-user-avatar">A</span>
                <span><strong>Administrator</strong><small>Full access</small></span>
            </div>
            <a href="<?= $basePath ?>/auth/logout.php" class="admin-logout">Log out</a>
        </div>
    </aside>
    <?php
}

function render_admin_page_header(string $title, string $description, string $eyebrow = 'Admin workspace'): void
{
    ?>
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow"><?= htmlspecialchars($eyebrow, ENT_QUOTES, 'UTF-8') ?></span>
            <h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
            <p><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></p>
        </div>
    </header>
    <?php
}
