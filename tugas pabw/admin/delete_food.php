<?php
session_start();
require '../db.php';
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $st = $pdo->prepare("DELETE FROM foods WHERE id = ?");
    $st->execute([$id]);
}
header('Location: dashboard.php');
exit;
?>
