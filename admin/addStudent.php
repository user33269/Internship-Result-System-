<?php
session_start();
include("../includes/auth.php");
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
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<?php include("../includes/navbar.php"); ?>

<div style="max-width:500px; margin:50px auto; padding:0 20px;">

    <h2 style="font-size:24px; color:#333; margin-bottom:24px; text-align:center;">Add Student</h2>

    <a href="dashboard.php"
        style="display:inline-block; margin-bottom:20px; padding:9px 18px; background:white; border:1px solid #dbdbdb; border-radius:8px; color:#333; font-size:14px; font-weight:bold; text-decoration:none;"
        onmouseover="this.style.borderColor='#0095f6'; this.style.color='#0095f6';"
        onmouseout="this.style.borderColor='#dbdbdb'; this.style.color='#333';">
        ← Back to Dashboard
    </a>

    <div style="background:white; border:1px solid #dbdbdb; border-radius:12px; padding:35px 40px; box-shadow:0 2px 8px rgba(0,0,0,0.06);">

<form method="POST">
     <div style="margin-bottom:18px;">
                <label style="display:block; font-size:14px; font-weight:bold; color:#333; margin-bottom:6px;">Student ID</label>
                <input type="text" name="student_id" required
                    style="width:100%; padding:11px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;">
            </div>

            <div style="margin-bottom:18px;">
                <label style="display:block; font-size:14px; font-weight:bold; color:#333; margin-bottom:6px;">Student Name</label>
                <input type="text" name="student_name" required
                    style="width:100%; padding:11px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;">
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block; font-size:14px; font-weight:bold; color:#333; margin-bottom:6px;">Programme</label>
                <input type="text" name="programme" required
                    style="width:100%; padding:11px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;">
            </div>

        <button type="submit"
        style="width:100%; padding:13px; background-color:#0095f6; color:white; border:none; border-radius:8px; font-size:16px; font-weight:bold; cursor:pointer;"
        onmouseover="this.style.backgroundColor='#1877f2'"
        onmouseout="this.style.backgroundColor='#0095f6'">Add Student
        </button>
    </form>
    </div>

    <div style="text-align:center; margin-top:16px;">
        <a href="viewStudents.php" style="color:#0095f6; text-decoration:none; font-size:14px;">← Back to Student List</a>
    </div>

</div>


</body>
</html>