<?php
session_start();
include("config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // simple password check (temporary)
        if ($password == $user['password']) {

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == "admin") {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: assessor/dashboard.php");
            }
        } else {
            echo "Wrong password";
        }
    } else {
        echo "User not found";
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
        <form method ="POST">
            Username: <input type="text" name ="username" required><br><br>
            Password: <input type= "password" name= "password" required><br><br> 

            <button type ="submit"> Login </button> 

        </form> 
    </body>
</html>