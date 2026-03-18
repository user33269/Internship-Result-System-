<?php
include("../includes/auth.php");
include("../config/db.php");
include("../includes/navbar.php");

if ($_SESSION['role'] != 'assessor') {
    die("Access denied");
}

$assessor_id = $_SESSION['user_id'];
$id = $_GET['id'];


$sql = "SELECT students.student_name, assessments.*
        FROM assessments
        JOIN students ON assessments.student_id = students.student_id
        JOIN internships ON students.student_id = internships.student_id
        WHERE assessments.student_id = '$id'
        AND internships.assessor_id = '$assessor_id'";

$result = $conn->query($sql);
$row = $result->fetch_assoc();

// extra protection
if (!$row) {
    die("Access denied");
}
?>

<a href="viewResults.php">⬅ Back</a>

<h2>Assessment Details</h2>

<p><strong>Student:</strong> <?php echo $row['student_name']; ?></p>

<table border="1">
<tr><th>Criteria</th><th>Marks</th></tr>

<tr><td>Undertaking Tasks</td><td><?php echo $row['undertaking_tasks']; ?></td></tr>
<tr><td>Health Requirements</td><td><?php echo $row['health_requirements']; ?></td></tr>
<tr><td>Theoretical Knowledge</td><td><?php echo $row['theoretical_knowledge']; ?></td></tr>
<tr><td>Report Presentation</td><td><?php echo $row['report_presentation']; ?></td></tr>
<tr><td>Language Clarity</td><td><?php echo $row['language_clarity']; ?></td></tr>
<tr><td>Learning Activities</td><td><?php echo $row['learning_activities']; ?></td></tr>
<tr><td>Project Management</td><td><?php echo $row['project_management']; ?></td></tr>
<tr><td>Time Management</td><td><?php echo $row['time_management']; ?></td></tr>

<tr>
    <td><strong>Final Mark</strong></td>
    <td><strong><?php echo $row['final_mark']; ?></strong></td>
</tr>

<tr>
    <td>Comment</td>
    <td><?php echo $row['comment']; ?></td>
</tr>

</table>