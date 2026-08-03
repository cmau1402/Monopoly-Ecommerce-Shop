<?php
session_start();

require_once 'config.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/*
|------------------
| Add Items to Cart
|------------------
*/

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['product_id'], $_POST['quantity'])
) {
    $product_id = (int) $_POST['product_id'];
    $quantity   = (int) $_POST['quantity'];

    if ($quantity > 0) {
        $_SESSION['cart'][$product_id] =
            ($_SESSION['cart'][$product_id] ?? 0) + $quantity;
    }

    header('Location: cart.php');
    exit();
}

/*
|-------------
| Remove Items
|-------------
*/

if (isset($_GET['remove'])) {

    $remove_id = (int) $_GET['remove'];

    unset($_SESSION['cart'][$remove_id]);

    header('Location: cart.php');
    exit();
}

/*
|-----------
| Clear Cart
|-----------
*/

if (isset($_GET['clear'])) {

    $_SESSION['cart'] = [];

    header('Location: cart.php');
    exit();
}

$total = 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Shopping Cart | Monopoly Shop</title>
</head>

<body>

<h1>Shopping Cart</h1>

<hr>

<nav>
    <a href="index.php">Home</a> |
    <a href="shop.php">Shop</a> |
    <a href="checkout.php">Checkout</a> |
    <a href="orderHistory.php">Order History</a>
</nav>

<hr>

<h2>Your Cart</h2>

<table border="1" cellpadding="10" cellspacing="0">

    <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Subtotal</th>
        <th>Action</th>
    </tr>

<?php if (empty($_SESSION['cart'])): ?>

    <tr>
        <td colspan="5" align="center">
            Your shopping cart is currently empty.
        </td>
    </tr>

<?php else: ?>

    <?php foreach ($_SESSION['cart'] as $product_id => $quantity): ?>

        <?php

        $stmt = $db_conn->prepare("
            SELECT
                product_id,
                product_name,
                product_price,
                product_qty
            FROM products
            WHERE product_id = ?
        ");

        $stmt->bind_param("i", $product_id);
        $stmt->execute();

        $product = $stmt->get_result()->fetch_assoc();

        if ($product):

            $price = (float) $product['product_price'];
            $subtotal = $price * $quantity;
            $total += $subtotal;

        ?>

        <tr>

            <td><?= htmlspecialchars($product['product_name']) ?></td>

            <td>
                $<?= number_format($price, 2) ?>
            </td>

            <td>
                <?= (int) $quantity ?>
            </td>

            <td>
                $<?= number_format($subtotal, 2) ?>
            </td>

            <td>
                <a href="cart.php?remove=<?= (int) $product_id ?>">
                    Remove
                </a>
            </td>

        </tr>

        <?php endif; ?>

        <?php $stmt->close(); ?>

    <?php endforeach; ?>

<?php endif; ?>

</table>

<br>

<h3>
    Total:
    $<?= number_format($total, 2); ?>
</h3>

<?php if (!empty($_SESSION['cart'])): ?>

    <a href="checkout.php">
        <button>Proceed to Checkout</button>
    </a>

    <a href="cart.php?clear=1">
        <button>Clear Cart</button>
    </a>

<?php else: ?>

    <button disabled>
        Proceed to Checkout
    </button>

<?php endif; ?>

</body>
</html>

<?php
$db_conn->close();