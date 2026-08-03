<?php
session_start();

require_once 'config.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

$total = 0;
$cart_items = [];

foreach ($_SESSION['cart'] as $product_id => $quantity) {

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

    if ($product) {

        $subtotal = $product['product_price'] * $quantity;
        $total += $subtotal;

        $cart_items[] = [
            'product_id'    => $product['product_id'],
            'product_name'  => $product['product_name'],
            'product_price' => $product['product_price'],
            'quantity'      => $quantity,
            'subtotal'      => $subtotal
        ];
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Checkout | Monopoly Shop</title>
</head>

<body>

<h1>Checkout</h1>

<hr>

<nav>
    <a href="index.php">Home</a> |
    <a href="shop.php">Shop</a> |
    <a href="cart.php">Cart</a> |
    <a href="orderHistory.php">Order History</a>
</nav>

<hr>

<h2>Customer Information</h2>

<form action="receipt.php" method="post">

    <label for="fullname">Full Name:</label><br>
    <input
        type="text"
        id="fullname"
        name="fullname"
        required
    >

    <br><br>

    <label for="email">Email:</label><br>
    <input
        type="email"
        id="email"
        name="email"
        required
    >

    <br><br>

    <label for="address">Shipping Address:</label><br>
    <textarea
        id="address"
        name="address"
        rows="4"
        cols="40"
        required
    ></textarea>

    <br><br>

    <h2>Order Summary</h2>

    <table border="1" cellpadding="10" cellspacing="0">

        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>

        <?php foreach ($cart_items as $item): ?>

            <tr>

                <td>
                    <?= htmlspecialchars($item['product_name']) ?>
                </td>

                <td>
                    $<?= number_format($item['product_price'], 2) ?>
                </td>

                <td>
                    <?= (int) $item['quantity'] ?>
                </td>

                <td>
                    $<?= number_format($item['subtotal'], 2) ?>
                </td>

            </tr>

        <?php endforeach; ?>

    </table>

    <h3>
        Total:
        $<?= number_format($total, 2) ?>
    </h3>

    <input type="submit" value="Place Order">

</form>

</body>
</html>

<?php
$db_conn->close();