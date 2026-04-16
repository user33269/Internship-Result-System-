<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare ("SELECT * FROM users WHERE username = ?");
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
            $_SESSION['role']    = $row['role'];
            $_SESSION['username'] = $row['username'];

            if ($row['role'] == "admin") {
                header("Location: admin/dashboard.php");
            }else {
                header("Location: assessor/dashboard.php");
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
        <title> Login </title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #fafafa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Roboto', Arial, sans-serif;
        }

        .login-card {
            background: white;
            border: 1px solid #dbdbdb;
            border-radius: 12px;
            padding: 55px 50px;
            width: 450px;
            text-align: center;
        }

        .login-card img {
            width: 130px;
            margin-bottom: 25px;
        }

        .login-card h2 {
            font-size: 22px;
            color: #555;
            margin-bottom: 25px;
            font-weight: normal;
        }

        .login-card input {
            width: 100%;
            padding: 14px 15px;
            margin-bottom: 15px;
            border: 1px solid #dbdbdb;
            border-radius: 6px;
            background-color: #fafafa;
            font-size: 16px;
            box-sizing: border-box;
        }

        .login-card input:focus {
            outline: none;
            border-color: #a8a8a8;
            background-color: white;
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background-color: #0095f6;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 5px;
        }

        .login-btn:hover {
            background-color: #1877f2;
        }

        .error-msg {
            color: #ed4956;
            font-size: 15px;
            margin-bottom: 12px;
        }

        .contact-link {
            display: block;
            margin-top: 18px;
            font-size: 15px;
            color: #0095f6;
            text-decoration: none;
        }

        .contact-link:hover {
            text-decoration: underline;
            color: #1877f2;
        }
    </style>
</head>
<body>

<div class="login-card">
    <img src="image/logo.png" alt="Logo">
    <h2>Sign in to your account</h2>

    <?php if (!empty($error)): ?>
        <p class="error-msg"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" class="login-btn">Log In</button>
    </form>
    <a href="https://mail.google.com/mail/?view=cm&to=admin@outlook.com.my" class="contact-link" target="_blank">Contact Admin</a>
</div>

    </body>
</html>