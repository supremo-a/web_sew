<?php
require_once __DIR__ . '/header.php';

$settings = $pdo->query("SELECT * FROM website_settings LIMIT 1")->fetch();
$message = ''; $messageType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf($_POST['csrf_token'] ?? '')) { $message = 'Invalid security token.'; $messageType = 'error'; }
    else {
        $logo = trim($_POST['logo_text'] ?? '');
        $footer = trim($_POST['footer_text'] ?? '');
        $sub = trim($_POST['footer_subtext'] ?? '');
        $site = trim($_POST['site_name'] ?? '');
        if (!$logo || !$footer || !$site) { $message = 'All fields are required.'; $messageType = 'error'; }
        else {
            $pdo->prepare("UPDATE website_settings SET logo_text=?, footer_text=?, footer_subtext=?, site_name=?, updated_at=NOW() WHERE id=?")
                ->execute([$logo, $footer, $sub ?: 'Built with PHP, MySQL, HTML, CSS & JavaScript.', $site, $settings['id']]);
            logActivity($pdo, $_SESSION['admin_id'], 'UPDATE', 'website_settings', $settings['id'], 'Updated website settings');
            $message = 'Website settings updated.'; $messageType = 'success';
            $settings = $pdo->query("SELECT * FROM website_settings LIMIT 1")->fetch();
        }
    }
}
?>
    <div class="form-card">
        <?php if ($message): ?>
            <div class="alert" style="background:rgba(119,117,255,0.1); border:1px solid var(--primary); color:var(--primary); padding:0.75rem 1rem; margin-bottom:1rem;"><?= h($message) ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <div class="form-group">
                <label>Logo Text</label>
                <input type="text" name="logo_text" value="<?= h($settings['logo_text'] ?? 'LP.dev') ?>" required>
            </div>
            <div class="form-group">
                <label>Site Name (browser title)</label>
                <input type="text" name="site_name" value="<?= h($settings['site_name'] ?? 'Laurence Portfolio') ?>" required>
            </div>
            <div class="form-group">
                <label>Footer Copyright Text</label>
                <input type="text" name="footer_text" value="<?= h($settings['footer_text'] ?? '© 2026 Laurence Andrew B. Perlas. All Rights Reserved.') ?>" required>
            </div>
            <div class="form-group">
                <label>Footer Subtext</label>
                <input type="text" name="footer_subtext" value="<?= h($settings['footer_subtext'] ?? 'Built with PHP, MySQL, HTML, CSS & JavaScript.') ?>">
            </div>
            <button type="submit" class="btn">Save Settings</button>
            <a href="index.php" class="btn btn-outline" style="margin-left:0.5rem;">Cancel</a>
        </form>
    </div>
<?php require_once __DIR__ . '/footer.php'; ?>
