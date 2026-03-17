<?php
session_start();

if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

echo "<h1>Admin Dashboard</h1>";
?>