<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");
include("../includes/navbar.php");

if ($_SESSION['role'] != 'assessor') {
    die("Access denied");
}

$assessor_id = $_SESSION['user_id'];


$sql = "SELECT students.student_id, students.student_name, internships.company_name
        FROM internships
        JOIN students ON internships.student_id = students.student_id
        WHERE internships.assessor_id = '$assessor_id'";

$result = $conn->query($sql);
?>

<h2>My Students</h2>

<table border="1">
<tr>
    <th>Student</th>
    <th>Company</th>
    <th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?php echo $row['student_name']; ?></td>
    <td><?php echo $row['company_name']; ?></td>
    <td>
        <a href="assessStudent.php?id=<?php echo $row['student_id']; ?>">
            Assess
        </a>
    </td>
</tr>
<?php } ?>
</table>