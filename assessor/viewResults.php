<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");
include("../includes/navbar.php");

if ($_SESSION['role'] != 'assessor') {
    die("Access denied");
}

$assessor_id = $_SESSION['user_id'];


$search = $_GET['search'] ?? "";
$programme = $_GET['programme'] ?? "";


$programmes = $conn->query("SELECT DISTINCT programme FROM students");


$sql = "SELECT students.student_id, students.student_name,
               assessments.final_mark
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
?>

<h2>My Students Results</h2>
<form method="GET">
    <input type="text" name="search" placeholder="Search by ID or Name"
           value="<?php echo $search; ?>">

    <select name="programme">
        <option value="">All Programmes</option>

        <?php while($p = $programmes->fetch_assoc()) { ?>
            <option value="<?php echo $p['programme']; ?>"
                <?php if ($programme == $p['programme']) echo "selected"; ?>>
                <?php echo $p['programme']; ?>
            </option>
        <?php } ?>
    </select>

    <button type="submit">Filter</button>
</form>
<form method="GET" action="exportResults.php">
    <input type="hidden" name="search" value="<?php echo $search; ?>">
    <input type="hidden" name="programme" value="<?php echo $programme; ?>">
    <button type="submit">⬇ Export to Excel</button>
</form>

<table border="1">
<tr>
    <th>ID</th>
    <th>Student</th>
    <th>Final Mark</th>
    <th>Details</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?php echo $row['student_id']; ?></td>
    <td><?php echo $row['student_name']; ?></td>
    <td><?php echo $row['final_mark']; ?></td>
    <td>
        <a href="viewDetails.php?id=<?php echo $row['student_id']; ?>">
            <button>View</button>
        </a>
    </td>
</tr>
<?php } ?>
</table>