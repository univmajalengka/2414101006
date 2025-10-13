<?php
session_start();
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Tombol plus / minus
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id'])) {
    $id = $_POST['id'];
    foreach ($_SESSION['cart'] as $i => $item) {
        if ($item['id'] == $id) {
            if ($_POST['action'] == 'plus') $_SESSION['cart'][$i]['qty']++;
            if ($_POST['action'] == 'minus') {
                $_SESSION['cart'][$i]['qty']--;
                if ($_SESSION['cart'][$i]['qty'] <= 0) unset($_SESSION['cart'][$i]);
            }
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header('Location: cart.php'); 
    exit;
}

$cart = $_SESSION['cart'];
$total = 0;
foreach ($cart as $it) $total += $it['price'] * $it['qty'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Keranjang | Dapoer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

    <!-- ✅ Tambahkan CSS berikut agar footer otomatis di bawah -->
    <style>
    html,
    body {
        height: 100%;
        margin: 0;
        display: flex;
        flex-direction: column;
    }

    main {
        flex: 1;
    }

    footer {
        background-color: #27ae60;
        color: white;
        text-align: center;
        padding: 1rem;
    }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="logo">🥗 Dapoer</div>
        <div class="nav-links">
            <a href="index.php">Beranda</a>
            <a href="checkout.php">Checkout</a>
        </div>
    </nav>

    <!-- Ganti kontainer utama jadi <main> agar CSS flex berfungsi -->
    <main>
        <div class="checkout-container">
            <h2>Keranjang Belanja</h2>
            <?php if (empty($cart)): ?>
            <p class="empty-cart">Keranjang masih kosong 😢</p>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($cart as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td>Rp<?= number_format($item['price'],0,',','.') ?></td>
                        <td>
                            <form method="POST" class="qty-form">
                                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                <button class="qty-btn" name="action" value="minus">−</button>
                                <span><?= $item['qty'] ?></span>
                                <button class="qty-btn" name="action" value="plus">+</button>
                            </form>
                        </td>
                        <td>Rp<?= number_format($item['price']*$item['qty'],0,',','.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Total</th>
                        <th>Rp<?= number_format($total,0,',','.') ?></th>
                    </tr>
                </tfoot>
            </table>
            <div class="checkout-btn-container">
                <a href="checkout.php" class="btn">Lanjut ke Checkout</a>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>© <?= date('Y') ?> Dapoer</footer>
</body>

</html>