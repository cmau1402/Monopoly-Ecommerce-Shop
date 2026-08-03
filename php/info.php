<?php
session_start();

require_once 'config.php';

/*
|-----------------------------
| Validate Information Page ID
|-----------------------------
*/

if (!isset($_GET['id'])) {
    die("Information page not found.");
}

$info_id = (int) $_GET['id'];


/*
|--------------------------
| Retrieve Information Page
|--------------------------
*/

$stmt = $db_conn->prepare(
    "SELECT *
     FROM monopoly_info_pages
     WHERE info_id = ?"
);

if (!$stmt) {
    die("Unable to load information page.");
}

$stmt->bind_param("i", $info_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Information page not found.");
}

$page = $result->fetch_assoc();

$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        <?= htmlspecialchars($page['title']); ?> | Monopoly Shop
    </title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<h1>
    <?= htmlspecialchars($page['title']); ?>
</h1>

<hr>

<p>
    <?= nl2br(htmlspecialchars($page['content'])); ?>
</p>

<br>

<a href="infopages.php">
    Back to Information Pages
</a>

</body>
</html>

<?php
$db_conn->close();
?>