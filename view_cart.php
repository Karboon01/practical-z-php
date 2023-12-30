<?php
session_start();
require 'db.php';
include './layouts/header.php';

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    echo 'Корзина пуста';
    exit();
}

$result = $conn->query("SELECT DISTINCT product_id FROM cart");
$cartProductIds = array();
while ($row = $result->fetch_assoc()) {
    $cartProductIds[] = $row['product_id'];
}

$_SESSION['cart'] = array_intersect($_SESSION['cart'], $cartProductIds);


$cartProducts = array();
foreach ($_SESSION['cart'] as $productId) {
    $productInfo = getProductById($productId);

    if ($productInfo) {
        $cartProducts[] = $productInfo;
    } else {
        $cartProducts[] = "Товар с ID $productId не найден";
    }
}

echo '<h1>Содержимое корзины</h1>';
echo '<ul>';
foreach ($cartProducts as $cartProduct) {
    echo '<li>';
    echo '<strong>' . $cartProduct['name'] . '</strong>';
    echo '<br>';
    echo 'Цена: ' . number_format($cartProduct['price'], 0, ',', ' ') . ' руб.';
    echo '</li>';
}
echo '</ul>';

?>
