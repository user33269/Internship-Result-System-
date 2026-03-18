<?php

$role = $_SESSION['role'];


if ($role == 'admin') {
    $dashboard = "../admin/dashboard.php";
} else if ($role == 'assessor') {
    $dashboard = "../assessor/dashboard.php";
}
?>

<div style="margin-bottom:20px;">
    <a href="<?php echo $dashboard; ?>"> Dashboard</a> |
    <a href="../logout.php">🚪 Logout</a>
</div>