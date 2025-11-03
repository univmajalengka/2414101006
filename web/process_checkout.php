<?php
session_start();
require 'db.php';

$customer = $_POST['customer_name'] ?? '';
$phone = $_POST['phone'] ?? '';
$visit = $_POST['visit_date'] ?? '';
$cart = $_SESSION['cart'] ?? [];
$buy_now = $_SESSION['buy_now'] ?? null;

if (!$customer || !$phone || !$visit) die("Data tidak lengkap.");

try {
  $pdo->beginTransaction();

  $total = 0;
  if ($buy_now) $total = $buy_now['price'];
  else foreach ($cart as $it) $total += $it['price'] * $it['qty'];

  $pdo->prepare("INSERT INTO orders (customer_name, phone, visit_date, total) VALUES (?,?,?,?)")
      ->execute([$customer, $phone, $visit, $total]);
  $order_id = $pdo->lastInsertId();

  $stmt = $pdo->prepare("INSERT INTO order_items (order_id, food_id, name, price, quantity, subtotal) VALUES (?,?,?,?,?,?)");

  if ($buy_now) {
      $stmt->execute([$order_id,$buy_now['id'],$buy_now['name'],$buy_now['price'],1,$buy_now['price']]);
  } else {
      foreach ($cart as $it) {
          $stmt->execute([$order_id,$it['id'],$it['name'],$it['price'],$it['qty'],$it['price']*$it['qty']]);
      }
  }

  $pdo->commit();
  unset($_SESSION['cart'], $_SESSION['buy_now']);
  header("Location: receipt.php?id=$order_id");
  exit;

} catch(Exception $e) {
  $pdo->rollBack();
  die("Gagal: ".$e->getMessage());
}
?>
