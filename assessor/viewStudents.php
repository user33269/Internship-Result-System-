<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");

if ($_SESSION['role'] != 'assessor') {
    die("Access denied");
}

$assessor_id = $_SESSION['user_id'];

// FILTER
$year = $_GET['year'] ?? "";

// BASE SQL
$sql = "SELECT 
            students.student_id, 
            students.student_name, 
            companies.company_name,
            internships.semester,
            internships.year,
            internships.internship_id,
            EXISTS (
                SELECT 1 
                FROM assessments a 
                WHERE a.internship_id = internships.internship_id
                AND a.assessor_id = '$assessor_id'
            ) AS is_assessed
        FROM internships
        JOIN students 
            ON internships.student_id = students.student_id
        LEFT JOIN companies 
            ON internships.company_id = companies.company_id
        WHERE internships.assessor_id = '$assessor_id'";

// APPLY FILTER
if (!empty($year)) {
    $sql .= " AND internships.year = '$year'";
}

$sql .= " ORDER BY is_assessed ASC, students.student_name ASC";

$result = $conn->query($sql);

if (!$result) {
    die("SQL ERROR: " . $conn->error);
}

// GET DISTINCT YEARS FOR FILTER DROPDOWN
$years = $conn->query("SELECT DISTINCT year FROM internships WHERE year IS NOT NULL ORDER BY year DESC");

$total_students = 0;
$assessed_students = 0;
$students = [];

while ($row = $result->fetch_assoc()) {
    $students[] = $row;
    $total_students++;

    if ($row['is_assessed']) {
        $assessed_students++;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Students</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <?php include("../includes/navbar.php"); ?>

    <div style="max-width:1000px; margin:50px auto; padding:0 20px;">

        <h2 style="font-size:24px; color:#333; margin-bottom:24px; text-align:center;">
            My Students👥
        </h2>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">

            <!-- LEFT: Back button -->
            <a href="dashboard.php"
                style="display:inline-block; padding:9px 18px; background:white; border:1px solid #dbdbdb; border-radius:8px; color:#333; font-size:14px; font-weight:bold; text-decoration:none;"
                onmouseover="this.style.borderColor='#0095f6'; this.style.color='#0095f6';"
                onmouseout="this.style.borderColor='#dbdbdb'; this.style.color='#333';">
                ← Back to Dashboard
            </a>

            <!-- RIGHT: Filter -->
            <form method="GET" style="display:flex; gap:10px; align-items:center;">

                <select name="year"
                    style="padding:10px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:14px; background:#fafafa;">

                    <option value="">All Years</option>

                    <?php while ($y = $years->fetch_assoc()) { ?>
                        <option value="<?php echo $y['year']; ?>" <?php if ($year == $y['year'])
                               echo "selected"; ?>>
                            <?php echo $y['year']; ?>
                        </option>
                    <?php } ?>

                </select>

                <button type="submit"
                    style="padding:10px 18px; background:#0095f6; color:white; border:none; border-radius:8px; font-size:14px; font-weight:bold; cursor:pointer;"
                    onmouseover="this.style.backgroundColor='#1877f2'"
                    onmouseout="this.style.backgroundColor='#0095f6'">
                    Filter
                </button>

            </form>

        </div>

        <div
            style="background:white; border:1px solid #dbdbdb; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.06);">

            <?php
            $progress = ($total_students > 0)
                ? round(($assessed_students / $total_students) * 100)
                : 0;
            ?>

            <div style="margin:15px 0;">
                <span style="padding:6px 12px; font-size:13px; color:#333;">
                    Assessment Progress: <b><?= $assessed_students ?>/<?= $total_students ?></b>
                </span>
            </div>

            <table style="width:100%; border-collapse:collapse;">

                <thead>
                    <tr style="background-color:#0095f6;">
                        <th style="padding:14px 18px; color:white;">Student Name</th>
                        <th style="padding:14px 18px; color:white;">Company</th>
                        <th style="padding:14px 18px; color:white;">Semester</th>
                        <th style="padding:14px 18px; color:white;">Year</th>
                        <th style="padding:14px 18px; color:white;">Status</th>
                        <th style="padding:14px 18px; color:white;">Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    $i = 0;

                    if (count($students) > 0) {

                        foreach ($students as $row) {

                            $bg = ($i % 2 == 0) ? "#ffffff" : "#f9f9f9";

                            if ($row['is_assessed']) {
                                $statusText = "Assessed";
                                $statusColor = "#28a745";
                            } else {
                                $statusText = "Not yet assessed";
                                $statusColor = "#ffc107";
                            }

                            echo "
                    <tr style='background-color:{$bg};'>

                        <td style='padding:13px 18px;'>{$row['student_name']}</td>

                        <td style='padding:13px 18px;'>
                            " . ($row['company_name'] ?? '-') . "
                        </td>

                        <td style='padding:13px 18px;'>
                            " . ($row['semester'] ?? '-') . "
                        </td>

                        <td style='padding:13px 18px;'>
                            " . ($row['year'] ?? '-') . "
                        </td>

                        <td style='padding:13px 18px;'>
                            <span style='padding:4px 10px; border-radius:20px; font-size:12px; font-weight:bold; color:white; background:{$statusColor};'>
                                {$statusText}
                            </span>
                        </td>

                        <td style='padding:13px 18px;'>
    " . ($row['is_assessed']
                                ? "<a href='assessStudent.php?id={$row['student_id']}&internship_id={$row['internship_id']}'
                style='padding:6px 14px; background:#fd7e14; color:white; border-radius:6px; text-decoration:none;'
                onclick=\"return confirm('Are you sure you want to reassess this student? The previous marks will be replaced.');\">
                Reassess
            </a>"
                                : "<a href='assessStudent.php?id={$row['student_id']}&internship_id={$row['internship_id']}'
                style='padding:6px 14px; background:#0095f6; color:white; border-radius:6px; text-decoration:none;'>
                Assess
            </a>"
                            ) . "
</td>

                    </tr>
                    ";

                            $i++;
                        }

                    } else {
                        echo "
                <tr>
                    <td colspan='6' style='padding:20px; text-align:center; color:#888;'>
                        No students assigned yet
                    </td>
                </tr>";
                    }
                    ?>

                </tbody>

            </table>

        </div>
    </div>

</body>

</html>