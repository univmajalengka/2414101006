<?php
session_start();
require 'db.php';

// Ambil data makanan
$foods = $pdo->query("SELECT * FROM foods ORDER BY id DESC")->fetchAll();

// Proses tombol
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $name = $_POST['name'];
    $price = (float)$_POST['price'];

    if (isset($_POST['add_to_cart'])) {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $id) {
                $item['qty']++;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $_SESSION['cart'][] = ['id'=>$id,'name'=>$name,'price'=>$price,'qty'=>1];
        }

        $_SESSION['message'] = "✅ {$name} berhasil ditambahkan ke keranjang!";
        header("Location: " . $_SERVER['PHP_SELF']); 
        exit;
    }

    if (isset($_POST['buy_now'])) {
        $_SESSION['buy_now'] = ['id'=>$id,'name'=>$name,'price'=>$price,'qty'=>1];
        header("Location: checkout.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dapoer | Pemesanan Makanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <nav class="navbar">
        <div class="logo">Dapoer</div>
        <div class="nav-links">
            <a href="index.php">Beranda</a>
            <a href="cart.php">Keranjang
                (<?= isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0 ?>)</a>
            <a href="admin/login.php" class="admin-btn">Login Admin</a>
        </div>
    </nav>

    <?php if(isset($_SESSION['message'])): ?>
    <div class="message-box">
        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
    </div>
    <?php endif; ?>

    <section class="jumbotron">
        <h1>Selamat Datang di Dapoer 🍛</h1>
        <p>Nikmati cita rasa khas dengan bahan segar pilihan setiap hari!</p>
    </section>

    <div class="menu-container">
        <?php foreach ($foods as $food): ?>
        <div class="menu-card">
            <img src="assets/img/<?= htmlspecialchars($food['image'] ?? 'noimg.jpg') ?>"
                alt="<?= htmlspecialchars($food['name']) ?>">
            <h3><?= htmlspecialchars($food['name']) ?></h3>
            <p class="price">Rp<?= number_format($food['price'],0,',','.') ?></p>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $food['id'] ?>">
                <input type="hidden" name="name" value="<?= htmlspecialchars($food['name']) ?>">
                <input type="hidden" name="price" value="<?= $food['price'] ?>">
                <button type="submit" name="add_to_cart" class="btn">+ Keranjang</button>
                <button type="submit" name="buy_now" class="btn btn-green">Beli Sekarang</button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>

    <section class="testimoni">
        <h2>Pelanggan Kami Suka Makan di Sini 😋</h2>
        <img src="assets/img/erdin.jpg" alt="Erdin makan di sini">
    </section>

    <footer>© <?= date('Y') ?> Dapoer • Makan Enak, Hati Senang</footer>
</body>

</html>