<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");
include("../includes/navbar.php");

if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

// GET values
$search = $_GET['search'] ?? "";
$programme = $_GET['programme'] ?? "";

// get programme list
$programmes = $conn->query("SELECT DISTINCT programme FROM students");

// base SQL
$sql = "SELECT students.student_id, students.student_name,
               assessments.final_mark, assessments.comment
        FROM assessments
        JOIN students ON assessments.student_id = students.student_id
        WHERE (students.student_id LIKE '%$search%'
        OR students.student_name LIKE '%$search%')";

// add programme filter
if (!empty($programme)) {
    $sql .= " AND students.programme = '$programme'";
}

$result = $conn->query($sql);
?>

<h2>Internship Results</h2>

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

<table border="1">
<tr>
    <th>Student ID</th>
    <th>Name</th>
    <th>Final Mark</th>
    <th>Comment</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?php echo $row['student_id']; ?></td>
    <td><?php echo $row['student_name']; ?></td>
    <td><?php echo $row['final_mark']; ?></td>
    <td><?php echo $row['comment']; ?></td>
</tr>
<?php } ?>
</table>