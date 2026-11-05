<?php
// Database configuration for InfinityFree free web hosting
// EDIT THESE VALUES to match your InfinityFree MySQL database credentials
// Get them from the InfinityFree Client Area / phpMyAdmin

define('DB_HOST', '127.0.0.1');   // e.g. sql123.infinityfree.com
define('DB_NAME', 'web_sew');    // your database name
define('DB_USER', 'root');              // your database username
define('DB_PASS', '');    // your database password
define('DB_CHARSET', 'utf8mb4');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    // Do not expose raw error details on production; log instead
    http_response_code(500);
    die('Database connection failed.');
}
