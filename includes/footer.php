<?php
// footer.php - Shared public footer
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
$settings = getSettings($pdo);
?>
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-brand"><?= h($settings['logo_text'] ?? 'LP.dev') ?></div>
            <p class="footer-copy"><?= h($settings['footer_text'] ?? '© 2026 Laurence Andrew B. Perlas. All Rights Reserved.') ?></p>
            <p class="footer-sub"><?= h($settings['footer_subtext'] ?? 'Built with PHP, MySQL, HTML, CSS & JavaScript.') ?></p>
            <div class="footer-social">
                <a href="<?= h($profile['github_url'] ?? '#') ?>" target="_blank" aria-label="GitHub"><i class="bi bi-github"></i></a>
                <a href="mailto:<?= h($profile['email'] ?? '') ?>" aria-label="Email"><i class="bi bi-envelope"></i></a>
            </div>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>
