<?php
session_start();

require_once 'config.php';

/*
|------------------------
| Employee Authentication
|------------------------
*/

if (
    !isset($_SESSION['valid_user']) ||
    $_SESSION['user_type'] !== 'employee'
) {
    header("Location: authmain.php");
    exit();
}

$message = '';
$error = '';


/*
|----------------
| Add New Product
|----------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $prod_id          = (int) ($_POST['product_id'] ?? 0);
    $prod_name        = trim($_POST['product_name'] ?? '');
    $prod_description = trim($_POST['product_description'] ?? '');
    $prod_qty         = (int) ($_POST['product_qty'] ?? 0);
    $prod_price       = (float) ($_POST['product_price'] ?? 0);
    $category_id      = (int) ($_POST['category_id'] ?? 0);


    if (
        $prod_id > 0 &&
        !empty($prod_name) &&
        !empty($prod_description) &&
        $prod_price > 0 &&
        $category_id > 0
    ) {


        /*
        |-----------------------
        | Check Existing Product
        |-----------------------
        */

        $check_stmt = $db_conn->prepare(
            "SELECT product_id
             FROM products
             WHERE product_id = ?
             LIMIT 1"
        );

        $check_stmt->bind_param("i", $prod_id);
        $check_stmt->execute();
        $check_stmt->store_result();


        if ($check_stmt->num_rows > 0) {

            $error = "A product with that ID already exists.";

            $check_stmt->close();

        } else {

            $check_stmt->close();


            /*
            |---------------
            | Insert Product
            |---------------
            */

            $insert_stmt = $db_conn->prepare(
                "INSERT INTO products
                (
                    product_id,
                    product_name,
                    product_description,
                    product_qty,
                    product_price,
                    category_id
                )
                VALUES (?, ?, ?, ?, ?, ?)"
            );


            if ($insert_stmt) {


                $insert_stmt->bind_param(
                    "issidi",
                    $prod_id,
                    $prod_name,
                    $prod_description,
                    $prod_qty,
                    $prod_price,
                    $category_id
                );


                if ($insert_stmt->execute()) {

                    $message =
                        "Product successfully added: " .
                        htmlspecialchars($prod_name);

                } else {

                    $error = "Unable to add product.";

                }


                $insert_stmt->close();


            } else {

                $error = "Unable to prepare product request.";

            }
        }


    } else {

        $error = "Please complete all required fields.";

    }
}

$db_conn->close();

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Add Product | Monopoly Shop</title>

    <link rel="stylesheet" href="css/style.css">

</head>


<body>


<h1>Monopoly Shop Staff Portal</h1>


<hr>


<nav>

    <a href="employee_dashboard.php">Dashboard</a> |
    <a href="insert_product.php">Add Product</a> |
    <a href="manage_inventory.php">Manage Inventory</a> |
    <a href="orderHistory.php">Customer Orders</a> |
    <a href="create_employee.php">Create Employee</a> |
    <a href="logout.php">Logout</a>

</nav>


<hr>

<h2>Add New Product</h2>

<p>
Add a new product to the Monopoly Shop inventory.
</p>

<?php if (!empty($message)): ?>

<p style="color:green;font-weight:bold;">
    <?= $message ?>
</p>

<?php endif; ?>

<?php if (!empty($error)): ?>

<p style="color:red;font-weight:bold;">
    <?= htmlspecialchars($error) ?>
</p>

<?php endif; ?>


<form method="post" action="insert_product.php">

<table>

<tr>

<td>Product ID:</td>

<td>
<input 
    type="number"
    name="product_id"
    min="1"
    required>
</td>

</tr>

<tr>

<td>Product Name:</td>

<td>
<input 
    type="text"
    name="product_name"
    required>
</td>

</tr>

<tr>

<td>Category:</td>

<td>

<select name="category_id" required>

<option value="">
Select Category
</option>

<option value="1">
Classic Monopoly
</option>

<option value="2">
Family Editions
</option>

<option value="3">
Pop Culture Editions
</option>

<option value="4">
City & Travel Editions
</option>

<option value="5">
Accessories
</option>

</select>

</td>

</tr>


<tr>

<td>Quantity:</td>

<td>

<input
    type="number"
    name="product_qty"
    min="0"
    value="0"
    required>

</td>

</tr>


<tr>

<td>Price:</td>

<td>

<input
    type="number"
    name="product_price"
    step="0.01"
    min="0.01"
    required>

</td>

</tr>


<tr>

<td>
Product Description:
</td>

<td>

<textarea
    name="product_description"
    rows="5"
    required></textarea>

</td>

</tr>


<tr>

<td colspan="2" align="center">

<br>

<input 
    type="submit"
    value="Add Product">

</td>

</tr>


</table>


</form>


</body>

</html>