<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row) {
        $valid = false; // Initialize login status

        if (password_verify($password, $row['password'])) {
            $valid = true;

        } elseif ($password === $row['password']) {
            $hashed = password_hash($password, PASSWORD_DEFAULT); // Hash password
            $upd = $conn->prepare("UPDATE users SET password=? WHERE user_id=?");
            $upd->bind_param("si", $hashed, $row['user_id']);
            $upd->execute();
            $upd->close();
            $valid = true;
        }

        if ($valid) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['username'] = $row['username'];

            if ($row['role'] == "admin") {
                header("Location: admin/home.php");
            } else {
                header("Location: assessor/home.php");
            }
            exit();
        } else {
            $error = "Wrong password";
        }
    } else {
        $error = "User not found";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>

    <style>
        body {
            margin: 0;
            font-family: 'Roboto', Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f5f7fb;
        }

        .main {
            width: 900px;
            height: 520px;
            display: flex;
            border-radius: 18px;
            overflow: hidden;
            background: linear-gradient(135deg, #d9ecff 0%, #f7fbff 50%, #ffffff 100%);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        }

        /* LEFT SIDE */
        .left {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .left img {
            width: 180px;
            margin-bottom: 25px;
        }

        .left h1 {
            font-size: 26px;
            margin: 0 0 15px 0;
            color: #1f3b57;
        }

        .left p {
            font-size: 14px;
            line-height: 1.3;
            color: #4a647a;
            max-width: 320px;
            margin-top:10px;
        }

        /* RIGHT SIDE */
        .right {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-right: 45px;
        }

        /* LOGIN BOX */
        .login-box {
            width: 320px;
            background: white;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            overflow: visible;
        }

        .login-box label {
            font-size: 12px;
            color: #353333;
            margin-bottom: 6px;
            display: block;
        }

        .login-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 18px;
        }

        .login-box h2 {
            margin: 0 0 20px 0;
            font-size: 20px;
            color: #2c3e50;
        }

        .login-box input {
            width: 100%;
            padding: 12px 14px;
            margin-bottom: 14px;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            background: #fafafa;
            font-size: 14px;
            box-sizing: border-box;
        }

        .login-box input:focus {
            outline: none;
            border-color: #7fb3ff;
            background: #fff;
        }

        .login-btn {
            width: auto;
            padding: 10px 18px;
            font-size: 13px;
            border-radius: 6px;
            margin-top: 0;
        }

        .login-btn:hover {
            background: #bcd4ef;
        }

        .error-msg {
            background: #fff5f5;
            border: 1px solid #ffd6d6;
            border-left: 4px solid #e74c3c;
            color: #c0392b;
            font-size: 13px;
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .contact-link {
            margin-top: 0;
            font-size: 12px;
            text-align: left;
        }

        .contact-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="main">

        <!-- LEFT PANEL -->
        <div class="left">
            <img src="image/logo.png" alt="Logo">

            <h1>Internship Management System</h1>

            <p>
                A centralized platform to manage internship applications, assessor evaluations,
                and student progress tracking in a clean and efficient workflow.
            </p>
        </div>

        <!-- RIGHT PANEL -->
        <div class="right">
            <div class="login-box">

                <h2>Login</h2>

                <?php if (!empty($error)): ?>
                    <div class="error-msg"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">

                    <label>Username</label>
                    <input type="text" name="username" placeholder="Enter username" required>

                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter password" required>

                    <!-- bottom row actions -->
                    <div class="login-actions">
                        <a href="https://mail.google.com/mail/?view=cm&to=admin@outlook.com.my" class="contact-link"
                            target="_blank">
                            Contact Admin
                        </a>

                        <button type="submit" class="login-btn">Log In</button>
                    </div>

                </form>

            </div>
        </div>

    </div>

</body>

</html>