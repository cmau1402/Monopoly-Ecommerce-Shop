<?php

session_start();

require_once 'config.php';


/*
|-----------
| Check Cart
|-----------
*/

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {

    header("Location: shop.php");
    exit();

}



/*
|---------------------
| Customer Information
|---------------------
*/

/*
 Replace this with the logged-in customer ID
 after connecting your customer login system.
*/

$customer_id = 1;


$total = 0;

$items = array();



/*
|-----------------------
| Retrieve Cart Products
|-----------------------
*/

foreach ($_SESSION['cart'] as $product_id => $quantity) {


    $stmt = $db_conn->prepare(
        "SELECT 
            product_id,
            product_name,
            product_price,
            product_qty
         FROM products
         WHERE product_id = ?"
    );


    $stmt->bind_param(
        "i",
        $product_id
    );


    $stmt->execute();


    $result = $stmt->get_result();


    $product = $result->fetch_assoc();



    if ($product) {


        $subtotal =
            $product['product_price'] * $quantity;


        $total += $subtotal;



        $items[] = array(

            "product_id" => $product['product_id'],

            "product_name" => $product['product_name'],

            "price" => $product['product_price'],

            "quantity" => $quantity,

            "subtotal" => $subtotal,

            "current_qty" => $product['product_qty']

        );

    }


    $stmt->close();

}




/*
|-------------
| Create Order
|-------------
*/


$stmt = $db_conn->prepare(
    "INSERT INTO ORDERS
    (customer_id, order_total)
    VALUES (?, ?)"
);


$stmt->bind_param(
    "id",
    $customer_id,
    $total
);


$stmt->execute();


$order_id = $db_conn->insert_id;


$stmt->close();




/*
|--------------------------------------
| Save Order Items And Update Inventory
|--------------------------------------
*/


foreach ($items as $item) {


    $stmt = $db_conn->prepare(
        "INSERT INTO order_items
        (
            order_id,
            product_id,
            quantity,
            purchase_price
        )
        VALUES (?, ?, ?, ?)"
    );


    $stmt->bind_param(
        "iiid",
        $order_id,
        $item['product_id'],
        $item['quantity'],
        $item['price']
    );


    $stmt->execute();


    $stmt->close();



    $new_qty =
        $item['current_qty'] - $item['quantity'];



    if ($new_qty < 0) {

        $new_qty = 0;

    }



    $stmt = $db_conn->prepare(
        "UPDATE products
         SET product_qty = ?
         WHERE product_id = ?"
    );


    $stmt->bind_param(
        "ii",
        $new_qty,
        $item['product_id']
    );


    $stmt->execute();


    $stmt->close();

}


?>


<!DOCTYPE html>
<html lang="en">


<head>

<meta charset="UTF-8">

<title>Order Receipt | Monopoly Shop</title>

<link rel="stylesheet" href="css/style.css">

</head>



<body>


<h1>Order Receipt</h1>


<hr>


<nav>

<a href="index.php">Home</a> |
<a href="shop.php">Shop</a> |
<a href="orderHistory.php">Order History</a>

</nav>


<hr>



<h2>
Thank You For Your Order!
</h2>



<p>

<strong>
Order Number:
</strong>

<?= htmlspecialchars($order_id); ?>

</p>




<table border="1" cellpadding="10" cellspacing="0">


<tr>

<th>
Product
</th>

<th>
Price
</th>

<th>
Quantity
</th>

<th>
Subtotal
</th>

</tr>



<?php foreach ($items as $item): ?>


<tr>


<td>

<?= htmlspecialchars($item['product_name']); ?>

</td>



<td>

$
<?= number_format($item['price'], 2); ?>

</td>



<td>

<?= htmlspecialchars($item['quantity']); ?>

</td>



<td>

$
<?= number_format($item['subtotal'], 2); ?>

</td>


</tr>



<?php endforeach; ?>


</table>



<h3>

Total:

$

<?= number_format($total, 2); ?>

</h3>



<p>
Your order has been saved successfully.
</p>



<a href="shop.php">
Continue Shopping
</a>



<?php

$_SESSION['cart'] = array();

$db_conn->close();

?>


</body>

</html>