<?php

$role = $_SESSION['role'];
$username = $_SESSION['username'];


if ($role == 'admin') {
    $dashboard = "../admin/dashboard.php";
} else if ($role == 'assessor') {
    $dashboard = "../assessor/dashboard.php";
}
?>

<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Roboto', Arial, sans-serif;
        background-color: #fafafa;
    }

    .navbar {
        background-color: white;
        border-bottom: 1px solid #dbdbdb;
        padding: 12px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .navbar-left img {
        width: 60px;
        cursor: pointer;
    }

    .navbar-center a {
        text-decoration: none;
        color: #333;
        font-size: 15px;
        font-weight: bold;
        padding: 8px 18px;
        border-radius: 6px;
        transition: background 0.2s;
    }

    .navbar-center a:hover { background-color: #f0f2f5; }

    .navbar-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .navbar-right a {
        text-decoration: none;
        color: #333;
        font-size: 15px;
    }

    .navbar-right a:hover { color: #0095f6; }

    .profile-wrapper { position: relative; }

    .profile-btn {
        background: #0095f6;
        color: white;
        border: none;
        border-radius: 50%;
        width: 38px;
        height: 38px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-btn:hover { background: #1877f2; }

    .dropdown {
        display: none;
        position: absolute;
        right: 0;
        top: 48px;
        background: white;
        border: 1px solid #dbdbdb;
        border-radius: 10px;
        padding: 16px 20px;
        width: 200px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        text-align: center;
        z-index: 200;
    }

    .dropdown.show { display: block; }

    .dropdown .user-icon {
        background: #0095f6;
        color: white;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        font-size: 20px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
    }

    .dropdown p {
        font-size: 15px;
        font-weight: bold;
        color: #333;
        margin-bottom: 4px;
    }

    .dropdown span {
        font-size: 13px;
        color: #888;
    }

    .dropdown hr {
        margin: 12px 0;
        border: none;
        border-top: 1px solid #eee;
    }

    .logout-btn {
        display: block;
        width: 100%;
        padding: 9px;
        background-color: #ed4956;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
    }

    .logout-btn:hover { background-color: #c0392b; }
</style>

<!-- NAVBAR -->
<div class="navbar">
    <div class="navbar-left">
        <a href="<?= $dashboard ?>">
            <img src="../image/logo.png" alt="Logo">
        </a>
    </div>

    <div class="navbar-center">
        <a href="<?= $dashboard ?>">Dashboard</a>
    </div>

    <div class="navbar-right">
        <a href="mailto:admin@outlook.com.my">Help</a>

        <div class="profile-wrapper">
            <button class="profile-btn" onclick="toggleDropdown()">
                <?= strtoupper(substr($username, 0, 1)) ?>
            </button>

            <div class="dropdown" id="profileDropdown">
                <div class="user-icon">
                    <?= strtoupper(substr($username, 0, 1)) ?>
                </div>
                <p><?= htmlspecialchars($username) ?></p>
                <span><?= ucfirst($role) ?></span>
                <hr>
                <a href="../logout.php" class="logout-btn">Log Out</a>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleDropdown() {
        document.getElementById('profileDropdown').classList.toggle('show');
    }

    window.addEventListener('click', function(e) {
        if (!e.target.closest('.profile-wrapper')) {
            document.getElementById('profileDropdown').classList.remove('show');
        }
    });
</script>