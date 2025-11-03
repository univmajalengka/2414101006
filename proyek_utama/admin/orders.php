<?php
session_start();
require '../db.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Ambil semua pesanan urut berdasarkan waktu
$orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll();
$cleared = isset($_GET['cleared']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Pesanan | Dapoer</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body class="orders-body">
    <div class="admin-container">
        <h2>Daftar Pesanan 📦</h2>

        <div class="action-bar">
            <a href="dashboard.php" class="back-btn">← Kembali</a>
            <a href="clear_orders.php" class="clear-btn"
                onclick="return confirm('Yakin ingin menghapus semua data pesanan?')">
                🗑 Hapus Semua Pesanan
            </a>
        </div>

        <?php if ($cleared): ?>
        <div class="alert">✅ Semua pesanan berhasil dihapus dan nomor urut telah direset.</div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>HP</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($orders) === 0): ?>
                <tr>
                    <td colspan="6">Belum ada pesanan.</td>
                </tr>
                <?php else: ?>
                <?php $no = 1; foreach ($orders as $o): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($o['customer_name']) ?></td>
                    <td><?= htmlspecialchars($o['phone']) ?></td>
                    <td><?= $o['visit_date'] ?></td>
                    <td>Rp <?= number_format($o['total'], 0, ',', '.') ?></td>
                    <td><a href="../receipt.php?id=<?= $o['id'] ?>" target="_blank">Lihat Nota</a></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>