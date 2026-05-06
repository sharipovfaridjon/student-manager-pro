<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "myapp";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Database connection error.");
}

$conn->set_charset("utf8mb4");

?>