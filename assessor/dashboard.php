<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");

if ($_SESSION['role'] != 'assessor') {
    die("Access denied");
}

$assessor_id = $_SESSION['user_id'];

$total_students = $conn->query("SELECT COUNT(*) AS c FROM internships WHERE assessor_id='$assessor_id'")->fetch_assoc()['c'];
$total_assessed = $conn->query("SELECT COUNT(*) AS c FROM assessments a JOIN internships i ON a.student_id = i.student_id WHERE i.assessor_id='$assessor_id'")->fetch_assoc()['c'];
$pending = $total_students - $total_assessed;

?>

<!DOCTYPE html>
<html>

<head>
    <title>Assessor Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

        <?php include("../includes/navbar.php"); ?>

    <div style="max-width:900px; margin:40px auto; padding:0 20px;">

        <h1 style="font-size:26px; color:#333; margin-bottom:30px; text-align:center;">Assessor Dashboard</h1>

        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:35px;">

            <div
                style="background:white; border:1px solid #dbdbdb; border-radius:10px; padding:24px; text-align:center; box-shadow:0 1px 4px rgba(0,0,0,0.05);">
                <div style="font-size:36px; font-weight:bold; color:#0095f6;"><?= $total_students ?></div>
                <div style="font-size:13px; color:#888; margin-top:6px;">Assigned Students</div>
            </div>

            <div
                style="background:white; border:1px solid #dbdbdb; border-radius:10px; padding:24px; text-align:center; box-shadow:0 1px 4px rgba(0,0,0,0.05);">
                <div style="font-size:36px; font-weight:bold; color:#0095f6;"><?= $total_assessed ?></div>
                <div style="font-size:13px; color:#888; margin-top:6px;">Assessed</div>
            </div>

            <div
                style="background:white; border:1px solid #dbdbdb; border-radius:10px; padding:24px; text-align:center; box-shadow:0 1px 4px rgba(0,0,0,0.05);">
                <div style="font-size:36px; font-weight:bold; color:#0095f6;"><?= $pending ?></div>
                <div style="font-size:13px; color:#888; margin-top:6px;">Pending Assessment</div>
            </div>

        </div>

        <div style="font-size:18px; font-weight:bold; color:#333; margin-bottom:16px;">Quick Links</div>

        <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:14px; margin-bottom:30px;">

            <a href="viewStudents.php"
                style="background:white; border:1px solid #dbdbdb; border-radius:10px; padding:20px; text-align:center; text-decoration:none; color:#333; font-size:15px; font-weight:bold; box-shadow:0 1px 4px rgba(0,0,0,0.05); transition:all 0.2s;"
                onmouseover="this.style.background='#0095f6'; this.style.color='white'; this.style.borderColor='#0095f6';"
                onmouseout="this.style.background='white'; this.style.color='#333'; this.style.borderColor='#dbdbdb';">
                <span style="font-size:28px; display:block; margin-bottom:10px;">👥</span>View Students
            </a>

            <a href="viewResults.php"
                style="background:white; border:1px solid #dbdbdb; border-radius:10px; padding:20px; text-align:center; text-decoration:none; color:#333; font-size:15px; font-weight:bold; box-shadow:0 1px 4px rgba(0,0,0,0.05); transition:all 0.2s;"
                onmouseover="this.style.background='#0095f6'; this.style.color='white'; this.style.borderColor='#0095f6';"
                onmouseout="this.style.background='white'; this.style.color='#333'; this.style.borderColor='#dbdbdb';">
                <span style="font-size:28px; display:block; margin-bottom:10px;">📊</span>View Results
            </a>

        </div>

    </div>

</body>

</html>