<?php
include("../includes/auth.php");
include("../config/db.php");

$sql = "SELECT students.student_name, users.username, internships.company_name
        FROM internships
        JOIN students ON internships.student_id = students.student_id
        JOIN users ON internships.assessor_id = users.user_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Internship List</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

        <?php include("../includes/navbar.php"); ?>

    <div style="max-width:900px; margin:50px auto; padding:0 20px;">

        <h2 style="font-size:24px; color:#333; margin-bottom:24px; text-align:center;">Internship List</h2>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">

            <a href="dashboard.php"
                style="display:inline-block; padding:9px 18px; background:white; border:1px solid #dbdbdb; border-radius:8px; color:#333; font-size:14px; font-weight:bold; text-decoration:none;"
                onmouseover="this.style.borderColor='#0095f6'; this.style.color='#0095f6';"
                onmouseout="this.style.borderColor='#dbdbdb'; this.style.color='#333';">
                ← Back to Dashboard
            </a>

            <a href="assignInternship.php"
                style="display:inline-block; padding:9px 18px; background:#0095f6; border:1px solid #0095f6; border-radius:8px; color:white; font-size:14px; font-weight:bold; text-decoration:none;"
                onmouseover="this.style.backgroundColor='#1877f2'; this.style.borderColor='#1877f2';"
                onmouseout="this.style.backgroundColor='#0095f6'; this.style.borderColor='#0095f6';">
                + Assign Internship
            </a>

        </div>

        <div
            style="background:white; border:1px solid #dbdbdb; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background-color:#0095f6;">
                        <th style="padding:14px 18px; text-align:left; color:white; font-size:14px;">Student</th>
                        <th style="padding:14px 18px; text-align:left; color:white; font-size:14px;">Assessor</th>
                        <th style="padding:14px 18px; text-align:left; color:white; font-size:14px;">Company</th>
                    </tr>
                </thead>
                <tbody>

                                        <?php
                                        $i = 0;
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $bg = ($i % 2 == 0) ? "#ffffff" : "#f9f9f9";
                                                echo "
        <tr style='background-color:{$bg};'>
            <td style='padding:13px 18px; font-size:14px; color:#333; border-bottom:1px solid #eee;'>{$row['student_name']}</td>
            <td style='padding:13px 18px; font-size:14px; color:#333; border-bottom:1px solid #eee;'>{$row['username']}</td>
            <td style='padding:13px 18px; font-size:14px; color:#333; border-bottom:1px solid #eee;'>{$row['company_name']}</td>
        </tr>";
                                                $i++;
                                            }
                                        } else {
                                            echo "<tr><td colspan='3' style='padding:20px; text-align:center; color:#888; font-size:14px;'>No internships found</td></tr>";
                                        }
                                        ?>
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>