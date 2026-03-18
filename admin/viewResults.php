<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");
include("../includes/navbar.php");
if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

$search = "";

if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

$sql = "SELECT students.student_id, students.student_name,
               assessments.final_mark, assessments.comment
        FROM assessments
        JOIN students ON assessments.student_id = students.student_id
        WHERE students.student_id LIKE '%$search%'
        OR students.student_name LIKE '%$search%'";
$result = $conn->query($sql);
?>

<h2>Internship Results</h2>
<form method="GET">
    <input type="text" name="search" placeholder="Search by ID or Name">
    <button type="submit">Search</button>
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