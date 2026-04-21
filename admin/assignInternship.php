<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");

if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

//fetch data
$students = $conn->query("SELECT student_id, student_name FROM students");
$assessors = $conn->query("SELECT user_id, username FROM users WHERE role='assessor'");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student = trim($_POST['student_id'] ?? '');
    $assessor_username = trim($_POST['assessor_name'] ?? '');
    $companyName = trim($_POST['company_name'] ?? '');
    $year = (int)($_POST['year'] ?? 0);
    $semester = trim($_POST['semester'] ?? '');

    //php form validation 
    if (empty($student) || empty($assessor_username) || empty($companyName) || empty($year) || empty($semester)) {
        $message = "All fields are required.";
    }
    elseif ($year < 2000 || $year > 2100) {
        $message = "Invalid year selected.";
    }
    else {

        //validate student
        $stmt = $conn->prepare("SELECT student_id FROM students WHERE student_id = ?");
        $stmt->bind_param("s", $student);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows == 0) {
            $message = "Invalid student selected.";
        } else {

            //validating assessor
            $stmt = $conn->prepare("
                SELECT user_id 
                FROM users 
                WHERE username = ? AND role = 'assessor'
            ");
            $stmt->bind_param("s", $assessor_username);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows == 0) {
                $message = "Invalid assessor selected.";
            } else {
                $assessor_id = $res->fetch_assoc()['user_id'];


                $stmt = $conn->prepare("
                    SELECT company_id 
                    FROM companies 
                    WHERE LOWER(company_name) = LOWER(?)
                ");
                $stmt->bind_param("s", $companyName);
                $stmt->execute();
                $res = $stmt->get_result();

                if ($res->num_rows > 0) {
                    $company_id = $res->fetch_assoc()['company_id'];
                } else {
                    $stmt = $conn->prepare("
                        INSERT INTO companies (company_name) VALUES (?)
                    ");
                    $stmt->bind_param("s", $companyName);
                    $stmt->execute();
                    $company_id = $stmt->insert_id;
                }


                $stmt = $conn->prepare("
                    INSERT INTO internships 
                    (student_id, assessor_id, company_id, semester, year)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->bind_param("siisi", $student, $assessor_id, $company_id, $semester, $year);

                if ($stmt->execute()) {
                    header("Location: viewInternships.php");
                    exit();
                } else {
                    $message = "Database error: " . $stmt->error;
                }
            }
        }
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

        <h2 style="font-size:24px; color:#333; margin-bottom:24px; text-align:center;">Assign Internship📋</h2>

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

                <div style="margin-bottom:18px;">
                    <label style="display:block; font-size:14px; font-weight:bold; color:#333; margin-bottom:6px;">
                        Semester
                    </label>

                    <select name="semester"
                        style="width:100%; padding:11px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;"
                        required>

                        <option value="" disabled selected>Select semester</option>
                        <option value="Spring">Spring</option>
                        <option value="Summer">Summer</option>
                        <option value="Autumn">Autumn</option>
                        <option value="Winter">Winter</option>

                    </select>
                </div>

                <div style="margin-bottom:18px;">
                    <label style="display:block; font-size:14px; font-weight:bold; color:#333; margin-bottom:6px;">
                        Year
                    </label>

                    <input type="number" name="year" placeholder="e.g. 2026"
                        min="2000" max="2100"
                        style="width:100%; padding:11px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;"
                        required>
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

            const form = document.querySelector("form");

            const student = form.querySelector("input[name='student_id']");
            const assessor = form.querySelector("input[name='assessor_name']");
            const company = form.querySelector("input[name='company_name']");
            const year = form.querySelector("input[name='year']");
            const semester = form.querySelector("select[name='semester']");

            form.addEventListener("submit", function (e) {

                let errors = [];

                if (student.value.trim() === "") {
                    errors.push("Student is required.");
                }

                if (assessor.value.trim() === "") {
                    errors.push("Assessor is required.");
                }

                if (company.value.trim() === "") {
                    errors.push("Company name is required.");
                }

                if (year.value === "" || year.value < 2000 || year.value > 2100) {
                    errors.push("Year must be between 2000 and 2100.");
                }

                if (semester.value === "") {
                    errors.push("Please select a semester.");
                }

                if (errors.length > 0) {
                    e.preventDefault();
                    alert(errors.join("\n"));
                }

            });
        });
    </script>

</body>

</html>