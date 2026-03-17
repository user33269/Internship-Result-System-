<?php
session_start();
include("../config/db.php");

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
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['student_id']}</td>
                    <td>{$row['student_name']}</td>
                    <td>{$row['programme']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No students found</td></tr>";
    }
    ?>

</table>

</body>
</html>