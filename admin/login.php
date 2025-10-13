<?php
session_start();
require '../db.php';

// 🔒 Jika admin sudah login, langsung ke dashboard
if (isset($_SESSION['admin'])) {
    header('Location: dashboard.php');
    exit;
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = trim($_POST['password'] ?? '');

    $st = $pdo->prepare("SELECT * FROM admins WHERE username = ? AND password = MD5(?)");
    $st->execute([$u, $p]);
    $adm = $st->fetch();

    if ($adm) {
        $_SESSION['admin'] = $adm['username'];
        $_SESSION['last_activity'] = time();
        header('Location: dashboard.php');
        exit;
    } else {
        $msg = 'Login gagal. Periksa username/password.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Dapoer</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body class="login-body">
    <div class="login-wrapper">
        <div class="login-card">
            <h2>Login Admin</h2>
            <p class="login-subtitle">Masuk ke panel administrasi Dapoer</p>

            <?php if ($msg): ?>
            <div class="error"><?= htmlspecialchars($msg) ?></div>
            <?php endif; ?>

            <form method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Masuk</button>
            </form>

            <div class="login-footer">
                &copy; <?= date('Y') ?> Dapoer. Semua hak dilindungi.
            </div>
        </div>
    </div>
</body>

</html>