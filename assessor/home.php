<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");

if ($_SESSION['role'] != 'assessor') {
    die("Access denied");
}

$assessor_id = $_SESSION['user_id'];

// Assessed students (unique)
$total_students = $conn->query("SELECT COUNT(*) AS c FROM internships WHERE assessor_id='$assessor_id'")->fetch_assoc()['c'];
$total_assessed = $conn->query("SELECT COUNT(DISTINCT a.student_id) AS c FROM assessments a JOIN internships i ON a.internship_id = i.internship_id WHERE i.assessor_id='$assessor_id'")->fetch_assoc()['c'];
$pending = $conn->query("
    SELECT COUNT(*) AS c
    FROM internships i
    WHERE NOT EXISTS (
        SELECT 1
        FROM assessments a
        WHERE a.internship_id = i.internship_id
    )
")->fetch_assoc()['c'];

?>

<!DOCTYPE html>
<html>

<head>
    <title>Assessor Home</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <?php include("../includes/navbar.php"); ?>

    <div style="max-width:1000px; margin:40px auto; padding:0 20px;">

        <!-- Welcome -->
        <div style="background: linear-gradient(135deg, #6a5cff, #00c6ff);
                color:white;
                padding:30px;
                border-radius:14px;
                margin-bottom:25px;
                box-shadow:0 6px 18px rgba(0,0,0,0.15);">

            <h1 style="margin:0; font-size:28px;">
                Welcome back, Assessor 👋
            </h1>

            <p style="margin-top:8px; font-size:15px; opacity:0.9;">
                Here’s your assessment progress for today.
            </p>

            <div style="margin-top:12px; font-size:14px; opacity:0.95;">
                You have assessed <b><?= $total_assessed ?></b> students,
                completed <b><?= $total_assessed ?></b> assessments,
                with <b><?= $pending ?></b> pending reviews.
            </div>

        </div>

        <h2 style="font-size:18px; color:#333; margin-bottom:14px;">
            Your Overview
        </h2>

        <!-- STATS GRID -->
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px;">

            <!-- Assessed Students -->
            <div style="background:white; border-radius:12px; padding:22px;
                    box-shadow:0 2px 8px rgba(0,0,0,0.06);
                    border-left:5px solid #0095f6;">

                <div style="font-size:30px; font-weight:bold; color:#0095f6;">
                    <?= $total_assessed ?>
                </div>
                <div style="font-size:13px; color:#777;">
                    Assessed Students
                </div>
            </div>

            <!-- Completed -->
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

            <!-- Pending -->
            <div style="background:white; border-radius:12px; padding:22px;
                    box-shadow:0 2px 8px rgba(0,0,0,0.06);
                    border-left:5px solid #ff6b6b;">

                <div style="font-size:30px; font-weight:bold; color:#ff6b6b;">
                    <?= $pending ?>
                </div>
                <div style="font-size:13px; color:#777;">
                    Pending Assessments
                </div>
            </div>

        </div>

    </div>
    <a href="../assessor/dashboard.php" style="position:fixed;
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
          gap:6px;" onmouseover="this.style.transform='translateY(-2px)'"
        onmouseout="this.style.transform='translateY(0)'">

        Get Started <span style="font-size:16px;">→</span>
    </a>


</body>

</html>