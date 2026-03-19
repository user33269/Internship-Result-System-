<?php
include("../config/db.php");

// get filters
$search = $_GET['search'] ?? "";
$programme = $_GET['programme'] ?? "";

// headers to force download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="results.csv"');

// open output
$output = fopen("php://output", "w");

// column headers
fputcsv($output, ['Student ID', 'Name', 'Programme', 'Final Mark', 'Comment']);

// SQL
$sql = "SELECT students.student_id, students.student_name,
               students.programme,
               assessments.final_mark, assessments.comment
        FROM assessments
        JOIN students ON assessments.student_id = students.student_id
        WHERE (students.student_id LIKE '%$search%'
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