<?php
session_start();

require_once 'config.php';

/*
-------------------------
| Employee Authentication
|------------------------
*/

if (
    !isset($_SESSION['valid_user']) ||
    $_SESSION['user_type'] !== 'employee'
) {
    header('Location: authmain.php');
    exit();
}

$message = '';
$error = '';

/*
|------------------------
| Create Employee Account
|------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $emp_name     = trim($_POST['employee_name'] ?? '');
    $emp_role     = trim($_POST['employee_role'] ?? '');
    $emp_username = trim($_POST['employee_username'] ?? '');
    $emp_password = $_POST['password'] ?? '';

    if (
        !empty($emp_name) &&
        !empty($emp_role) &&
        !empty($emp_username) &&
        !empty($emp_password)
    ) {

        $hashed_password = password_hash(
            $emp_password,
            PASSWORD_DEFAULT
        );

        /*
        |---------------------------------
        | Check if username already exists
        |---------------------------------
        */

        $check_stmt = $db_conn->prepare(
            "SELECT employee_username
             FROM EMPLOYEES
             WHERE employee_username = ?
             LIMIT 1"
        );

        $check_stmt->bind_param("s", $emp_username);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {

            $error = "That employee username is already in use.";
            $check_stmt->close();

        } else {

            $check_stmt->close();

            /*
            |----------------
            | Create Employee
            |----------------
            */

            $insert_stmt = $db_conn->prepare(
                "INSERT INTO EMPLOYEES
                (
                    employee_name,
                    employee_role,
                    employee_username,
                    password_hash
                )
                VALUES (?, ?, ?, ?)"
            );

            if ($insert_stmt) {

                $insert_stmt->bind_param(
                    "ssss",
                    $emp_name,
                    $emp_role,
                    $emp_username,
                    $hashed_password
                );

                if ($insert_stmt->execute()) {

                    $message =
                        "Employee account successfully created for " .
                        htmlspecialchars($emp_name) . ".";

                } else {

                    $error = "Unable to create employee account.";

                }

                $insert_stmt->close();

            } else {

                $error = "Unable to prepare the database request.";

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

    <title>Create Employee Account | Monopoly Shop</title>

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

<h2>Create Employee Account</h2>

<p>
    Register a new employee account by completing the information below.
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

<form method="post" action="create_employee.php">

<table>

<tr>

    <td>Full Name</td>

    <td>

        <input
            type="text"
            name="employee_name"
            required
        >

    </td>

</tr>

<tr>

    <td>Role</td>

    <td>

        <select
            name="employee_role"
            required
        >
            <option value="">Select a Role</option>
            <option value="employee">Employee</option>
            <option value="admin">Administrator</option>
        </select>

    </td>

</tr>

<tr>

    <td>Username</td>

    <td>

        <input
            type="text"
            name="employee_username"
            autocomplete="off"
            required
        >

    </td>

</tr>

<tr>

    <td>Temporary Password</td>

    <td>

        <input
            type="password"
            name="password"
            autocomplete="new-password"
            required
        >

    </td>

</tr>

<tr>

    <td colspan="2" align="center">

        <br>

        <input
            type="submit"
            value="Create Employee Account"
        >

    </td>

</tr>

</table>

</form>

</body>
</html>