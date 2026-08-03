<?php
session_start();

require_once 'config.php';

/*
|---------------------------
| Retrieve Information Pages
|---------------------------
*/

$query = "
    SELECT info_id, title
    FROM monopoly_info_pages
    ORDER BY created_at DESC
";

$result = $db_conn->query($query);

if (!$result) {
    die("Unable to load information pages.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Monopoly Information Pages</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<h1>Monopoly Information Pages</h1>

<hr>

<nav>

    <a href="index.php">Home</a> |
    <a href="shop.php">Shop</a> |
    <a href="cart.php">Cart</a> |
    <a href="orderHistory.php">Order History</a>

</nav>

<hr>

<ul>

<?php while ($row = $result->fetch_assoc()): ?>

    <li>
        <a href="info.php?id=<?= (int) $row['info_id']; ?>">
            <?= htmlspecialchars($row['title']); ?>
        </a>
    </li>

<?php endwhile; ?>

</ul>

<br>

<a href="index.php">
    Back to Home
</a>

</body>
</html>

<?php
$db_conn->close();
?>