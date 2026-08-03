<?php

session_start();

require_once 'config.php';


$message = '';
$error = '';



/*
|----------------------
| Customer Registration
|----------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $cust_name  = trim($_POST['customer_name'] ?? '');
    $cust_email = trim($_POST['customer_email'] ?? '');
    $username   = trim($_POST['username'] ?? '');
    $password   = $_POST['password'] ?? '';



    if (
        !empty($cust_name) &&
        !empty($cust_email) &&
        !empty($username) &&
        !empty($password)
    ) {



        /*
        |----------------
        | Secure Password
        |----------------
        */

        $hashed_password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );



        /*
        |-----------------------
        | Check Existing Account
        |-----------------------
        */

        $check_stmt = $db_conn->prepare(
            "SELECT username
             FROM CUSTOMERS
             WHERE username = ?
             OR customer_email = ?
             LIMIT 1"
        );


        $check_stmt->bind_param(
            "ss",
            $username,
            $cust_email
        );


        $check_stmt->execute();

        $check_stmt->store_result();



        if ($check_stmt->num_rows > 0) {


            $error =
            "That username or email is already registered.";


            $check_stmt->close();



        } else {


            $check_stmt->close();



            /*
            |------------------------
            | Create Customer Account
            |------------------------
            */


            $insert_stmt = $db_conn->prepare(
                "INSERT INTO CUSTOMERS
                (
                    customer_name,
                    customer_email,
                    username,
                    password_hash
                )
                VALUES (?, ?, ?, ?)"
            );



            if ($insert_stmt) {


                $insert_stmt->bind_param(
                    "ssss",
                    $cust_name,
                    $cust_email,
                    $username,
                    $hashed_password
                );



                if ($insert_stmt->execute()) {


                    $message =
                    "Account created successfully! You can now log in.";


                } else {


                    $error =
                    "Unable to create account.";


                }



                $insert_stmt->close();



            } else {


                $error =
                "Unable to process registration.";


            }

        }



    } else {


        $error =
        "Please fill in all fields.";


    }

}

?>


<!DOCTYPE html>
<html lang="en">


<head>

<meta charset="UTF-8">

<title>Create Account | Monopoly Shop</title>

<link rel="stylesheet" href="css/style.css">

</head>



<body>



<h1>
Welcome to Monopoly Shop
</h1>



<hr>



<nav>

<a href="index.php">Home</a> |
<a href="about.php">About</a> |
<a href="shop.php">Shop</a> |
<a href="cart.php">Cart</a> |
<a href="orderHistory.php">Order History</a> |
<a href="register.php">Create Account</a> |
<a href="logout.php">Logout</a>


</nav>



<hr>



<h2>
Create a Customer Account
</h2>



<?php if (!empty($message)): ?>

<p style="color:green;font-weight:bold;">

<?= htmlspecialchars($message); ?>

</p>

<?php endif; ?>



<?php if (!empty($error)): ?>

<p style="color:red;font-weight:bold;">

<?= htmlspecialchars($error); ?>

</p>

<?php endif; ?>




<form method="post" action="register.php">


<table>


<tr>

<td>
Full Name:
</td>


<td>

<input
type="text"
name="customer_name"
required>

</td>

</tr>



<tr>

<td>
Email Address:
</td>


<td>

<input
type="email"
name="customer_email"
required>

</td>

</tr>



<tr>

<td>
Username:
</td>


<td>

<input
type="text"
name="username"
autocomplete="off"
required>

</td>

</tr>



<tr>

<td>
Password:
</td>


<td>

<input
type="password"
name="password"
autocomplete="new-password"
required>

</td>

</tr>



<tr>

<td colspan="2" align="center">

<br>

<input
type="submit"
value="Create Account">

</td>

</tr>


</table>


</form>



<br>


<p>

Already have an account?

<a href="authmain.php">
Log in here
</a>

</p>



</body>

</html>


<?php

$db_conn->close();

?>