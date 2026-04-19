<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");

if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}
//Dashboard stats
$total_students = $conn->query("SELECT COUNT(*) AS c FROM students")->fetch_assoc()['c'];
$total_assessors = $conn->query("SELECT COUNT(*) AS c FROM users WHERE role='assessor'")->fetch_assoc()['c'];
$total_assessed = $conn->query("SELECT COUNT(*) AS c FROM assessments")->fetch_assoc()['c'];
$pending = $total_students - $total_assessed;
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

        <?php include("../includes/navbar.php"); ?>

    <div style="max-width:900px; margin:40px auto; padding:0 20px;">

        <h1 style="font-size:26px; color:#333; margin-bottom:30px; text-align:center;">Admin Dashboard</h1>


        <div style="font-size:18px; font-weight:bold; color:#333; margin-bottom:16px;">Quick Links</div>

        <div style="display:flex; flex-wrap:wrap; justify-content:center; gap:14px; margin-bottom:30px;">

            <a href="addStudent.php"
                style="flex: 0 1 calc(33.33% - 14px); min-width: 200px; background:white; border:1px solid #dbdbdb; border-radius:10px; padding:20px; text-align:center; text-decoration:none; color:#333; font-size:15px; font-weight:bold; box-shadow:0 1px 4px rgba(0,0,0,0.05); transition:all 0.2s;"
                onmouseover="this.style.background='#0095f6'; this.style.color='white';"
                onmouseout="this.style.background='white'; this.style.color='#333';">
                <span style="font-size:28px; display:block; margin-bottom:10px;">🎓</span>Add Student
            </a>

            <a href="viewStudents.php"
                style="flex: 0 1 calc(33.33% - 14px); min-width: 200px; background:white; border:1px solid #dbdbdb; border-radius:10px; padding:20px; text-align:center; text-decoration:none; color:#333; font-size:15px; font-weight:bold; box-shadow:0 1px 4px rgba(0,0,0,0.05); transition:all 0.2s;"
                onmouseover="this.style.background='#0095f6'; this.style.color='white';"
                onmouseout="this.style.background='white'; this.style.color='#333';">
                <span style="font-size:28px; display:block; margin-bottom:10px;">👥</span>View Students
            </a>

            <a href="assignInternship.php"
                style="flex: 0 1 calc(33.33% - 14px); min-width: 200px; background:white; border:1px solid #dbdbdb; border-radius:10px; padding:20px; text-align:center; text-decoration:none; color:#333; font-size:15px; font-weight:bold; box-shadow:0 1px 4px rgba(0,0,0,0.05); transition:all 0.2s;"
                onmouseover="this.style.background='#0095f6'; this.style.color='white';"
                onmouseout="this.style.background='white'; this.style.color='#333';">
                <span style="font-size:28px; display:block; margin-bottom:10px;">📋</span>Assign Internship
            </a>

            <a href="viewInternships.php"
                style="flex: 0 1 calc(33.33% - 14px); min-width: 200px; background:white; border:1px solid #dbdbdb; border-radius:10px; padding:20px; text-align:center; text-decoration:none; color:#333; font-size:15px; font-weight:bold; box-shadow:0 1px 4px rgba(0,0,0,0.05); transition:all 0.2s;"
                onmouseover="this.style.background='#0095f6'; this.style.color='white';"
                onmouseout="this.style.background='white'; this.style.color='#333';">
                <span style="font-size:28px; display:block; margin-bottom:10px;">🏢</span>View Internships
            </a>

            <a href="viewResults.php"
                style="flex: 0 1 calc(33.33% - 14px); min-width: 200px; background:white; border:1px solid #dbdbdb; border-radius:10px; padding:20px; text-align:center; text-decoration:none; color:#333; font-size:15px; font-weight:bold; box-shadow:0 1px 4px rgba(0,0,0,0.05); transition:all 0.2s;"
                onmouseover="this.style.background='#0095f6'; this.style.color='white';"
                onmouseout="this.style.background='white'; this.style.color='#333';">
                <span style="font-size:28px; display:block; margin-bottom:10px;">📊</span>View Results
            </a>

        </div>

    </div>

</body>

</html>