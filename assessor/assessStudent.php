<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != 'assessor') {
    die("Access denied");
}

$student_id = $_GET['id'];

// handle form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $u = $_POST['undertaking_tasks'];
    $h = $_POST['health_requirements'];
    $t = $_POST['theoretical_knowledge'];
    $r = $_POST['report_presentation'];
    $l = $_POST['language_clarity'];
    $la = $_POST['learning_activities'];
    $p = $_POST['project_management'];
    $tm = $_POST['time_management'];
    $comment = $_POST['comment'];

    // 🔥 AUTO CALCULATION
    $final =
        ($u * 0.10) +
        ($h * 0.10) +
        ($t * 0.10) +
        ($r * 0.15) +
        ($l * 0.10) +
        ($la * 0.15) +
        ($p * 0.15) +
        ($tm * 0.15);

    $sql = "INSERT INTO assessments
            (student_id, undertaking_tasks, health_requirements, theoretical_knowledge,
             report_presentation, language_clarity, learning_activities,
             project_management, time_management, comment, final_mark)
            VALUES
            ('$student_id','$u','$h','$t','$r','$l','$la','$p','$tm','$comment','$final')";

    if ($conn->query($sql)) {
        echo "Assessment submitted!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<h2>Assess Student</h2>

<form method="POST">

Undertaking Tasks (10%):
<input type="number" name="undertaking_tasks"><br><br>

Health & Safety (10%):
<input type="number" name="health_requirements"><br><br>

Theoretical Knowledge (10%):
<input type="number" name="theoretical_knowledge"><br><br>

Report Presentation (15%):
<input type="number" name="report_presentation"><br><br>

Language Clarity (10%):
<input type="number" name="language_clarity"><br><br>

Learning Activities (15%):
<input type="number" name="learning_activities"><br><br>

Project Management (15%):
<input type="number" name="project_management"><br><br>

Time Management (15%):
<input type="number" name="time_management"><br><br>

Comment:
<textarea name="comment"></textarea><br><br>

<button type="submit">Submit Assessment</button>

</form>