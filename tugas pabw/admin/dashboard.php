<?php
session_start();
require '../db.php';

// 🔐 Cek login
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// 🕒 Auto logout setelah 10 menit tidak aktif
$timeout = 10 * 60;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset();
    session_destroy();
    header('Location: login.php?timeout=1');
    exit;
}
$_SESSION['last_activity'] = time();

// Ambil data makanan
$foods = $pdo->query("SELECT * FROM foods ORDER BY id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin | Dapoer</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body class="admin-body">
    <div class="admin-container">
        <header>
            <h2>Dashboard Admin — 🍱 Dapoer</h2>
            <div class="admin-user">
                <p>Halo, <strong><?= htmlspecialchars($_SESSION['admin']) ?></strong></p>
                <div class="top-buttons">
                    <a href="../index.php" class="back-btn">⬅️ Kembali ke Pemesanan</a>
                    <a href="logout.php" class="logout-btn">Logout</a>
                </div>
            </div>

            <div class="admin-nav">
                <a href="add_food.php" class="nav-btn">Tambah Makanan</a>
                <a href="orders.php" class="nav-btn">Lihat Pesanan</a>
            </div>
        </header>

        <main>
            <h3>Daftar Menu</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($foods as $f): ?>
                    <tr>
                        <td><?= $f['id'] ?></td>
                        <td><?= htmlspecialchars($f['name']) ?></td>
                        <td><?= htmlspecialchars($f['category']) ?></td>
                        <td>Rp <?= number_format($f['price'],0,',','.') ?></td>
                        <td>
                            <a href="edit_food.php?id=<?= $f['id'] ?>" class="edit-btn">Edit</a> |
                            <a href="delete_food.php?id=<?= $f['id'] ?>" class="delete-btn"
                                onclick="return confirm('Hapus menu ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>

</html>