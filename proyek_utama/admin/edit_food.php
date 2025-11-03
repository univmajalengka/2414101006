<?php
session_start();
require '../db.php';
if (!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }

$id = (int)($_GET['id'] ?? 0);
$st = $pdo->prepare("SELECT * FROM foods WHERE id = ?");
$st->execute([$id]);
$food = $st->fetch();
if (!$food) { header('Location: dashboard.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = (float)$_POST['price'];
    $cat = $_POST['category'];
    $imgPath = $food['image'];

    if (!empty($_FILES['image']['name'])) {
        $target = '../assets/img/';
        if (!is_dir($target)) mkdir($target,0777,true);
        $fn = time().'_'.basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $target.$fn);
        $imgPath = $fn;
    }

    $up = $pdo->prepare("UPDATE foods SET name=?, description=?, price=?, category=?, image=? WHERE id=?");
    $up->execute([$name, $desc, $price, $cat, $imgPath, $id]);
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Makanan | Dapoer</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body class="edit-body">
    <div class="admin-container">
        <h2>Edit Menu ✏️</h2>
        <form method="post" enctype="multipart/form-data" class="admin-form">
            <input name="name" value="<?= htmlspecialchars($food['name']) ?>" required>
            <input name="category" value="<?= htmlspecialchars($food['category']) ?>" required>
            <input name="price" value="<?= $food['price'] ?>" required>
            <textarea name="description"><?= htmlspecialchars($food['description']) ?></textarea>
            <input type="file" name="image">
            <button type="submit">Update</button>
        </form>
        <a href="dashboard.php" class="back-btn">← Kembali</a>
    </div>
</body>

</html>