<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");
if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

$sql = "SELECT * FROM students";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>View Students</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

        <?php include("../includes/navbar.php"); ?>

    <div style="max-width:900px; margin:50px auto; padding:0 20px;">

        <h2 style="font-size:24px; color:#333; margin-bottom:24px; text-align:center;">Student List</h2>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">

            <a href="dashboard.php"
                style="display:inline-block; padding:9px 18px; background:white; border:1px solid #dbdbdb; border-radius:8px; color:#333; font-size:14px; font-weight:bold; text-decoration:none;"
                onmouseover="this.style.borderColor='#0095f6'; this.style.color='#0095f6';"
                onmouseout="this.style.borderColor='#dbdbdb'; this.style.color='#333';">
                ← Back to Dashboard
            </a>

            <a href="addStudent.php"
                style="display:inline-block; padding:9px 18px; background:#0095f6; border:1px solid #0095f6; border-radius:8px; color:white; font-size:14px; font-weight:bold; text-decoration:none;"
                onmouseover="this.style.backgroundColor='#1877f2'; this.style.borderColor='#1877f2';"
                onmouseout="this.style.backgroundColor='#0095f6'; this.style.borderColor='#0095f6';">
                + Add New Student
            </a>

        </div>

        <div
            style="background:white; border:1px solid #dbdbdb; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background-color:#0095f6;">
                        <th style="padding:14px 18px; text-align:left; color:white; font-size:14px;">Student ID</th>
                        <th style="padding:14px 18px; text-align:left; color:white; font-size:14px;">Name</th>
                        <th style="padding:14px 18px; text-align:left; color:white; font-size:14px;">Programme</th>
                        <th style="padding:14px 18px; text-align:center; color:white; font-size:14px;">Actions</th>
                    </tr>
                </thead>
                <tbody>

                                    <?php
                                    if ($result->num_rows > 0) {
                                        $i = 0;
                                        while ($row = $result->fetch_assoc()) {
                                            $bg = ($i % 2 == 0) ? "#ffffff" : "#f9f9f9";
                                            echo "
            <tr style='background-color:{$bg};'>
                    <td style='padding:13px 18px; font-size:14px; color:#333; border-bottom:1px solid #eee;'>{$row['student_id']}</td>
                    <td style='padding:13px 18px; font-size:14px; color:#333; border-bottom:1px solid #eee;'>{$row['student_name']}</td>
                    <td style='padding:13px 18px; font-size:14px; color:#333; border-bottom:1px solid #eee;'>{$row['programme']}</td>
                    <td style='padding:13px 18px; font-size:14px; color:#333; border-bottom:1px solid #eee;'>
                        <a href='editStudent.php?id={$row['student_id']} 'style='display:inline-block; padding:6px 14px; background:#0095f6; color:white; border-radius:6px; font-size:13px; font-weight:bold; text-decoration:none; margin-right:6px;'>
                        Edit
                        </a>|
                        <a href= 'deleteStudent.php?id={$row['student_id']}'
                        onclick=\"return confirm('Are you sure you want to delete this student?')\"
                        style='display:inline-block; padding:6px 14px; background:#ed4956; color:white; border-radius:6px; font-size:13px; font-weight:bold; text-decoration:none;'>
                        Delete
                        </a>
                    </td>
                  </tr>";
                                            $i++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='4' style='padding:20px; text-align:center; color:#888; font-size:14px;'>No students found</td></tr>";
                                    }
                                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>