<?php
session_start();

if ($_SESSION['role'] != 'assessor') {
    die("Access denied");
}

echo "<h1>Assessor Dashboard</h1>";
?>