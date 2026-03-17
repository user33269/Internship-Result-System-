<?php
session_start();
include("../config/db.php");


if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST['student_id'];
    $name = $_POST['student_name'];
    $programme = $_POST['programme'];

    $sql = "INSERT INTO students (student_id, student_name, programme)
            VALUES ('$id', '$name', '$programme')";

    if ($conn->query($sql) === TRUE) {
        echo "Student added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
</head>

<body>

<h2>Add Student</h2>

<form method="POST">
    Student ID: <input type="text" name="student_id" required><br><br>
    Name: <input type="text" name="student_name" required><br><br>
    Programme: <input type="text" name="programme" required><br><br>

    <button type="submit">Add Student</button>
</form>

</body>
</html>