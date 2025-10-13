<?php
require 'db.php';
$id = $_GET['id'] ?? 0;

// Ambil data pesanan
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id=?");
$stmt->execute([$id]);
$order = $stmt->fetch();

// Ambil item pesanan
$stmt2 = $pdo->prepare("SELECT * FROM order_items WHERE order_id=?");
$stmt2->execute([$id]);
$items = $stmt2->fetchAll();

// Validasi nomor HP agar hanya angka & max 16 digit
$phone = preg_replace('/[^0-9]/', '', $order['phone']); // hapus huruf/simbol
if (strlen($phone) > 16) {
  $phone = substr($phone, 0, 16); // potong jika lebih dari 16 digit
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Nota Pembelian | Dapoer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="receipt.css">
    <!-- ✅ pakai style.css -->
</head>

<body>
    <nav class="navbar">
        <div class="logo">🥗 Dapoer</div>
    </nav>

    <div class="receipt-container fade-in">
        <div class="store-name">Dapoer</div>
        <div class="store-address">Jl. Kuliner No. 88, Majalengka<br>Telp: (021) 123-4567</div>

        <hr>

        <div class="info">
            <strong>No. Pesanan:</strong> <?= htmlspecialchars($order['id']) ?><br>
            <strong>Nama:</strong> <?= htmlspecialchars($order['customer_name']) ?><br>
            <strong>HP:</strong> <?= htmlspecialchars($phone) ?><br>
            <strong>Tanggal:</strong> <?= htmlspecialchars($order['visit_date']) ?>
        </div>

        <hr>

        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($items as $it): ?>
                <tr>
                    <td><?= htmlspecialchars($it['name']) ?></td>
                    <td><?= $it['quantity'] ?></td>
                    <td>Rp<?= number_format($it['price'], 0, ',', '.') ?></td>
                    <td>Rp<?= number_format($it['subtotal'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="total-row">Total</td>
                    <td>Rp<?= number_format($order['total'], 0, ',', '.') ?></td>
                </tr>
            </tfoot>
        </table>

        <hr>

        <p style="text-align:center;font-size:0.9em;">Terima kasih telah berbelanja di Dapoer 🍽️<br>Semoga harimu
            menyenangkan!</p>

        <button onclick="window.print()" class="print-btn">🖨 Cetak Nota</button>
    </div>

    <footer>© <?= date('Y') ?> Dapoer</footer>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        document.body.classList.add("fade-in");
    });
    </script>
</body>

</html>