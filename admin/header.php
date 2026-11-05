<?php
// admin header - requires authentication
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config.php';

requireLogin();

$admin = getCurrentAdmin($pdo);
$settings = getSettings($pdo);
$totalEdu = countRecords($pdo, 'education');
$totalSkills = countRecords($pdo, 'skills');
$totalCerts = countRecords($pdo, 'certifications');
$totalUploads = countRecords($pdo, 'certifications'); // certifications with uploads
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | <?= h($settings['site_name'] ?? 'Laurence Portfolio') ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Admin layout */
        .admin-wrap {
            display: flex;
            min-height: 100vh;
        }
        .admin-sidebar {
            width: 260px;
            background: var(--bg-secondary);
            border-right: 1px solid var(--border);
            position: fixed;
            top: 0;
            bottom: 0;
            z-index: 100;
            overflow-y: auto;
        }
        .admin-sidebar .brand {
            padding: 1.25rem 1.5rem;
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text-main);
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .admin-sidebar nav ul { padding: 1rem; }
        .admin-sidebar nav ul li { margin-bottom: 0.5rem; }
        .admin-sidebar nav ul li a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
            transition: all var(--transition);
            border: 1px solid transparent;
        }
        .admin-sidebar nav ul li a:hover,
        .admin-sidebar nav ul li a.active {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
            box-shadow: var(--shadow-glow);
        }
        .admin-sidebar nav ul li a i { width: 20px; text-align: center; }
        .admin-main {
            margin-left: 260px;
            flex: 1;
            padding: 2rem;
            background: var(--bg-main);
            min-height: 100vh;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .admin-header h1 {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .admin-header h1 i { color: var(--primary); }
        .user-chip {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: var(--bg-card);
            border: 1px solid var(--border);
            padding: 0.5rem 1rem;
            border-radius: 30px;
            color: var(--text-secondary);
            font-size: 0.85rem;
        }
        .user-chip i { color: var(--secondary); }
        .btn-logout {
            background: var(--danger);
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition);
        }
        .btn-logout:hover { background: #e03e3e; box-shadow: 0 0 20px rgba(255,77,77,0.3); }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: var(--shadow);
        }
        .stat-card .stat-icon {
            width: 56px; height: 56px;
            border-radius: 14px;
            background: rgba(119, 117, 255, 0.1);
            border: 1px solid rgba(119, 117, 255, 0.2);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .stat-card .stat-value { font-size: 1.75rem; font-weight: 800; color: var(--text-main); }
        .stat-card .stat-label { color: var(--text-secondary); font-size: 0.85rem; }

        .quick-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }
        .quick-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            transition: transform var(--transition);
        }
        .quick-card:hover { transform: translateY(-3px); border-color: var(--primary); }
        .quick-card h3 { font-size: 1rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.5rem; }
        .quick-card p { color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 1rem; }
        .quick-card .btn {
            display: inline-block;
            background: var(--primary);
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            font-weight: 600;
            text-align: center;
        }

        .table-wrap {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--border); color: var(--text-main); }
        th { background: var(--bg-secondary); color: var(--text-secondary); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(119, 117, 255, 0.03); }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .badge-primary { background: rgba(119, 117, 255, 0.15); color: var(--primary); border: 1px solid rgba(119,117,255,0.3); }
        .badge-secondary { background: rgba(0, 217, 255, 0.15); color: var(--secondary); border: 1px solid rgba(0,217,255,0.3); }

        .form-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 2rem;
            box-shadow: var(--shadow);
            max-width: 600px;
        }
        .form-group { margin-bottom: 1.25rem; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem; }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            color: var(--text-main);
            padding: 0.75rem 1rem;
            border-radius: var(--radius-sm);
            font-size: 0.95rem;
            transition: border-color var(--transition);
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(119, 117, 255, 0.15);
        }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .file-hint { font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem; }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition);
        }
        .btn:hover { background: #5f5df5; box-shadow: var(--shadow-glow); }
        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-main);
        }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
        .btn-danger {
            background: var(--danger);
            color: #fff;
        }
        .btn-danger:hover { background: #e03e3e; }
        .btn-sm { padding: 0.4rem 0.8rem; font-size: 0.8rem; }
        .btn-group { display: flex; gap: 0.5rem; }
        .actions-cell { white-space: nowrap; }

        .img-preview {
            width: 100px; height: 100px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            object-fit: cover;
            margin-top: 0.5rem;
            display: block;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }
        .empty-state i { font-size: 3rem; color: var(--border); margin-bottom: 1rem; display: block; }

        @media (max-width: 768px) {
            .admin-sidebar { transform: translateX(-100%); transition: transform var(--transition); position: fixed; z-index: 101; }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-main { margin-left: 0; padding: 1.5rem 1rem; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="admin-wrap">
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="brand"><?= h($settings['logo_text'] ?? 'LP.dev') ?></div>
        <nav>
            <ul>
                <li><a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>"><i class="bi bi-speedometer2"></i> Overview</a></li>
                <li><a href="about.php" class="<?= basename($_SERVER['PHP_SELF']) === 'about.php' ? 'active' : '' ?>"><i class="bi bi-person"></i> About</a></li>
                <li><a href="education.php" class="<?= basename($_SERVER['PHP_SELF']) === 'education.php' ? 'active' : '' ?>"><i class="bi bi-mortarboard"></i> Education</a></li>
                <li><a href="skills.php" class="<?= basename($_SERVER['PHP_SELF']) === 'skills.php' ? 'active' : '' ?>"><i class="bi bi-code-square"></i> Skills</a></li>
                <li><a href="certifications.php" class="<?= basename($_SERVER['PHP_SELF']) === 'certifications.php' ? 'active' : '' ?>"><i class="bi bi-diploma"></i> Certifications</a></li>
                <li><a href="settings.php" class="<?= basename($_SERVER['PHP_SELF']) === 'settings.php' ? 'active' : '' ?>"><i class="bi bi-gear"></i> Settings</a></li>
                <li style="margin-top:2rem;"><a href="logout.php" class="btn-logout" style="width:100%; justify-content:center;"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>
    <main class="admin-main">
        <div class="admin-header">
            <div>
                <h1><i class="bi bi-speedometer2"></i><?= ucfirst(str_replace('.php', '', basename($_SERVER['PHP_SELF']))) ?></h1>
            </div>
            <div class="user-chip">
                <i class="bi bi-person-circle"></i>
                <span><?= h($admin['full_name'] ?? $admin['username']) ?></span>
            </div>
        </div>
