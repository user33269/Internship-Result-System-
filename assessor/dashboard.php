<?php
session_start();

if ($_SESSION['role'] != 'assessor') {
    die("Access denied");
}

echo "<h1>Assessor Dashboard</h1>";
echo "<a href='viewStudents.php'>View Students</a><br>";
?>