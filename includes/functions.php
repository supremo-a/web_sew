<?php
// functions.php - Shared helper functions for public site
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config.php';

if (!function_exists('getProfile')) {
    function getProfile(PDO $pdo): array {
        $stmt = $pdo->query("SELECT * FROM profile LIMIT 1");
        return $stmt->fetch() ?: [];
    }
}

if (!function_exists('getEducation')) {
    function getEducation(PDO $pdo): array {
        $stmt = $pdo->query("SELECT * FROM education ORDER BY sort_order ASC, id ASC");
        return $stmt->fetchAll();
    }
}

if (!function_exists('getSkills')) {
    function getSkills(PDO $pdo): array {
        $stmt = $pdo->query("SELECT * FROM skills ORDER BY sort_order ASC, id ASC");
        return $stmt->fetchAll();
    }
}

if (!function_exists('getCertifications')) {
    function getCertifications(PDO $pdo): array {
        $stmt = $pdo->query("SELECT * FROM certifications ORDER BY sort_order ASC, id DESC");
        return $stmt->fetchAll();
    }
}

if (!function_exists('getSettings')) {
    function getSettings(PDO $pdo): array {
        $stmt = $pdo->query("SELECT * FROM website_settings LIMIT 1");
        return $stmt->fetch() ?: [];
    }
}

if (!function_exists('countRecords')) {
    function countRecords(PDO $pdo, string $table): int {
        $stmt = $pdo->query("SELECT COUNT(*) AS c FROM " . $table);
        return (int)$stmt->fetch()['c'];
    }
}

if (!function_exists('uploadFile')) {
    function uploadFile(array $file, string $subdir, array $allowedTypes): array {
        $result = ['success' => false, 'path' => null, 'error' => null];

        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            $result['error'] = 'No file uploaded.';
            return $result;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $result['error'] = 'Upload error code: ' . (int)$file['error'];
            return $result;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowedTypes, true)) {
            $result['error'] = 'Invalid file type.';
            return $result;
        }

        if ($file['size'] > MAX_FILE_SIZE) {
            $result['error'] = 'File too large (max 5MB).';
            return $result;
        }

        $uploadDir = UPLOADS_DIR . $subdir . '/';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = bin2hex(random_bytes(12)) . '.' . $ext;
        $dest = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            $result['error'] = 'Failed to save uploaded file.';
            return $result;
        }

        $result['success'] = true;
        $result['path'] = UPLOADS_URL . $subdir . '/' . $filename;
        return $result;
    }
}

if (!function_exists('deleteFile')) {
    function deleteFile(string $path): void {
        $full = UPLOADS_DIR . basename($path);
        if (file_exists($full)) {
            @unlink($full);
        }
    }
}
