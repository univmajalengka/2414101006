<?php
session_start();
$cart = $_SESSION['cart'] ?? [];
$buy_now = $_SESSION['buy_now'] ?? null;

// Cek jika keranjang dan beli-sekarang kosong
if (empty($cart) && empty($buy_now)) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Checkout | Dapoer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> <!-- ✅ pakai CSS umum -->
</head>

<body>

    <nav class="navbar">
        <div class="logo">🥗 Dapoer</div>
        <div class="nav-links">
            <a href="index.php">Beranda</a>
            <a href="cart.php">Keranjang</a>
        </div>
    </nav>

    <div class="checkout-container">
        <h2>Checkout Pesanan</h2>
        <form action="process_checkout.php" method="POST" onsubmit="return validateForm()">
            <label>Nama Pemesan</label>
            <input type="text" name="customer_name" required>

            <label>No. HP</label>
            <input type="text" name="phone" id="phone" maxlength="16" pattern="[0-9]{10,16}"
                placeholder="Hanya angka (10–16 digit)" required>

            <label>Tanggal Kunjungan</label>
            <input type="date" name="visit_date" required>

            <button type="submit" class="btn">Pesan Sekarang</button>
        </form>
    </div>

    <footer>
        © <?= date('Y') ?> Dapoer
    </footer>

    <script>
    function validateForm() {
        const phone = document.getElementById('phone').value.trim();
        const regex = /^[0-9]{10,16}$/;

        if (!regex.test(phone)) {
            alert("Nomor HP hanya boleh berisi angka (10–16 digit) dan tidak boleh kosong!");
            return false;
        }
        return true;
    }
    </script>

</body>

</html>