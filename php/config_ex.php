<?php

$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

$db_conn = new mysqli($host, $username, $password, $database);

if ($db_conn->connect_error) {
    die("Database connection failed.");
}