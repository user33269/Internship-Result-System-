<?php
include("../includes/auth.php");
include("../config/db.php");
include("../includes/navbar.php");
$sql = "SELECT students.student_name, users.username, internships.company_name
        FROM internships
        JOIN students ON internships.student_id = students.student_id
        JOIN users ON internships.assessor_id = users.user_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<body>
<h2>Internship List</h2>

<table border="1">
<tr>
    <th>Student</th>
    <th>Assessor</th>
    <th>Company</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?php echo $row['student_name']; ?></td>
    <td><?php echo $row['username']; ?></td>
    <td><?php echo $row['company_name']; ?></td>
</tr>
<?php } ?>

</table></body></html>