<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");

if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

// GET values
$search = $_GET['search'] ?? "";
$year = $_GET['year'] ?? "";

// base SQL
$sql = "SELECT 
            students.student_id, 
            students.student_name,
            internships.semester,
            internships.year,
            assessments.final_mark, 
            assessments.comment
        FROM assessments
        JOIN students ON assessments.student_id = students.student_id
        JOIN internships ON assessments.internship_id = internships.internship_id
        WHERE (students.student_id LIKE '%$search%'
        OR students.student_name LIKE '%$search%')";

// year filter
if (!empty($year)) {
    $sql .= " AND internships.year = '$year'";
}

$result = $conn->query($sql);

if (!$result) {
    die("SQL ERROR: " . $conn->error);
}

// year list
$years = $conn->query("SELECT DISTINCT year FROM internships ORDER BY year DESC");
?>

<!DOCTYPE html>
<html>

<head>
    <title>View Results</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<?php include("../includes/navbar.php"); ?>

<div style="max-width:1000px; margin:50px auto; padding:0 20px;">

    <h2 style="font-size:24px; color:#333; margin-bottom:24px; text-align:center;">
        Internship Results
    </h2>

    <a href="dashboard.php"
        style="display:inline-block; margin-bottom:20px; padding:9px 18px; background:white; border:1px solid #dbdbdb; border-radius:8px; color:#333; font-size:14px; font-weight:bold; text-decoration:none;">
        ← Back to Dashboard
    </a>

    <!-- FILTER -->
    <div style="display:flex; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:10px;">

        <form method="GET" style="display:flex; gap:10px; flex-wrap:wrap;">

            <input type="text" name="search" placeholder="Search ID or Name"
                value="<?php echo $search; ?>"
                style="padding:10px 14px; border:1px solid #dbdbdb; border-radius:8px; width:220px;">

            <!-- YEAR FILTER -->
            <select name="year"
                style="padding:10px 14px; border:1px solid #dbdbdb; border-radius:8px; width:150px;">
                <option value="">All Years</option>
                <?php while ($y = $years->fetch_assoc()) { ?>
                    <option value="<?php echo $y['year']; ?>"
                        <?php if ($year == $y['year']) echo "selected"; ?>>
                        <?php echo $y['year']; ?>
                    </option>
                <?php } ?>
            </select>

            <button type="submit"
                style="padding:10px 18px; background:#0095f6; color:white; border:none; border-radius:8px; font-weight:bold;">
                Filter
            </button>

        </form>

    </div>

    <!-- TABLE -->
    <div style="background:white; border:1px solid #dbdbdb; border-radius:12px; overflow:hidden;">

        <table style="width:100%; border-collapse:collapse;">

            <thead>
                <tr style="background:#0095f6; color:white;">
                    <th style="padding:14px;">Student ID</th>
                    <th style="padding:14px;">Name</th>
                    <th style="padding:14px;">Semester</th>
                    <th style="padding:14px;">Year</th>
                    <th style="padding:14px;">Final Mark</th>
                    <th style="padding:14px;">Comment</th>
                </tr>
            </thead>

            <tbody>

            <?php
            $i = 0;

            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {

                    $bg = ($i % 2 == 0) ? "#fff" : "#f9f9f9";

                    $mark = $row['final_mark'];

                    if ($mark >= 80) $color = "#27ae60";
                    elseif ($mark >= 60) $color = "#f39c12";
                    else $color = "#e74c3c";

                    echo "
                    <tr style='background:$bg;'>
                        <td style='padding:12px; border-bottom:1px solid #eee;'>{$row['student_id']}</td>
                        <td style='padding:12px; border-bottom:1px solid #eee;'>{$row['student_name']}</td>
                        <td style='padding:12px; border-bottom:1px solid #eee;'>{$row['semester']}</td>
                        <td style='padding:12px; border-bottom:1px solid #eee; font-weight:bold;'>{$row['year']}</td>
                        <td style='padding:12px; border-bottom:1px solid #eee; color:$color; font-weight:bold; text-align:center;'>
                            {$row['final_mark']}
                        </td>
                        <td style='padding:12px; border-bottom:1px solid #eee;'>{$row['comment']}</td>
                    </tr>";
                    $i++;
                }

            } else {
                echo "<tr><td colspan='6' style='padding:20px; text-align:center;'>No results found</td></tr>";
            }
            ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>