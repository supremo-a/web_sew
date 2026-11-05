<?php
// auth.php - Session-based admin authentication
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config.php';

session_start();

if (!function_exists('isLoggedIn')) {
    function isLoggedIn(): bool {
        return !empty($_SESSION['admin_id']);
    }
}

if (!function_exists('requireLogin')) {
    function requireLogin(): void {
        if (!isLoggedIn()) {
            redirect('/admin/login.php');
        }
    }
}

if (!function_exists('getCurrentAdmin')) {
    function getCurrentAdmin(PDO $pdo): ?array {
        if (!isLoggedIn()) {
            return null;
        }
        $stmt = $pdo->prepare("SELECT id, username, full_name FROM admins WHERE id = ?");
        $stmt->execute([$_SESSION['admin_id']]);
        return $stmt->fetch();
    }
}

if (!function_exists('loginAdmin')) {
    function loginAdmin(PDO $pdo, string $username, string $password): bool {
        $stmt = $pdo->prepare("SELECT id, username, password_hash, full_name FROM admins WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        if ($admin && password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_full_name'] = $admin['full_name'];
            session_regenerate_id(true);
            return true;
        }
        return false;
    }
}

if (!function_exists('logoutAdmin')) {
    function logoutAdmin(): void {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
}
