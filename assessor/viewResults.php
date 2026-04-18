<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");

if ($_SESSION['role'] != 'assessor') {
    die("Access denied");
}

$assessor_id = $_SESSION['user_id'];


$search = $_GET['search'] ?? "";
$programme = $_GET['programme'] ?? "";




$sql = "SELECT students.student_id, students.student_name,
               students.programme, assessments.final_mark
        FROM assessments
        JOIN students ON assessments.student_id = students.student_id
        JOIN internships ON students.student_id = internships.student_id
        WHERE internships.assessor_id = '$assessor_id'
        AND (students.student_id LIKE '%$search%'
        OR students.student_name LIKE '%$search%')";


if (!empty($programme)) {
    $sql .= " AND students.programme = '$programme'";
}

$result = $conn->query($sql);
$programmes = $conn->query("SELECT DISTINCT programme FROM students");
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Students Results</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <?php include("../includes/navbar.php"); ?>

    <div style="max-width:1000px; margin:50px auto; padding:0 20px;">

        <h2 style="font-size:24px; color:#333; margin-bottom:24px; text-align:center;">My Students Results</h2>


        <a href="dashboard.php"
            style="display:inline-block; margin-bottom:20px; padding:9px 18px; background:white; border:1px solid #dbdbdb; border-radius:8px; color:#333; font-size:14px; font-weight:bold; text-decoration:none;"
            onmouseover="this.style.borderColor='#0095f6'; this.style.color='#0095f6';"
            onmouseout="this.style.borderColor='#dbdbdb'; this.style.color='#333';">
            ← Back to Dashboard
        </a>

        <div
            style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; gap:10px; flex-wrap:wrap;">

            <form method="GET" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                <input type="text" name="search" placeholder="Search by ID or Name" value="<?php echo $search; ?>"
                    style="padding:10px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:14px; background:#fafafa; width:220px;">

                <select name="programme"
                    style="padding:10px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:14px; background:#fafafa; cursor:pointer;">
                    <option value="">All Programmes</option>

                    <?php while ($p = $programmes->fetch_assoc()) { ?>
                        <option value="<?php echo $p['programme']; ?>" <?php if ($programme == $p['programme'])
                               echo "selected"; ?>>
                            <?php echo $p['programme']; ?>
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
            <form method="GET" action="exportResults.php">
                <input type="hidden" name="search" value="<?php echo $search; ?>">
                <input type="hidden" name="programme" value="<?php echo $programme; ?>">
                <button type="submit"
                    style="padding:10px 18px; background:white; border:1px solid #dbdbdb; border-radius:8px; font-size:14px; font-weight:bold; color:#333; cursor:pointer;"
                    onmouseover="this.style.borderColor='#0095f6'; this.style.color='#0095f6';"
                    onmouseout="this.style.borderColor='#dbdbdb'; this.style.color='#333';">
                    ⬇ Export to Excel
                </button>
            </form>

        </div>

        <div
            style="background:white; border:1px solid #dbdbdb; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background-color:#0095f6;">
                        <th style="padding:14px 18px; text-align:left; color:white; font-size:14px;">Student ID</th>
                        <th style="padding:14px 18px; text-align:left; color:white; font-size:14px;">Student</th>
                        <th style="padding:14px 18px; text-align:left; color:white; font-size:14px;">Programme</th>
                        <th style="padding:14px 18px; text-align:left; color:white; font-size:14px;">Final Mark</th>
                        <th style="padding:14px 18px; text-align:left; color:white; font-size:14px;">Details</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $i = 0;
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $bg = ($i % 2 == 0) ? "#ffffff" : "#f9f9f9";

                            $mark = $row['final_mark'];
                            if ($mark >= 80) {
                                $markColor = "#27ae60";
                            } elseif ($mark >= 60) {
                                $markColor = "#f39c12";
                            } else {
                                $markColor = "#ed4956";
                            }

                            echo "
                    <tr style='background-color:{$bg};'>
                        <td style='padding:13px 18px; font-size:14px; color:#333; border-bottom:1px solid #eee;'>{$row['student_id']}</td>
                        <td style='padding:13px 18px; font-size:14px; color:#333; border-bottom:1px solid #eee;'>{$row['student_name']}</td>
                        <td style='padding:13px 18px; font-size:14px; color:#333; border-bottom:1px solid #eee;'>{$row['programme']}</td>
                        <td style='padding:13px 18px; font-size:14px; font-weight:bold; color:{$markColor}; text-align:center; border-bottom:1px solid #eee;'>{$row['final_mark']}</td>
                        <td style='padding:13px 18px; text-align:center; border-bottom:1px solid #eee;'>
                            <a href='viewDetails.php?id={$row['student_id']}'style='display:inline-block; padding:6px 14px; background:#0095f6; color:white; border-radius:6px; font-size:13px; font-weight:bold; text-decoration:none;'
                                onmouseover=\"this.style.backgroundColor='#1877f2'\"
                                onmouseout=\"this.style.backgroundColor='#0095f6'\">
                                View
                            </a>
                        </td>
                    </tr>";
                            $i++;
                        }
                    } else {
                        echo "<tr><td colspan='5' style='padding:20px; text-align:center; color:#888; font-size:14px;'>No results found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>