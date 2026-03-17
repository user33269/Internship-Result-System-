<?php
session_start();
include("../config/db.php");

// restrict access
if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

// get student ID
$id = $_GET['id'];

// fetch student data
$sql = "SELECT * FROM students WHERE student_id='$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST['student_id'];
    $name = $_POST['student_name'];
    $programme = $_POST['programme'];

    $sql = "UPDATE students
            SET student_name='$name', programme='$programme'
            WHERE student_id='$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: viewStudents.php");
    } else {
        echo "Error updating: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
</head>

<body>

<h2>Edit Student</h2>

<form method="POST">
    <p><strong>Student ID:</strong> <?php echo $row['student_id']; ?></p>

   
    <input type="hidden" name="student_id"
           value="<?php echo $row['student_id']; ?>">

    Name:
    <input type="text" name="student_name"
           value="<?php echo $row['student_name']; ?>"><br><br>

    Programme:
    <input type="text" name="programme"
           value="<?php echo $row['programme']; ?>"><br><br>

    <button type="submit">Update Student</button>
</form>

</body>
</html>