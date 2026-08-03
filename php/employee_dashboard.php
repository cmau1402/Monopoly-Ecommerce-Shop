<?php
session_start();

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

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Employee Dashboard | Monopoly Shop</title>

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

<h2>Employee Dashboard</h2>

<p>
Welcome to the Monopoly Shop administrative dashboard.
</p>

</body>
</html>