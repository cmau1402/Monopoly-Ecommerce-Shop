<?php

session_start();

require_once 'config.php';


/*
|-----------------
| Check Product ID
|-----------------
*/

if (!isset($_GET['id'])) {
    die("Product not found.");
}

$product_id = (int) $_GET['id'];


/*
|-----------------------------
| Retrieve Product Information
|-----------------------------
*/

$stmt = $db_conn->prepare(
    "SELECT 
        product_id,
        product_name,
        product_description,
        product_price,
        product_qty
     FROM products
     WHERE product_id = ?
     LIMIT 1"
);


$stmt->bind_param("i", $product_id);

$stmt->execute();


$result = $stmt->get_result();


if ($result->num_rows === 0) {
    die("Product not found.");
}


$product = $result->fetch_assoc();


$stmt->close();



/*
|--------------------
| Add Product To Cart
|--------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $quantity = (int) ($_POST['quantity'] ?? 1);


    if ($quantity > 0) {

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }


        if (isset($_SESSION['cart'][$product_id])) {

            $_SESSION['cart'][$product_id] += $quantity;

        } else {

            $_SESSION['cart'][$product_id] = $quantity;

        }


        header("Location: cart.php");
        exit();

    }

}

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        <?= htmlspecialchars($product['product_name']); ?> | Monopoly Shop
    </title>

    <link rel="stylesheet" href="css/style.css">

</head>


<body>


<h1>Monopoly Shop</h1>


<hr>


<nav>

    <a href="index.php">Home</a> |
    <a href="shop.php">Shop</a> |
    <a href="cart.php">Cart</a> |
    <a href="orderHistory.php">Order History</a>

</nav>


<hr>



<h2>
    <?= htmlspecialchars($product['product_name']); ?>
</h2>



<p>

<strong>Description:</strong>

<br>

<?= nl2br(htmlspecialchars($product['product_description'])); ?>

</p>



<p>

<strong>Price:</strong>

$

<?= number_format($product['product_price'], 2); ?>

</p>



<p>

<strong>Available Quantity:</strong>

<?= htmlspecialchars($product['product_qty']); ?>

</p>




<?php if ($product['product_qty'] > 0): ?>


<form method="post" action="product.php?id=<?= $product_id; ?>">


<label>
Quantity:
</label>


<input
    type="number"
    name="quantity"
    value="1"
    min="1"
    max="<?= $product['product_qty']; ?>">


<br><br>


<input
    type="submit"
    value="Add To Cart">


</form>



<?php else: ?>


<p>
This product is currently out of stock.
</p>


<?php endif; ?>



<br>


<a href="shop.php">
Back to Shop
</a>



</body>

</html>


<?php

$db_conn->close();

?>