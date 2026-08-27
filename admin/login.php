<?php
// ============================================================
// SHAJARDOST.COM — Admin Login
// File: admin/login.php
// ============================================================
session_start();
require_once '../includes/db-connect.php';

$error = '';

// Agar pehle se logged in hai
if (isAdminLoggedIn()) {
    header('Location: index.php');
    exit;
}

// Login form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION[ADMIN_SESSION_KEY] = true;
            $_SESSION['admin_username']  = $user['username'];
            $_SESSION['admin_role']      = $user['role'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Galat username ya password. Dobara try karein.';
        }
    } else {
        $error = 'Username aur password dono zaruri hain.';
    }
}
?>
<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — ShajarDost</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #2E7D32;
            --dark: #1B5E20;
            --secondary: #4CAF50;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1B5E20 0%, #2E7D32 50%, #388E3C 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: #fff;
            border-radius: 16px;
            padding: 50px 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-logo img {
            height: 60px;
            margin: 0 auto 10px;
            display: block;
        }
        .login-logo h1 {
            font-size: 1.6rem;
            color: var(--primary);
            font-weight: 800;
        }
        .login-logo p {
            color: #666;
            font-size: 0.9rem;
            margin-top: 4px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        .input-wrap {
            position: relative;
        }
        .input-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }
        .form-group input {
            width: 100%;
            padding: 12px 14px 12px 40px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
            outline: none;
        }
        .form-group input:focus {
            border-color: var(--primary);
        }
        .error-msg {
            background: #fff3f3;
            border: 1px solid #f44336;
            color: #c62828;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-login {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-login:hover {
            background: var(--dark);
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #666;
            font-size: 0.9rem;
            text-decoration: none;
            transition: color 0.3s;
        }
        .back-link a:hover { color: var(--primary); }
        .back-link i { margin-right: 4px; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
            <img src="../assets/images/shajardostlogo.webp" alt="ShajarDost"
                 onerror="this.style.display='none'">
            <h1>ShajarDost</h1>
            <p><i class="fas fa-tree"></i> Admin Panel</p>
        </div>

        <?php if ($error): ?>
        <div class="error-msg">
            <i class="fas fa-exclamation-circle"></i>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <div class="input-wrap">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="admin"
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                           required autofocus>
                </div>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
            </div>
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

        <div class="back-link">
            <a href="../index.php"><i class="fas fa-arrow-left"></i> Wapas Website Pe</a>
        </div>
    </div>
</body>
</html>
