<?php
session_start();
include("../includes/auth.php");
include("../includes/navbar.php");
include("../config/db.php");

if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}
//Dashboard stats
$total_students  = $conn->query("SELECT COUNT(*) AS c FROM students")->fetch_assoc()['c'];
$total_assessors = $conn->query("SELECT COUNT(*) AS c FROM users WHERE role='assessor'")->fetch_assoc()['c'];
$total_assessed  = $conn->query("SELECT COUNT(*) AS c FROM assessments")->fetch_assoc()['c'];
$pending         = $total_students - $total_assessed;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>

<?php
echo "<h1>Admin Dashboard</h1>";
echo "<a href='addStudent.php'>Add Student</a><br>";
echo "<a href='viewStudents.php'>View Students</a><br>";
echo "<a href='assignInternship.php'>Assign Internship</a><br>";
echo "<a href='viewInternships.php'>View Internships </a><br>";
echo "<a href='viewResults.php'>View Results</a><br>";
?>

<h2>Overview</h2>
<table cellpadding="10">
    <tr>
        <th>Total Students</th>
        <th>Total Assessors</th>
        <th>Assessed</th>
        <th>Pending Assessment</th>
    </tr>
    <tr>
        <td><?= $total_students ?></td>
        <td><?= $total_assessors ?></td>
        <td><?= $total_assessed ?></td>
        <td><?= $pending ?></td>
    </tr>
</table>

<h2>Other Quick Links</h2>
<a href="manageAssessors.php">Manage Assessors</a> |
<a href="importStudents.php">Import Students (CSV)</a>

</body>
</html>