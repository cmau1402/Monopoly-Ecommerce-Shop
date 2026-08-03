<?php

session_start();

require_once 'config.php';


/*
|----------------
| Retrieve Orders
|----------------
*/

$sql = "
    SELECT 
        order_id,
        customer_id,
        order_date,
        order_total
    FROM ORDERS
    ORDER BY order_date DESC
";


$result = $db_conn->query($sql);


if (!$result) {
    die("Unable to load orders.");
}

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Order History | Monopoly Shop</title>

    <link rel="stylesheet" href="css/style.css">

</head>


<body>


<h1>Order History</h1>


<hr>


<nav>

    <a href="index.php">Home</a> |
    <a href="shop.php">Shop</a> |
    <a href="cart.php">Cart</a>

</nav>


<hr>


<h2>Previous Orders</h2>



<?php if ($result->num_rows > 0): ?>


<?php while ($order = $result->fetch_assoc()): ?>


<div style="border:1px solid black; padding:15px; margin-bottom:20px;">


<h3>
Order #<?= htmlspecialchars($order['order_id']); ?>
</h3>


<p>
<strong>Customer ID:</strong>
<?= htmlspecialchars($order['customer_id']); ?>
</p>


<p>
<strong>Date:</strong>
<?= htmlspecialchars($order['order_date']); ?>
</p>


<p>
<strong>Total:</strong>
$
<?= number_format($order['order_total'], 2); ?>
</p>



<?php


$stmt = $db_conn->prepare(
    "SELECT 
        oi.quantity,
        oi.purchase_price,
        p.product_name
     FROM order_items oi
     JOIN products p 
     ON oi.product_id = p.product_id
     WHERE oi.order_id = ?"
);


$stmt->bind_param(
    "i",
    $order['order_id']
);


$stmt->execute();


$items = $stmt->get_result();


?>



<table border="1" cellpadding="10" cellspacing="0">


<tr>

<th>
Product
</th>

<th>
Quantity
</th>

<th>
Purchase Price
</th>

<th>
Subtotal
</th>

</tr>



<?php if ($items->num_rows > 0): ?>


<?php while ($item = $items->fetch_assoc()): ?>


<tr>


<td>
<?= htmlspecialchars($item['product_name']); ?>
</td>


<td>
<?= htmlspecialchars($item['quantity']); ?>
</td>


<td>
$
<?= number_format($item['purchase_price'], 2); ?>
</td>


<td>

$
<?= number_format(
    $item['quantity'] * $item['purchase_price'],
    2
); ?>

</td>


</tr>



<?php endwhile; ?>


<?php else: ?>


<tr>

<td colspan="4" align="center">

No items found for this order.

</td>

</tr>


<?php endif; ?>


</table>



</div>



<?php

$stmt->close();

?>


<?php endwhile; ?>



<?php else: ?>


<p>
No previous orders found.
</p>


<?php endif; ?>



<br>


<a href="shop.php">

<button>
Continue Shopping
</button>

</a>



</body>

</html>


<?php

$db_conn->close();

?>