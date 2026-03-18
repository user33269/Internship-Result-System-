<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");
include("../includes/navbar.php");

if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

// fetch students
$students = $conn->query("SELECT * FROM students");

// fetch assessors
$assessors = $conn->query("SELECT * FROM users WHERE role='assessor'");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student = $_POST['student_id'];
    $assessor = $_POST['assessor_name'] ?? "";
    $company = $_POST['company_name'];

    // convert assessor username to user_id
    $getAssessor = $conn->query("
        SELECT user_id FROM users 
        WHERE username = '$assessor' 
        AND role = 'assessor'
    ");

    if ($getAssessor->num_rows > 0) {
        $assessor = $getAssessor->fetch_assoc()['user_id'];
    } else {
        die("Invalid assessor selected");
    }
    $sql = "INSERT INTO internships (student_id, assessor_id, company_name)
            VALUES ('$student', '$assessor', '$company')";

    if ($conn->query($sql)) {
        header("Location: viewInternships.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll("input");

    inputs.forEach((input, index) => {
        input.addEventListener("keydown", function (e) {
            if (e.key === "Enter") {
                e.preventDefault(); // STOP form submission
                
                let next = inputs[index + 1];
                if (next) {
                    next.focus(); // move to next field
                }
            }
        });
    });
});
</script>
<body>

<h2>Assign Internship</h2>

<form method="POST">

Student:
<input list="students" name="student_id" placeholder="Select or type student ID">
<datalist id="students">
<?php while($s = $students->fetch_assoc()) { ?>
    <option value="<?php echo $s['student_id']; ?>">
        <?php echo $s['student_name']; ?>
    </option>
<?php } ?>
</datalist>
<br><br>

Assessor:
<input list="assessors" name="assessor_name"
       value="<?php echo $assessor ?? ''; ?>"
       placeholder="Select assessor">

<datalist id="assessors">
<?php while($a = $assessors->fetch_assoc()) { ?>
    <option value="<?php echo $a['username']; ?>">
<?php } ?>
</datalist>
<br><br>

Company:
<input type="text" name="company_name" placeholder="Enter company name"><br><br>

<button type="submit">Assign</button>

</form>

</body>
</html>