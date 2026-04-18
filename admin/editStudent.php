<?php
session_start();
include("../includes/auth.php");
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
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<?php include("../includes/navbar.php"); ?>

<div style="max-width:500px; margin:50px auto; padding:0 20px;">

    <h2 style="font-size:24px; color:#333; margin-bottom:24px; text-align:center;">Edit Student</h2>

    <a href="viewStudents.php"
        style="display:inline-block; margin-bottom:20px; padding:9px 18px; background:white; border:1px solid #dbdbdb; border-radius:8px; color:#333; font-size:14px; font-weight:bold; text-decoration:none;"
        onmouseover="this.style.borderColor='#0095f6'; this.style.color='#0095f6';"
        onmouseout="this.style.borderColor='#dbdbdb'; this.style.color='#333';">
        ← Back to Student List
    </a>

    <div style="background:white; border:1px solid #dbdbdb; border-radius:12px; padding:35px 40px; box-shadow:0 2px 8px rgba(0,0,0,0.06);">

        <div style="background:#f0f7ff; border:1px solid #cce0ff; border-radius:8px; padding:12px 16px; margin-bottom:24px;">
            <span style="font-size:13px; color:#888;">Student ID</span>
            <div style="font-size:16px; font-weight:bold; color:#0095f6;"><?php echo $row['student_id']; ?></div>
        </div>

    <form method="POST">
    
        <input type="hidden" name="student_id" value=" <?php echo $row['student_id']; ?>

        <div style="margin-bottom:18px;">
            <label style="display:block; font-size:14px; font-weight:bold; color:#333; margin-bottom:6px;">Student Name</label>
            <input type="text" name="student_name"
           value="<?php echo $row['student_name']; ?>"style="width:100%; padding:11px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;">
        </div>

        <div style="margin-bottom:24px;">
            <label style="display:block; font-size:14px; font-weight:bold; color:#333; margin-bottom:6px;">Programme</label>
            <input type="text" name="programme"
           value="<?php echo $row['programme']; ?>"style="width:100%; padding:11px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;">
         </div>

        <button type="submit" style="width:100%; padding:13px; background-color:#0095f6; color:white; border:none; border-radius:8px; font-size:16px; font-weight:bold; cursor:pointer;"
                onmouseover="this.style.backgroundColor='#1877f2'"
                onmouseout="this.style.backgroundColor='#0095f6'">
                Update Student
            </button>
    </form>

    </div>

</div>

</body>
</html>