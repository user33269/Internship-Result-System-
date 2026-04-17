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
        border-bottom: 2px solid #dbdbdb;
        padding: 30px 40px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .navbar-left {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }

    .navbar-left img {
        width: 110px;
    }

    .navbar-left .dashboard-link {
        font-size: 32px;
        font-weight: bold;
        color: #0095f6;
        text-decoration: underline;
        letter-spacing: 1px;
    }

    .navbar-left .dashboard-link:hover {
        color: #1877f2;
    }

    .navbar-right {
        display: flex;
        align-items: center;
        gap: 28px;
        margin-left: auto;
    }

    .navbar-right .help-link {
        text-decoration: none;
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 8px 16px;
        background-color: #0095f6;
        border: 2px solid #0095f6;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .navbar-right .help-link:hover { 
        background-color: #1877f2;
        border-color: #1877f2;    
        color: white;
    }

    .profile-wrapper { position: relative; }

    .profile-btn {
        background: #0095f6;
        color: white;
        border: none;
        border-radius: 50%;
        width: 48px;
        height: 48px;
        font-size: 20px;
        font-weight: bold;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(0,149,246,0.4);
    }

    .profile-btn:hover { background: #1877f2; }

    .dropdown {
        display: none;
        position: absolute;
        right: 0;
        top: 58px;
        background: white;
        border: 1px solid #dbdbdb;
        border-radius: 12px;
        padding: 20px 24px;
        width: 220px;
        box-shadow: 0 6px 16px rgba(0,0,0,0.12);
        text-align: center;
        z-index: 200;
    }

    .dropdown.show { display: block; }

    .dropdown .user-icon {
        background: #0095f6;
        color: white;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        font-size: 26px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        box-shadow: 0 2px 6px rgba(0,149,246,0.4);
    }

    .dropdown p {
        font-size: 16px;
        font-weight: bold;
        color: #333;
        margin-bottom: 4px;
    }

    .dropdown span {
        font-size: 13px;
        color: #888;
    }

    .dropdown hr {
        margin: 14px 0;
        border: none;
        border-top: 1px solid #eee;
    }

    .logout-btn {
        display: block;
        width: 100%;
        padding: 11px;
        background-color: #ed4956;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        transition: background 0.2s;
    }

    .logout-btn:hover { background-color: #c0392b; }
</style>

<div class="navbar">
    <div class="navbar-left">
        <a href="<?= $dashboard ?>">
            <img src="../image/logo.png" alt="Logo">
        </a>
        <a href="<?= $dashboard ?>" class="dashboard-link">Dashboard</a>
    </div>

    <div class="navbar-right">
        <a href="mailto:admin@outlook.com.my" class="help-link">Help</a>

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