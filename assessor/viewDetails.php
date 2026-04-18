<?php
include("../includes/auth.php");
include("../config/db.php");

if ($_SESSION['role'] != 'assessor') {
    die("Access denied");
}

$assessor_id = $_SESSION['user_id'];
$id = $_GET['id'];


$sql = "SELECT students.student_name, assessments.*
        FROM assessments
        JOIN students ON assessments.student_id = students.student_id
        JOIN internships ON students.student_id = internships.student_id
        WHERE assessments.student_id = '$id'
        AND internships.assessor_id = '$assessor_id'";

$result = $conn->query($sql);
$row = $result->fetch_assoc();

// extra protection
if (!$row) {
    die("Access denied");
}

$mark = $row['final_mark'];
if ($mark >= 80) {
    $markColor = "#27ae60";
} elseif ($mark >= 60) {
    $markColor = "#f39c12";
} else {
    $markColor = "#ed4956";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Assessment Details</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <?php include("../includes/navbar.php"); ?>

    <div style="max-width:650px; margin:50px auto; padding:0 20px;">

        <h2 style="font-size:24px; color:#333; margin-bottom:24px; text-align:center;">Assessment Details</h2>

        <a href="viewResults.php"
            style="display:inline-block; margin-bottom:20px; padding:9px 18px; background:white; border:1px solid #dbdbdb; border-radius:8px; color:#333; font-size:14px; font-weight:bold; text-decoration:none;"
            onmouseover="this.style.borderColor='#0095f6'; this.style.color='#0095f6';"
            onmouseout="this.style.borderColor='#dbdbdb'; this.style.color='#333';">
            ⬅ Back to Results
        </a>


        <div
            style="background:#f0f7ff; border:1px solid #cce0ff; border-radius:8px; padding:16px 20px; margin-bottom:20px; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <div style="font-size:13px; color:#888;">Student Name</div>
                <div style="font-size:18px; font-weight:bold; color:#333;"><?= $row['student_name'] ?></div>
            </div>
            <div style="text-align:right;">
                <div style="font-size:13px; color:#888;">Final Mark</div>
                <div style="font-size:28px; font-weight:bold; color:<?= $markColor ?>;"><?= $row['final_mark'] ?></div>
            </div>
        </div>

        <div
            style="background:white; border:1px solid #dbdbdb; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.06); margin-bottom:20px;">

            <div style="background:#0095f6; padding:12px 18px;">
                <span style="color:white; font-size:15px; font-weight:bold;">Marks Breakdown</span>
            </div>


            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background-color:#f9f9f9;">
                        <th
                            style="padding:12px 18px; text-align:left; color:#888; font-size:13px; border-bottom:1px solid #eee;">
                            Criteria</th>
                        <th
                            style="padding:12px 18px; text-align:left; color:#888; font-size:13px; border-bottom:1px solid #eee;">
                            Weight</th>
                        <th
                            style="padding:12px 18px; text-align:left; color:#888; font-size:13px; border-bottom:1px solid #eee;">
                            Marks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom:1px solid #eee;">
                        <td style="padding:12px 18px; font-size:14px; color:#333;">Undertaking Tasks</td>
                        <td style="padding:12px 18px; font-size:13px; color:#888;">10%</td>
                        <td
                            style="padding:12px 18px; font-size:14px; font-weight:bold; color:#0095f6; text-align:center;">
                            <?= $row['undertaking_tasks'] ?>
                        </td>
                    </tr>
                    <tr style="background:#f9f9f9; border-bottom:1px solid #eee;">
                        <td style="padding:12px 18px; font-size:14px; color:#333;">Health Requirements</td>
                        <td style="padding:12px 18px; font-size:13px; color:#888;">10%</td>
                        <td
                            style="padding:12px 18px; font-size:14px; font-weight:bold; color:#0095f6; text-align:center;">
                            <?= $row['health_requirements'] ?>
                        </td>
                    </tr>
                    <tr style="border-bottom:1px solid #eee;">
                        <td style="padding:12px 18px; font-size:14px; color:#333;">Theoretical Knowledge</td>
                        <td style="padding:12px 18px; font-size:13px; color:#888;">10%</td>
                        <td
                            style="padding:12px 18px; font-size:14px; font-weight:bold; color:#0095f6; text-align:center;">
                            <?= $row['theoretical_knowledge'] ?>
                        </td>
                    </tr>
                    <tr style="background:#f9f9f9; border-bottom:1px solid #eee;">
                        <td style="padding:12px 18px; font-size:14px; color:#333;">Report Presentation</td>
                        <td style="padding:12px 18px; font-size:13px; color:#888;">15%</td>
                        <td
                            style="padding:12px 18px; font-size:14px; font-weight:bold; color:#0095f6; text-align:center;">
                            <?= $row['report_presentation'] ?>
                        </td>
                    </tr>
                    <tr style="border-bottom:1px solid #eee;">
                        <td style="padding:12px 18px; font-size:14px; color:#333;">Language Clarity</td>
                        <td style="padding:12px 18px; font-size:13px; color:#888;">10%</td>
                        <td
                            style="padding:12px 18px; font-size:14px; font-weight:bold; color:#0095f6; text-align:center;">
                            <?= $row['language_clarity'] ?>
                        </td>
                    </tr>
                    <tr style="background:#f9f9f9; border-bottom:1px solid #eee;">
                        <td style="padding:12px 18px; font-size:14px; color:#333;">Learning Activities</td>
                        <td style="padding:12px 18px; font-size:13px; color:#888;">15%</td>
                        <td
                            style="padding:12px 18px; font-size:14px; font-weight:bold; color:#0095f6; text-align:center;">
                            <?= $row['learning_activities'] ?>
                        </td>
                    </tr>
                    <tr style="border-bottom:1px solid #eee;">
                        <td style="padding:12px 18px; font-size:14px; color:#333;">Project Management</td>
                        <td style="padding:12px 18px; font-size:13px; color:#888;">15%</td>
                        <td
                            style="padding:12px 18px; font-size:14px; font-weight:bold; color:#0095f6; text-align:center;">
                            <?= $row['project_management'] ?>
                        </td>
                    </tr>
                    <tr style="background:#f9f9f9; border-bottom:1px solid #eee;">
                        <td style="padding:12px 18px; font-size:14px; color:#333;">Time Management</td>
                        <td style="padding:12px 18px; font-size:13px; color:#888;">15%</td>
                        <td
                            style="padding:12px 18px; font-size:14px; font-weight:bold; color:#0095f6; text-align:center;">
                            <?= $row['time_management'] ?>
                        </td>
                    </tr>

                    <tr style="background:#f0f7ff;">
                        <td style="padding:14px 18px; font-size:15px; font-weight:bold; color:#333;">Final Mark</td>
                        <td style="padding:14px 18px; font-size:13px; color:#888;">100%</td>
                        <td
                            style="padding:14px 18px; font-size:18px; font-weight:bold; color:<?= $markColor ?>; text-align:center;">
                            <?= $row['final_mark'] ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div
            style="background:white; border:1px solid #dbdbdb; border-radius:12px; padding:20px 24px; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
            <div style="font-size:14px; font-weight:bold; color:#333; margin-bottom:8px;">Comment</div>
            <div style="font-size:14px; color:#555; line-height:1.6;">
                <?= $row['comment'] ? $row['comment'] : "<span style='color:#aaa;'>No comment provided</span>" ?>
            </div>
        </div>

    </div>

</body>

</html>