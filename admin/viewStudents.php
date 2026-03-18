<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");
include("../includes/navbar.php");
if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

$sql = "SELECT * FROM students";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>
</head>

<body>

<h2>Student List</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>Student ID</th>
        <th>Name</th>
        <th>Programme</th>
        <th>Action </th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['student_id']}</td>
                    <td>{$row['student_name']}</td>
                    <td>{$row['programme']}</td>
                    <td>
                        <a href='editStudent.php?id={$row['student_id']}'>Edit</a>|
                        <a href= 'deleteStudent.php?id={$row['student_id']}'
                        onclick=\"return confirm('Are you sure you want to delete this student?')\">
                    Delete</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No students found</td></tr>";
    }
    ?>

</table>

</body>
</html>