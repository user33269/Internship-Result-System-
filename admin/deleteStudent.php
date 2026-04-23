<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");

// restrict access
if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

if (isset($_GET['id'])) {

    $id = $conn->real_escape_string($_GET['id']);

    $conn->query("DELETE FROM assessments WHERE student_id='$id'");
    $conn->query("DELETE FROM internships WHERE student_id='$id'");

    $sql = "DELETE FROM students WHERE student_id='$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: viewStudents.php");
    } else {
        echo "Error deleting: " . $conn->error;
    }
}
?>