<?php

session_start();

/*
|-------------
| Logout User
|-------------
*/

session_destroy();

header("Location: authmain.php");
exit();

?>