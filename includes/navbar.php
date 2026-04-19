<?php
session_start();

$role = $_SESSION['role'];
$username = $_SESSION['username'];

$currentPage = basename($_SERVER['PHP_SELF']);

if ($role == 'admin') {
    $dashboard = "../admin/dashboard.php";
    $home = "../admin/home.php";
} else if ($role == 'assessor') {
    $dashboard = "../assessor/dashboard.php";
    $home = "../assessor/home.php";
}
?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Inter', sans-serif;
        background-color: #f7f7f8;
    }

    .navbar {
        background-color: #ffffff;
        border-bottom: 1px solid #e5e7eb;
        padding: 0 40px;
        height: 70px;
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        justify-content: space-between;
    }

    .navbar-left {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-shrink: 0;
        white-space: nowrap;
    }

    .navbar-left img {
        width: 60px;
        margin-right: 30px;
    }

    .nav-link {
        position: relative;
        font-size: 14px;
        font-weight: 600;
        color: #111827;
        padding: 26px 0;
        text-decoration: none;
        margin-right: 18px;
        transition: color 0.2s;
    }

    .nav-link:hover {
        color: #707a89;
    }

    /* Only active link gets the purple underline */
    .nav-link.active::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 3px;
        background-color: #7c3aed;
        border-radius: 2px;
    }
    /* RIGHT SECTION */
    .navbar-right {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-shrink: 0; 
        white-space: nowrap;
    }

    .help-link {
        position: relative;
        font-size: 14px;
        font-weight: 600;
        color: #111827;
        padding: 26px 0;
        text-decoration: none;
        margin-right: 18px;
        transition: color 0.2s;
    }

    .help-link:hover {
        color: #707a89;
    }

    .profile-wrapper {
        position: relative;
    }

    .profile-btn {
        background: #111827;
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .profile-btn:hover {
        background: #1f2933;
    }

    .dropdown {
        display: none;
        position: absolute;
        right: 0;
        top: 52px;
        background: white;
        border-radius: 10px;
        padding: 18px;
        width: 200px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        text-align: center;
    }

    .dropdown.show {
        display: block;
    }

    .user-icon {
        background: #111827;
        color: white;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        font-size: 18px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
    }

    .dropdown p {
        font-size: 15px;
        font-weight: 600;
        color: #111827;
    }

    .dropdown span {
        font-size: 13px;
        color: #6b7280;
    }

    .dropdown hr {
        margin: 12px 0;
        border: none;
        border-top: 1px solid #eee;
    }

    .logout-btn {
        display: block;
        width: 100%;
        padding: 10px;
        background-color: #111827;
        color: white;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: background 0.2s;
    }

    .logout-btn:hover {
        background-color: #374151;
    }
</style>

<div class="navbar">
    <!-- LEFT -->
    <div class="navbar-left">
        <a href="<?= $dashboard ?>">
            <img src="../image/logo.png" alt="Logo">
        </a>

        <a href="<?= $home ?>" class="nav-link <?= ($currentPage == 'home.php') ? 'active' : '' ?>">
            Home
        </a>

        <a href="<?= $dashboard ?>" class="nav-link <?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">
            Dashboard
        </a>
    </div>

    <!-- RIGHT -->
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