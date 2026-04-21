<?php
session_start();
include("../config/db.php");

// get logged-in assessor
$assessor_id = $_SESSION['user_id'] ?? 0;

// get filters
$search = $_GET['search'] ?? "";
$programme = $_GET['programme'] ?? "";

// headers to force download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="results.csv"');

// open output
$output = fopen("php://output", "w");

// column headers (removed Comment)
fputcsv($output, ['Student ID', 'Name', 'Programme', 'Year', 'Semester', 'Final Mark']);

// SQL (removed assessments.comment)
$sql = "SELECT students.student_id, 
               students.student_name,
               students.programme,
               internships.year,
               internships.semester,
               assessments.final_mark
        FROM assessments
        JOIN students 
            ON assessments.student_id = students.student_id
        JOIN internships 
            ON assessments.internship_id = internships.internship_id
        WHERE internships.assessor_id = '$assessor_id'
        AND (students.student_id LIKE '%$search%'
        OR students.student_name LIKE '%$search%')";

if (!empty($programme)) {
    $sql .= " AND students.programme = '$programme'";
}

$result = $conn->query($sql);

// output rows
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
exit();
?>