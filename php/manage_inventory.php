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
|---------------
| Delete Product
|---------------

*/

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['delete_product'])
) {

    $delete_id = (int) ($_POST['select_product_id'] ?? 0);

    if ($delete_id > 0) {

        $delete_stmt = $db_conn->prepare(
            "DELETE FROM products WHERE product_id = ?"
        );

        if ($delete_stmt) {

            $delete_stmt->bind_param("i", $delete_id);

            if ($delete_stmt->execute()) {

                $message = "Product successfully removed.";

            } else {

                $error = "Unable to delete product.";

            }

            $delete_stmt->close();

        }

    } else {

        $error = "Please select a valid product.";

    }
}


/*
|---------------
| Update Product
|---------------
*/

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['update_product'])
) {

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

        $update_stmt = $db_conn->prepare(
            "UPDATE products
             SET
                product_name = ?,
                product_description = ?,
                product_qty = ?,
                product_price = ?,
                category_id = ?
             WHERE product_id = ?"
        );


        if ($update_stmt) {

            $update_stmt->bind_param(
                "ssidii",
                $prod_name,
                $prod_description,
                $prod_qty,
                $prod_price,
                $category_id,
                $prod_id
            );


            if ($update_stmt->execute()) {

                $message = "Product successfully updated.";

            } else {

                $error = "Unable to update product.";

            }

            $update_stmt->close();

        }

    } else {

        $error = "Please complete all required fields.";

    }
}


/*
|---------------------------
| Load Products For Dropdown
|---------------------------
*/

$dropdown_result = $db_conn->query(
    "SELECT product_id, product_name
     FROM products
     ORDER BY product_id ASC"
);



/*
|-------------------------
| Load Product For Editing
|-------------------------
*/

$edit_product = null;


if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['load_product'])
) {

    $edit_id = (int) ($_POST['select_product_id'] ?? 0);


    if ($edit_id > 0) {

        $edit_stmt = $db_conn->prepare(
            "SELECT *
             FROM products
             WHERE product_id = ?
             LIMIT 1"
        );


        if ($edit_stmt) {

            $edit_stmt->bind_param("i", $edit_id);

            $edit_stmt->execute();

            $edit_product = $edit_stmt
                ->get_result()
                ->fetch_assoc();

            $edit_stmt->close();


            if (!$edit_product) {

                $error = "Product not found.";

            }

        }


    } else {

        $error = "Please select a product.";

    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Manage Inventory | Monopoly Shop</title>

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


<h2>Manage Inventory</h2>


<p>
Select a product to update its information or remove it from inventory.
</p>



<?php if (!empty($message)): ?>

<p style="color:green;font-weight:bold;">
    <?= htmlspecialchars($message) ?>
</p>

<?php endif; ?>


<?php if (!empty($error)): ?>

<p style="color:red;font-weight:bold;">
    <?= htmlspecialchars($error) ?>
</p>

<?php endif; ?>



<form method="post" action="manage_inventory.php">

<table>


<tr>

<td>Select Product:</td>

<td>

<select name="select_product_id" required>

<option value="">
Choose Product
</option>


<?php if ($dropdown_result): ?>

<?php while ($row = $dropdown_result->fetch_assoc()): ?>


<option value="<?= $row['product_id']; ?>">

ID <?= $row['product_id']; ?> -
<?= htmlspecialchars($row['product_name']); ?>

</option>


<?php endwhile; ?>

<?php endif; ?>


</select>

</td>

</tr>


<tr>

<td colspan="2" align="center">

<br>

<input 
type="submit"
name="load_product"
value="Edit Product">


<input
type="submit"
name="delete_product"
value="Delete Product"
onclick="return confirm('Are you sure you want to delete this product?');">


</td>

</tr>


</table>

</form>




<?php if ($edit_product): ?>


<hr>


<h3>
Edit Product ID:
<?= $edit_product['product_id']; ?>
</h3>



<form method="post" action="manage_inventory.php">


<input
type="hidden"
name="product_id"
value="<?= $edit_product['product_id']; ?>">



<table>


<tr>

<td>Product Name:</td>

<td>

<input
type="text"
name="product_name"
value="<?= htmlspecialchars($edit_product['product_name']); ?>"
required>

</td>

</tr>



<tr>

<td>Category:</td>

<td>

<select name="category_id" required>


<option value="1"
<?= $edit_product['category_id'] == 1 ? 'selected' : '' ?>>
Classic Monopoly
</option>


<option value="2"
<?= $edit_product['category_id'] == 2 ? 'selected' : '' ?>>
Family Editions
</option>


<option value="3"
<?= $edit_product['category_id'] == 3 ? 'selected' : '' ?>>
Pop Culture Editions
</option>


<option value="4"
<?= $edit_product['category_id'] == 4 ? 'selected' : '' ?>>
City & Travel Editions
</option>


<option value="5"
<?= $edit_product['category_id'] == 5 ? 'selected' : '' ?>>
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
value="<?= $edit_product['product_qty']; ?>"
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
value="<?= $edit_product['product_price']; ?>"
required>

</td>

</tr>




<tr>

<td>Description:</td>

<td>

<textarea
name="product_description"
rows="5"
required><?= htmlspecialchars($edit_product['product_description']); ?></textarea>

</td>

</tr>



<tr>

<td colspan="2" align="center">

<br>

<input
type="submit"
name="update_product"
value="Save Changes">


<a href="manage_inventory.php">
Clear
</a>


</td>

</tr>



</table>


</form>


<?php endif; ?>



</body>

</html>


<?php

$db_conn->close();

?>