<?php
// start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// check if user is logged in
if (!isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit();
}
?>