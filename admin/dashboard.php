<?php
session_start();

if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

echo "<h1>Admin Dashboard</h1>";
echo "<a href='add_student.php'>Add Student</a><br>";
echo "<a href='view_students.php'>View Students</a>";
?>