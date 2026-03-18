<?php
session_start();
include("../includes/auth.php");
include("../includes/navbar.php");

if ($_SESSION['role'] != 'assessor') {
    die("Access denied");
}

echo "<h1>Assessor Dashboard</h1>";
echo "<a href='viewStudents.php'>View Students</a><br>";
echo "<a href='viewResults.php'>View Results</a><br>";
?>