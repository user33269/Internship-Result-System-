<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");

if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    //form input validation 
    if (empty($username) || empty($password) || empty($confirm)) {
        $error = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {

        //users validation 
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username already exists. Please choose another.";
        } else {

            $hashed = password_hash($password, PASSWORD_BCRYPT);


            $stmt = $conn->prepare("
                INSERT INTO users (username, password, role)
                VALUES (?, ?, 'assessor')
            ");
            $stmt->bind_param("ss", $username, $hashed);

            if ($stmt->execute()) {
                $success = "Assessor account created successfully!";
            } else {
                $error = "Database error: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Assessor</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <?php include("../includes/navbar.php"); ?>

    <div style="max-width:500px; margin:50px auto; padding:0 20px;">

        <h2 style="font-size:24px; color:#333; margin-bottom:24px; text-align:center;">Add Assessor</h2>

        <a href="viewAssessors.php"
            style="display:inline-block; margin-bottom:20px; padding:9px 18px; background:white; border:1px solid #dbdbdb; border-radius:8px; color:#333; font-size:14px; font-weight:bold; text-decoration:none;"
            onmouseover="this.style.borderColor='#0095f6'; this.style.color='#0095f6';"
            onmouseout="this.style.borderColor='#dbdbdb'; this.style.color='#333';">
            ← Back to Assessor List
        </a>

        <?php if ($error): ?>
            <div
                style="background:#fff0f0; border:1px solid #f5c6cb; border-radius:8px; padding:12px 16px; margin-bottom:20px; color:#c0392b; font-size:14px;">
                ⚠️
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div
                style="background:#f0fff4; border:1px solid #b2dfdb; border-radius:8px; padding:12px 16px; margin-bottom:20px; color:#27ae60; font-size:14px;">
                ✅
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <div
            style="background:white; border:1px solid #dbdbdb; border-radius:12px; padding:35px 40px; box-shadow:0 2px 8px rgba(0,0,0,0.06);">

            <form method="POST">

                <div style="margin-bottom:18px;">
                    <label
                        style="display:block; font-size:14px; font-weight:bold; color:#333; margin-bottom:6px;">Username</label>
                    <input type="text" name="username" required
                        value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                        style="width:100%; padding:11px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;">
                </div>

                <div style="margin-bottom:18px;">
                    <label
                        style="display:block; font-size:14px; font-weight:bold; color:#333; margin-bottom:6px;">Password</label>
                    <input type="password" name="password" required
                        style="width:100%; padding:11px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;">
                </div>

                <div style="margin-bottom:24px;">
                    <label
                        style="display:block; font-size:14px; font-weight:bold; color:#333; margin-bottom:6px;">Confirm
                        Password</label>
                    <input type="password" name="confirm_password" required
                        style="width:100%; padding:11px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;">
                </div>

                <button type="submit"
                    style="width:100%; padding:13px; background-color:#0095f6; color:white; border:none; border-radius:8px; font-size:16px; font-weight:bold; cursor:pointer;"
                    onmouseover="this.style.backgroundColor='#1877f2'"
                    onmouseout="this.style.backgroundColor='#0095f6'">
                    Create Assessor Account
                </button>
            </form>
        </div>
    </div>

    <!--client side validation-->
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const form = document.querySelector("form");

            const username = form.querySelector("input[name='username']");
            const password = form.querySelector("input[name='password']");
            const confirm = form.querySelector("input[name='confirm_password']");

            form.addEventListener("submit", function (e) {

                let errors = [];

                if (username.value.trim() === "") {
                    errors.push("Username is required.");
                }

                if (username.value.trim().length < 3) {
                    errors.push("Username must be at least 3 characters.");
                }

                if (password.value === "") {
                    errors.push("Password is required.");
                }

                if (password.value.length < 6) {
                    errors.push("Password must be at least 6 characters.");
                }

                if (password.value !== confirm.value) {
                    errors.push("Passwords do not match.");
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