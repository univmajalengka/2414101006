<?php
session_start();
require '../db.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

try {
    // Nonaktifkan foreign key sementara (agar bisa hapus berurutan)
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    // Hapus semua data dari tabel terkait
    $pdo->exec("DELETE FROM order_items");
    $pdo->exec("DELETE FROM orders");

    // Reset auto increment ke 1 untuk kedua tabel
    $pdo->exec("ALTER TABLE order_items AUTO_INCREMENT = 1");
    $pdo->exec("ALTER TABLE orders AUTO_INCREMENT = 1");

    // Aktifkan kembali foreign key
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    // Redirect kembali ke halaman daftar pesanan dengan notifikasi sukses
    header('Location: orders.php?cleared=1');
    exit;
    
} catch (PDOException $e) {
    echo "<h3 style='color:red; font-family:sans-serif; text-align:center; margin-top:50px;'>Terjadi kesalahan: "
         . htmlspecialchars($e->getMessage()) . "</h3>";
}
?>