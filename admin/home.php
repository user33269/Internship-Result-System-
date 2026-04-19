<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");

if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

// Dashboard stats
$total_students = $conn->query("SELECT COUNT(*) AS c FROM students")->fetch_assoc()['c'];
$total_assessors = $conn->query("SELECT COUNT(*) AS c FROM users WHERE role='assessor'")->fetch_assoc()['c'];
$total_assessed = $conn->query("SELECT COUNT(*) AS c FROM assessments")->fetch_assoc()['c'];
$pending = $total_students - $total_assessed;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Home</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<?php include("../includes/navbar.php"); ?>

<div style="max-width:1000px; margin:40px auto; padding:0 20px;">

    
    <div style="background: linear-gradient(135deg, #0095f6, #6a5cff);
                color:white;
                padding:30px;
                border-radius:14px;
                margin-bottom:25px;
                box-shadow:0 6px 18px rgba(0,0,0,0.15);">

        <h1 style="margin:0; font-size:28px;">
            Welcome back, Admin 👋
        </h1>

        <p style="margin-top:8px; font-size:15px; opacity:0.9;">
            Let’s get things moving today. 
        </p>

        <div style="margin-top:12px; font-size:14px; opacity:0.95;">
            You currently have <b><?= $total_students ?></b> students,
            <b><?= $total_assessors ?></b> assessors,
            with <b><?= $pending ?></b> pending assessments waiting for attention.
        </div>

    </div>

   
    <h2 style="font-size:18px; color:#333; margin-bottom:14px;">
        System Overview
    </h2>

    
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px;">

        <div style="background:white; border-radius:12px; padding:22px;
                    box-shadow:0 2px 8px rgba(0,0,0,0.06);
                    border-left:5px solid #0095f6;">

            <div style="font-size:30px; font-weight:bold; color:#0095f6;">
                <?= $total_students ?>
            </div>
            <div style="font-size:13px; color:#777;">
                Total Students
            </div>
        </div>

        <div style="background:white; border-radius:12px; padding:22px;
                    box-shadow:0 2px 8px rgba(0,0,0,0.06);
                    border-left:5px solid #6a5cff;">

            <div style="font-size:30px; font-weight:bold; color:#6a5cff;">
                <?= $total_assessors ?>
            </div>
            <div style="font-size:13px; color:#777;">
                Assessors
            </div>
        </div>

        <div style="background:white; border-radius:12px; padding:22px;
                    box-shadow:0 2px 8px rgba(0,0,0,0.06);
                    border-left:5px solid #2ecc71;">

            <div style="font-size:30px; font-weight:bold; color:#2ecc71;">
                <?= $total_assessed ?>
            </div>
            <div style="font-size:13px; color:#777;">
                Completed Assessments
            </div>
        </div>

        <div style="background:white; border-radius:12px; padding:22px;
                    box-shadow:0 2px 8px rgba(0,0,0,0.06);
                    border-left:5px solid #ff6b6b;">

            <div style="font-size:30px; font-weight:bold; color:#ff6b6b;">
                <?= $pending ?>
            </div>
            <div style="font-size:13px; color:#777;">
                Pending Reviews
            </div>
        </div>

    </div>

</div>


<a href="../admin/dashboard.php"
   style="position:fixed;
          bottom:35px;
          right:170px;
          background:linear-gradient(135deg, #0095f6, #6a5cff);
          color:white;
          padding:12px 18px;
          border-radius:30px;
          text-decoration:none;
          font-size:14px;
          font-weight:600;
          box-shadow:0 6px 18px rgba(0,0,0,0.2);
          transition:0.2s ease;
          display:flex;
          align-items:center;
          gap:6px;"

   onmouseover="this.style.transform='translateY(-2px)'"
   onmouseout="this.style.transform='translateY(0)'">

    Get Started <span style="font-size:16px;">→</span>
</a>

</body>
</html>