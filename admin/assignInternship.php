<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

// fetch students
$students = $conn->query("SELECT * FROM students");

// fetch assessors only
$assessors = $conn->query("SELECT * FROM users WHERE role='assessor'");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student = $_POST['student_id'];
    $assessor = $_POST['assessor_id'];
    $company = $_POST['company_name'];

    $sql = "INSERT INTO internships (student_id, assessor_id, company_name)
            VALUES ('$student', '$assessor', '$company')";

    if ($conn->query($sql)) {
        echo "Assignment successful!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html?=>
    <body>
<h2>Assign Internship</h2>

<form method="POST">

Student:
<select name="student_id">
<?php while($s = $students->fetch_assoc()) { ?>
    <option value="<?php echo $s['student_id']; ?>">
        <?php echo $s['student_name']; ?>
    </option>
<?php } ?>
</select><br><br>

Assessor:
<select name="assessor_id">
<?php while($a = $assessors->fetch_assoc()) { ?>
    <option value="<?php echo $a['internship_id']; ?>">
        <?php echo $a['username']; ?>
    </option>
<?php } ?>
</select><br><br>

Company:
<input type="text" name="company_name"><br><br>

<button type="submit">Assign</button>

</form></body></html> 