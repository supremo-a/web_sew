<?php
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../config.php';

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (loginAdmin($pdo, $username, $password)) {
        redirect('./index.php');
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | LP.dev</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; background: var(--bg-main); }
        .login-card {
            width: 100%;
            max-width: 400px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: var(--shadow), var(--shadow-glow);
        }
        .login-brand {
            font-size: 1.75rem;
            font-weight: 800;
            text-align: center;
            color: var(--text-main);
            margin-bottom: 0.25rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill: transparent;
        }
        .login-sub { text-align: center; color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 2rem; }
        .form-group { margin-bottom: 1.25rem; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem; }
        .form-group input { width: 100%; background: var(--bg-secondary); border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem 1rem; border-radius: var(--radius-sm); font-size: 0.95rem; }
        .form-group input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(119,117,255,0.15); }
        .btn { width: 100%; background: var(--primary); color: #fff; border: none; padding: 0.85rem; border-radius: var(--radius-sm); font-size: 1rem; font-weight: 700; cursor: pointer; transition: all var(--transition); }
        .btn:hover { background: #5f5df5; box-shadow: var(--shadow-glow); }
        .alert { background: rgba(255,77,77,0.1); border: 1px solid var(--danger); color: var(--danger); padding: 0.75rem 1rem; border-radius: var(--radius-sm); font-size: 0.85rem; margin-bottom: 1.25rem; }
        .footer-link { text-align: center; margin-top: 1.5rem; color: var(--text-secondary); font-size: 0.85rem; }
        .footer-link a { color: var(--primary); text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-brand">LP.dev</div>
        <div class="login-sub">Admin Panel Login</div>
        <?php if (!empty($error)): ?>
            <div class="alert"><?= h($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autocomplete="username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <button type="submit" class="btn">Sign in</button>
        </form>
        <div class="footer-link">
            <a href="../index.php">Back to portfolio</a>
        </div>
    </div>
</body>
</html>
