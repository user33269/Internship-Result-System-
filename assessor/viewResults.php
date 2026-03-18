<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != 'assessor') {
    die("Access denied");
}

$assessor_id = $_SESSION['user_id'];

$sql = "SELECT students.student_name, assessments.final_mark
        FROM assessments
        JOIN students ON assessments.student_id = students.student_id
        JOIN internships ON students.student_id = internships.student_id
        WHERE internships.assessor_id = '$assessor_id'";

$result = $conn->query($sql);
?>

<h2>My Students Results</h2>

<table border="1">
<tr>
    <th>Student</th>
    <th>Final Mark</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?php echo $row['student_name']; ?></td>
    <td><?php echo $row['final_mark']; ?></td>
</tr>
<?php } ?>
</table>