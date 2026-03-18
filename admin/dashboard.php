<?php
session_start();

if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

echo "<h1>Admin Dashboard</h1>";
echo "<a href='addStudent.php'>Add Student</a><br>";
echo "<a href='viewStudents.php'>View Students</a><br>";
echo "<a href='assignInternship.php'>Assign Internship</a><br>";
echo "<a href='viewInternships.php'>View Internships </a><br>";
?>