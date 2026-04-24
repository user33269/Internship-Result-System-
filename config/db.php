<?php

$host = "localhost";
$user = "root";
$password = "root";
$database = "COMP1044_Database";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>