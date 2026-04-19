<?php
session_start();
include("config/db.php");

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

if ($role == 'admin') {
    $conn->query("UPDATE assessments SET is_read = 1");
} else {
    $conn->query("UPDATE internships SET is_read = 1 WHERE assessor_id = '$user_id'");
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>