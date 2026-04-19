<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");

if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM users WHERE user_id = $id AND role = 'assessor'");
$row = $result->fetch_assoc();

if (!$row) {
    die("Assessor not found.");
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (empty($username)) {
        $error = "Username cannot be empty.";
    } else {
        // Check username not taken by another user
        $check = $conn->query("SELECT user_id FROM users WHERE username = '$username' AND user_id != $id");
        if ($check->num_rows > 0) {
            $error = "Username already taken. Please choose another.";
        } else {
            if (!empty($password)) {
                if ($password !== $confirm) {
                    $error = "Passwords do not match.";
                } else {
                    $hashed = password_hash($password, PASSWORD_BCRYPT);
                    $sql = "UPDATE users SET username = '$username', password = '$hashed' WHERE user_id = $id";
                }
            } else {
                // No password change
                $sql = "UPDATE users SET username = '$username' WHERE user_id = $id";
            }

            if (empty($error)) {
                if ($conn->query($sql) === TRUE) {
                    $success = "Assessor updated successfully!";
                    $row['username'] = $username;
                } else {
                    $error = "Error: " . $conn->error;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Assessor</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <?php include("../includes/navbar.php"); ?>

    <div style="max-width:500px; margin:50px auto; padding:0 20px;">

        <h2 style="font-size:24px; color:#333; margin-bottom:24px; text-align:center;">Edit Assessor</h2>

        <a href="../admin/viewAssessors.php"
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

            <div
                style="background:#f0f7ff; border:1px solid #cce0ff; border-radius:8px; padding:12px 16px; margin-bottom:24px;">
                <span style="font-size:13px; color:#888;">Assessor ID</span>
                <div style="font-size:16px; font-weight:bold; color:#0095f6;">
                    <?= $row['user_id'] ?>
                </div>
            </div>

            <form method="POST">

                <div style="margin-bottom:18px;">
                    <label
                        style="display:block; font-size:14px; font-weight:bold; color:#333; margin-bottom:6px;">Username</label>
                    <input type="text" name="username" required value="<?= htmlspecialchars($row['username']) ?>"
                        style="width:100%; padding:11px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;">
                </div>

                <div style="margin-bottom:18px;">
                    <label style="display:block; font-size:14px; font-weight:bold; color:#333; margin-bottom:6px;">New
                        Password <span style="font-weight:normal; color:#888;">(leave blank to keep
                            current)</span></label>
                    <input type="password" name="password"
                        style="width:100%; padding:11px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;">
                </div>

                <div style="margin-bottom:24px;">
                    <label
                        style="display:block; font-size:14px; font-weight:bold; color:#333; margin-bottom:6px;">Confirm
                        New Password</label>
                    <input type="password" name="confirm_password"
                        style="width:100%; padding:11px 14px; border:1px solid #dbdbdb; border-radius:8px; font-size:15px; background:#fafafa; box-sizing:border-box;">
                </div>

                <button type="submit"
                    style="width:100%; padding:13px; background-color:#0095f6; color:white; border:none; border-radius:8px; font-size:16px; font-weight:bold; cursor:pointer;"
                    onmouseover="this.style.backgroundColor='#1877f2'"
                    onmouseout="this.style.backgroundColor='#0095f6'">
                    Update Assessor
                </button>
            </form>
        </div>
    </div>

</body>

</html>