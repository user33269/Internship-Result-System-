<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");

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

<head>
    <title>Assign Internship</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

        <?php include("../includes/navbar.php"); ?>

    <div style="max-width:500px; margin:50px auto; padding:0 20px;">

        <h2 style="font-size:24px; color:#333; margin-bottom:24px; text-align:center;">Assign Internship</h2>

        <a href="dashboard.php"
            style="display:inline-block; margin-bottom:20px; padding:9px 18px; background:white; border:1px solid #dbdbdb; border-radius:8px; color:#333; font-size:14px; font-weight:bold; text-decoration:none;"
            onmouseover="this.style.borderColor='#0095f6'; this.style.color='#0095f6';"
            onmouseout="this.style.borderColor='#dbdbdb'; this.style.color='#333';">
            ← Back to Dashboard
        </a>

        <div
            style="background:white; border:1px solid #dbdbdb; border-radius:12px; padding:35px 40px; box-shadow:0 2px 8px rgba(0,0,0,0.06);">

            <form method="POST">

                <div style="margin-bottom:18px;">
                    <label
                        style="display:block; font-size:14px; font-weight:bold; color:#333; margin-bottom:6px;">Student</label>
                    <input list="students" name="student_id" placeholder="Select or type student ID"
                        style="width:100%; padding:11px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;">
                    <datalist id="students">
                            <?php while ($s = $students->fetch_assoc()) { ?>
                            <option value="<?php echo $s['student_id']; ?>">
                                    <?php echo $s['student_name']; ?>
                            </option>
                            <?php } ?>
                    </datalist>
                </div>

                <div style="margin-bottom:18px;">
                    <label
                        style="display:block; font-size:14px; font-weight:bold; color:#333; margin-bottom:6px;">Assessor</label>
                    <input list="assessors" name="assessor_name" placeholder="Select assessor"
                        style="width:100%; padding:11px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;">
                    <datalist id="assessors">
                            <?php while ($a = $assessors->fetch_assoc()) { ?>
                            <option value="<?php echo $a['username']; ?>">
                                    <?php } ?>
                    </datalist>
                </div>

                <div style="margin-bottom:24px;">
                    <label
                        style="display:block; font-size:14px; font-weight:bold; color:#333; margin-bottom:6px;">Company</label>
                    <input type="text" name="company_name" placeholder="Enter company name"
                        style="width:100%; padding:11px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;">
                </div>

                <button type="submit"
                    style="width:100%; padding:13px; background-color:#0095f6; color:white; border:none; border-radius:8px; font-size:16px; font-weight:bold; cursor:pointer;"
                    onmouseover="this.style.backgroundColor='#1877f2'"
                    onmouseout="this.style.backgroundColor='#0095f6'">
                    Assign Internship
                </button>

            </form>

        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const inputs = document.querySelectorAll("input");

            inputs.forEach((input, index) => {
                input.addEventListener("keydown", function (e) {
                    if (e.key === "Enter") {
                        e.preventDefault(); // Stop form submission

                        let next = inputs[index + 1];
                        if (next) {
                            next.focus(); // move to next field
                        }
                    }
                });
            });
        });
    </script>

</body>

</html>