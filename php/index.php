<?php
session_start();

/*
|--------------------
| User Authentication
|--------------------
*/

if (!isset($_SESSION['valid_user'])) {
    header("Location: authmain.php");
    exit();
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

<h1>Welcome to Monopoly Shop</h1>

<hr>

<nav>

    <a href="index.php">Home</a> |
    <a href="infopages.php">About</a> |
    <a href="shop.php">Shop</a> |
    <a href="cart.php">Cart</a> |
    <a href="orderHistory.php">Order History</a> |
    <a href="register.php">Create Account</a> |
    <a href="logout.php">Logout</a>

</nav>

<hr>

<h2>Welcome!</h2>

<p>
Welcome to the Monopoly Shop web application, developed as part of a
Database Systems course project. This application allows users to browse
Monopoly-themed merchandise, manage a shopping cart, place orders, and
explore information about the Monopoly brand.
</p>

<p>
Use the navigation menu above to browse products, view your cart,
review previous orders, or manage your account.
</p>

</body>
</html>