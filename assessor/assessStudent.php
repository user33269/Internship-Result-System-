<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");

if ($_SESSION['role'] != 'assessor') {
    die("Access denied");
}

$student_id = $_GET['id'] ?? '';
$assessor_id = $_SESSION['user_id'];
$sql = "INSERT INTO assessments (student_id, internship_id, assessor_id, ...) 
        VALUES (?, ?, ?, ...)";

if (empty($student_id)) {
    die("Invalid student ID");
}

$message = "";

/* ---------------------------
   CHECK STUDENT EXISTS (SECURE)
---------------------------- */
$stmt = $conn->prepare("SELECT student_id FROM students WHERE student_id = ?");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Student does not exist.");
}

/* ---------------------------
   GET INTERNSHIP (SECURE)
---------------------------- */
$stmt = $conn->prepare("
    SELECT internship_id 
    FROM internships 
    WHERE student_id = ? AND assessor_id = ?
    ORDER BY internship_id DESC
    LIMIT 1
");
$stmt->bind_param("si", $student_id, $assessor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $internship_id = $result->fetch_assoc()['internship_id'];
} else {
    die("No internship found for this student.");
}

/* ---------------------------
   FORM SUBMISSION
---------------------------- */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fields = [
        'undertaking_tasks',
        'health_requirements',
        'theoretical_knowledge',
        'report_presentation',
        'language_clarity',
        'learning_activities',
        'project_management',
        'time_management'
    ];

    $marks = [];
    $error = false;

    foreach ($fields as $field) {

        if (!isset($_POST[$field]) || $_POST[$field] === '') {
            $message = "All fields are required.";
            $error = true;
            break;
        }

        if (!is_numeric($_POST[$field])) {
            $message = "Marks must be numeric.";
            $error = true;
            break;
        }

        $value = (int) $_POST[$field];

        if ($value < 0 || $value > 100) {
            $message = "Marks must be between 0 and 100.";
            $error = true;
            break;
        }

        $marks[] = $value;
    }

    if (!$error) {

        $u = $marks[0];
        $h = $marks[1];
        $t = $marks[2];
        $r = $marks[3];
        $l = $marks[4];
        $la = $marks[5];
        $p = $marks[6];
        $tm = $marks[7];

        $comment = $_POST['comment'] ?? "";

        $final =
            ($u * 0.10) +
            ($h * 0.10) +
            ($t * 0.10) +
            ($r * 0.15) +
            ($l * 0.10) +
            ($la * 0.15) +
            ($p * 0.15) +
            ($tm * 0.15);

        /* ---------------------------
           INSERT (PREPARED STATEMENT)
        ---------------------------- */
        $stmt = $conn->prepare("
            INSERT INTO assessments
            (student_id, internship_id, undertaking_tasks, health_requirements, theoretical_knowledge,
             report_presentation, language_clarity, learning_activities,
             project_management, time_management, comment, final_mark)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "siiiiiiiiisd",
            $student_id,
            $internship_id,
            $u,
            $h,
            $t,
            $r,
            $l,
            $la,
            $p,
            $tm,
            $comment,
            $final
        );

        if ($stmt->execute()) {
            $message = "success";
        } else {
            $message = "Database Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Assess Student</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>

<body>

    <?php include("../includes/navbar.php"); ?>

    <div style="max-width:600px; margin:50px auto; padding:0 20px;">

        <h2 style="font-size:24px; color:#333; margin-bottom:24px; text-align:center;">Assess Student</h2>

        <a href="viewStudents.php"
            style="display:inline-block; margin-bottom:20px; padding:9px 18px; background:white; border:1px solid #dbdbdb; border-radius:8px; color:#333; font-size:14px; font-weight:bold; text-decoration:none;"
            onmouseover="this.style.borderColor='#0095f6'; this.style.color='#0095f6';"
            onmouseout="this.style.borderColor='#dbdbdb'; this.style.color='#333';">
            ← Back to Student List
        </a>

        <?php if ($message === "success"): ?>
            <div
                style="background:#d4edda; color:#155724; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:15px;">
                ✅ Assessment submitted successfully!
            </div>
        <?php elseif ($message): ?>
            <div
                style="background:#f8d7da; color:#721c24; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:15px;">
                ❌ <?= $message ?>
            </div>
        <?php endif; ?>

        <div
            style="background:#f0f7ff; border:1px solid #cce0ff; border-radius:8px; padding:12px 16px; margin-bottom:20px;">
            <span style="font-size:13px; color:#888;">Assessing Student ID</span>
            <div style="font-size:16px; font-weight:bold; color:#0095f6;"><?= $student_id ?></div>
        </div>

        <div
            style="background:white; border:1px solid #dbdbdb; border-radius:12px; padding:35px 40px; box-shadow:0 2px 8px rgba(0,0,0,0.06);">

            <form method="POST">

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">

                    <div>
                        <label
                            style="display:block; font-size:13px; font-weight:bold; color:#333; margin-bottom:6px;">Undertaking
                            Tasks <span style="color:#0095f6;">(10%)</span></label>
                        <input type="number" name="undertaking_tasks" min="0" max="100" placeholder="0-100"
                            style="width:100%; padding:10px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;">
                    </div>

                    <div>
                        <label
                            style="display:block; font-size:13px; font-weight:bold; color:#333; margin-bottom:6px;">Health
                            & Safety <span style="color:#0095f6;">(10%)</span></label>
                        <input type="number" name="health_requirements" min="0" max="100" placeholder="0-100"
                            style="width:100%; padding:10px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;">
                    </div>

                    <div>
                        <label
                            style="display:block; font-size:13px; font-weight:bold; color:#333; margin-bottom:6px;">Theoretical
                            Knowledge <span style="color:#0095f6;">(10%)</span></label>
                        <input type="number" name="theoretical_knowledge" min="0" max="100" placeholder="0-100"
                            style="width:100%; padding:10px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;">
                    </div>

                    <div>
                        <label
                            style="display:block; font-size:13px; font-weight:bold; color:#333; margin-bottom:6px;">Report
                            Presentation <span style="color:#0095f6;">(15%)</span></label>
                        <input type="number" name="report_presentation" min="0" max="100" placeholder="0-100"
                            style="width:100%; padding:10px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;">
                    </div>

                    <div>
                        <label
                            style="display:block; font-size:13px; font-weight:bold; color:#333; margin-bottom:6px;">Language
                            Clarity <span style="color:#0095f6;">(10%)</span></label>
                        <input type="number" name="language_clarity" min="0" max="100" placeholder="0-100"
                            style="width:100%; padding:10px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;">
                    </div>

                    <div>
                        <label
                            style="display:block; font-size:13px; font-weight:bold; color:#333; margin-bottom:6px;">Learning
                            Activities <span style="color:#0095f6;">(15%)</span></label>
                        <input type="number" name="learning_activities" min="0" max="100" placeholder="0-100"
                            style="width:100%; padding:10px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;">
                    </div>

                    <div>
                        <label
                            style="display:block; font-size:13px; font-weight:bold; color:#333; margin-bottom:6px;">Project
                            Management <span style="color:#0095f6;">(15%)</span></label>
                        <input type="number" name="project_management" min="0" max="100" placeholder="0-100"
                            style="width:100%; padding:10px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;">
                    </div>

                    <div>
                        <label
                            style="display:block; font-size:13px; font-weight:bold; color:#333; margin-bottom:6px;">Time
                            Management <span style="color:#0095f6;">(15%)</span></label>
                        <input type="number" name="time_management" min="0" max="100" placeholder="0-100"
                            style="width:100%; padding:10px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;">
                    </div>

                </div>

                <div style="margin-bottom:24px;">
                    <label
                        style="display:block; font-size:13px; font-weight:bold; color:#333; margin-bottom:6px;">Comment</label>
                    <textarea name="comment" placeholder="Enter your comments here..."
                        style="width:100%; padding:10px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box; min-height:100px; resize:vertical;"></textarea>
                </div>

                <button type="submit"
                    style="width:100%; padding:13px; background-color:#0095f6; color:white; border:none; border-radius:8px; font-size:16px; font-weight:bold; cursor:pointer;"
                    onmouseover="this.style.backgroundColor='#1877f2'"
                    onmouseout="this.style.backgroundColor='#0095f6'">
                    Submit Assessment
                </button>

            </form>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const inputs = document.querySelectorAll("input, textarea");

            inputs.forEach((input, index) => {
                input.addEventListener("keydown", function (e) {
                    if (e.key === "Enter") {
                        e.preventDefault(); // stop submit

                        let next = inputs[index + 1];
                        if (next) {
                            next.focus();
                        }
                    }
                });
            });

            const form = document.querySelector("form");
            const inputs_num = document.querySelectorAll("input[type='number']");

            form.addEventListener("submit", function (e) {

                let valid = true;

                inputs_num.forEach(input => {
                    const value = input.value.trim();

                    if (value === "") {
                        valid = false;
                        input.style.border = "2px solid red";
                    } else if (isNaN(value)) {
                        valid = false;
                        input.style.border = "2px solid red";
                    } else {
                        const num = Number(value);

                        if (num < 0 || num > 100) {
                            valid = false;
                            input.style.border = "2px solid red";
                        } else {
                            input.style.border = "1px solid #dbdbdb";
                        }
                    }
                });

                if (!valid) {
                    e.preventDefault();
                    alert("Please ensure all marks are filled correctly (0–100).");
                }
            });
        });
    </script>

</body>

</html>