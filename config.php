<?php
// config.php - Central constants and path helpers
define('BASE_URL', '');
define('UPLOADS_DIR', __DIR__ . '/../assets/uploads/');
define('UPLOADS_URL', 'assets/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif']);
define('ALLOWED_PDF_TYPES', ['application/pdf']);

if (!function_exists('h')) {
    function h(string $value = null): string {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url): void {
        header('Location: ' . $url, true, 302);
        exit;
    }
}

if (!function_exists('csrfToken')) {
    function csrfToken(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('verifyCsrf')) {
    function verifyCsrf(string $token): bool {
        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            return false;
        }
        return true;
    }
}

if (!function_exists('logActivity')) {
    function logActivity(PDO $pdo, ?int $adminId, string $action, string $table, ?int $recordId, string $description): void {
        $stmt = $pdo->prepare("INSERT INTO activity_logs (admin_id, action, table_name, record_id, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$adminId, $action, $table, $recordId, $description]);
    }
}
