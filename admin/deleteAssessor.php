<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");

if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

$id = intval($_GET['id']);

// Prevent deleting yourself
if ($id == $_SESSION['user_id']) {
    die("You cannot delete your own account.");
}

$conn->query("DELETE FROM users WHERE user_id = $id AND role = 'assessor'");

header("Location: viewAssessors.php");
exit();
?>