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
    </head>
    <body>
        <h2> Login </h2>
        <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
        <form method ="POST">
            Username: <input type="text" name ="username" required><br><br>
            Password: <input type= "password" name= "password" required><br><br> 

            <button type ="submit"> Login </button> 

        </form> 
    </body>
</html>