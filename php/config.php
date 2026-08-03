<?php
$host = "localhost";
$username = "YOUR_DATABASE_USERNAME";
$password = "YOUR_DATABASE_PASSWORD";
$database = "YOUR_DATABASE_NAME";

$db_conn = new mysqli($host, $username, $password, $database);

if ($db_conn->connect_error) {
    die("Connection failed.");
}
?>