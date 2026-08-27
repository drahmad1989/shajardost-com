<?php
// ============================================================
// SHAJARDOST.COM — Admin Password Setup
// File: admin/setup_admin.php
// RUN ONCE to set admin password, then DELETE this file!
// ============================================================

// Pehle is file ko browser mein kholo
// Apna desired password daalo
// Submit karo — hash generate ho ga
// us hash ko database mein update karo
// FIR IS FILE KO DELETE KARO!

$new_password = $_POST['password'] ?? '';
$hash = '';
$done = false;

if ($new_password) {
    $hash = password_hash($new_password, PASSWORD_DEFAULT);
    $done = true;

    // Auto-update database
    require_once '../includes/db-connect.php';
    $pdo->prepare("UPDATE users SET password_hash = ? WHERE username = 'admin'")
        ->execute([$hash]);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Setup — ShajarDost</title>
    <style>
        body { font-family: sans-serif; max-width: 500px; margin: 60px auto; padding: 20px; }
        h2 { color: #2E7D32; }
        input, button { width: 100%; padding: 10px; margin: 8px 0; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem; }
        button { background: #2E7D32; color: white; border: none; cursor: pointer; font-weight: bold; }
        .result { background: #e8f5e9; padding: 15px; border-radius: 5px; margin-top: 15px; }
        .warning { background: #ffebee; padding: 15px; border-radius: 5px; color: #c62828; font-weight: bold; }
        code { background: #f5f5f5; padding: 3px 6px; border-radius: 3px; word-break: break-all; display: block; margin: 5px 0; }
    </style>
</head>
<body>
    <h2>🔐 ShajarDost Admin Password Setup</h2>

    <?php if (!$done): ?>
    <p>Apna naya admin password daalo. Yeh automatically database mein update ho jaega.</p>
    <form method="POST">
        <input type="password" name="password" placeholder="Naya password" required minlength="8">
        <button type="submit">Password Set Karo</button>
    </form>
    <?php else: ?>
    <div class="result">
        <strong>✅ Password successfully set ho gaya!</strong>
        <p>Hash:</p>
        <code><?= htmlspecialchars($hash) ?></code>
        <p>Database update ho gaya — admin username: <strong>admin</strong></p>
    </div>
    <div class="warning">
        ⚠️ IMPORTANT: Ab is file (setup_admin.php) ko FTP se DELETE karo!
        Security ke liye zaroori hai.
    </div>
    <p><a href="login.php" style="color:#2E7D32;">→ Admin Login Pe Jao</a></p>
    <?php endif; ?>
</body>
</html>
