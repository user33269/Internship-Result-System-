<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");
include("../includes/navbar.php");
// restrict access
if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $sql = "DELETE FROM students WHERE student_id='$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: viewStudents.php");
    } else {
        echo "Error deleting: " . $conn->error;
    }
}
?>