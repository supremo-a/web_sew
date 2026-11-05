<?php
// header.php - Shared public header (navigation)
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
$settings = getSettings($pdo);
$profile = getProfile($pdo);
$logoText = $settings['logo_text'] ?? 'LP.dev';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($settings['site_name'] ?? 'Laurence Portfolio') ?> | <?= h($logoText) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <meta name="description" content="Personal portfolio of Laurence Andrew B. Perlas - Web Developer & IT Student">
</head>
<body>
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="#" class="logo" id="logo"><?= h($logoText) ?></a>
            <ul class="nav-links" id="navLinks">
                <li><a href="#about">About</a></li>
                <li><a href="#education">Education</a></li>
                <li><a href="#skills">Skills</a></li>
                <li><a href="#certifications">Certifications</a></li>
                <li><a href="#projects">Projects</a></li>
            </ul>
            <div class="nav-actions">
                <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
                    <span class="bar"></span><span class="bar"></span><span class="bar"></span>
                </button>
            </div>
        </div>
    </nav>
