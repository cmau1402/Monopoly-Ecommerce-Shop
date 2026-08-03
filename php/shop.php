<?php

session_start();

require_once 'config.php';


$search = "";



/*
|---------------
| Product Search
|---------------
*/


if (isset($_GET['search'])) {

    $search = trim($_GET['search']);

}



if ($search !== "") {


    $stmt = $db_conn->prepare(
        "SELECT 
            product_id,
            product_name,
            product_description,
            product_price,
            product_qty,
            category_id
         FROM products
         WHERE product_name LIKE ?
         OR product_description LIKE ?
         ORDER BY product_name"
    );


    $searchTerm = "%" . $search . "%";


    $stmt->bind_param(
        "ss",
        $searchTerm,
        $searchTerm
    );


    $stmt->execute();


    $result = $stmt->get_result();



} else {


    $result = $db_conn->query(
        "SELECT 
            product_id,
            product_name,
            product_description,
            product_price,
            product_qty,
            category_id
         FROM products
         ORDER BY product_name"
    );


}


?>


<!DOCTYPE html>
<html lang="en">


<head>

<meta charset="UTF-8">

<title>Monopoly Shop</title>

<link rel="stylesheet" href="css/style.css">

</head>



<body>



<h1>
Monopoly Shop
</h1>



<hr>



<nav>

<a href="index.php">Home</a> |
<a href="shop.php">Shop</a> |
<a href="cart.php">Cart</a> |
<a href="orderHistory.php">Order History</a>

</nav>



<hr>



<h2>
Products
</h2>




<form method="get" action="shop.php">


<input
type="text"
name="search"
placeholder="Search products"
value="<?= htmlspecialchars($search); ?>">



<input
type="submit"
value="Search">



<a href="shop.php">
Clear
</a>


</form>



<br>




<?php if ($result && $result->num_rows > 0): ?>


<?php while ($row = $result->fetch_assoc()): ?>


<div style="border:1px solid black; padding:15px; margin-bottom:15px;">



<h3>

<a href="product.php?id=<?= $row['product_id']; ?>">

<?= htmlspecialchars($row['product_name']); ?>

</a>

</h3>




<p>

<?= htmlspecialchars($row['product_description']); ?>

</p>



<p>

<strong>
Price:
</strong>

$

<?= number_format($row['product_price'], 2); ?>

</p>




<p>

<strong>
Available:
</strong>

<?= htmlspecialchars($row['product_qty']); ?>

</p>



<?php if ($row['product_qty'] > 0): ?>



<form action="cart.php" method="post">


<input
type="hidden"
name="product_id"
value="<?= $row['product_id']; ?>">



<label>
Quantity:
</label>



<input
type="number"
name="quantity"
value="1"
min="1"
max="<?= $row['product_qty']; ?>">



<input
type="submit"
value="Add to Cart">



</form>



<?php else: ?>



<p>

<strong>
Out of Stock
</strong>

</p>



<?php endif; ?>



</div>



<?php endwhile; ?>



<?php else: ?>


<p>
No products found.
</p>



<?php endif; ?>



</body>


</html>



<?php

$db_conn->close();

?>