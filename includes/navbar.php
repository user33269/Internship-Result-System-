<?php

$role = $_SESSION['role'];
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

$currentPage = basename($_SERVER['PHP_SELF']);

if ($role == 'admin') {
    $dashboard = "../admin/dashboard.php";
    $home = "../admin/home.php";

    $notif_sql = "SELECT a.student_id, s.student_name, a.created_at
                  FROM assessments a
                  JOIN students s ON a.student_id = s.student_id
                  WHERE a.is_read = 0
                  ORDER BY a.created_at DESC";

} else if ($role == 'assessor') {
    $dashboard = "../assessor/dashboard.php";
    $home = "../assessor/home.php";

    $notif_sql = "SELECT s.student_name, i.created_at
                  FROM internships i
                  JOIN students s ON i.student_id = s.student_id
                  WHERE i.assessor_id = '$user_id'
                  AND i.is_read = 0
                  ORDER BY i.created_at DESC";
}

if (!isset($conn)) {
    include_once("../config/db.php");
}
$notif_result = $conn->query($notif_sql);
$notif_count = $notif_result ? $notif_result->num_rows : 0;
?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

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

    .notif-wrapper {
        position: relative;
    }

    .notif-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 20px;
        color: #111827;
        padding: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        transition: color 0.2s;
    }

    .notif-btn:hover {
        color: #7c3aed;
    }

    .notif-badge {
        position: absolute;
        top: 0;
        right: 0;
        background: #ef4444;
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 11px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .notif-dropdown {
        display: none;
        position: absolute;
        right: 0;
        top: 52px;
        background: white;
        border-radius: 10px;
        width: 300px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        z-index: 300;
        overflow: hidden;
    }

    .notif-dropdown.show {
        display: block;
    }

    .notif-header {
        padding: 14px 18px;
        font-size: 14px;
        font-weight: 600;
        color: #111827;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notif-header a {
        font-size: 12px;
        color: #7c3aed;
        text-decoration: none;
        font-weight: 500;
    }

    .notif-header a:hover {
        text-decoration: underline;
    }

    .notif-item {
        padding: 12px 18px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 13px;
        color: #374151;
        background: #fafafa;
    }

    .notif-item:last-child {
        border-bottom: none;
    }

    .notif-item:hover {
        background: #f3f4f6;
    }

    .notif-item .notif-text {
        font-weight: 500;
        color: #111827;
    }

    .notif-item .notif-time {
        font-size: 11px;
        color: #9ca3af;
        margin-top: 3px;
    }

    .notif-empty {
        padding: 20px;
        text-align: center;
        font-size: 13px;
        color: #9ca3af;
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
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
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

    <div class="navbar-right">
        <a href="mailto:admin@outlook.com.my" class="help-link">Help</a>

        <div class="notif-wrapper">
            <button class="notif-btn" onclick="toggleNotif()">
                🔔
                <?php if ($notif_count > 0): ?>
                    <span class="notif-badge"><?= $notif_count ?></span>
                <?php endif; ?>
            </button>

            <div class="notif-dropdown" id="notifDropdown">
                <div class="notif-header">
                    Notifications
                    <?php if ($notif_count > 0): ?>
                        <a href="../markread.php">Mark all as read</a>
                    <?php endif; ?>
                </div>

                <?php if ($notif_count > 0): ?>
                    <?php while ($n = $notif_result->fetch_assoc()): ?>
                        <div class="notif-item">
                            <?php if ($role == 'admin'): ?>
                                <div class="notif-text">✅ <?= htmlspecialchars($n['student_name']) ?> has been assessed</div>
                            <?php else: ?>
                                <div class="notif-text">🎓 <?= htmlspecialchars($n['student_name']) ?> has been assigned to you
                                </div>
                            <?php endif; ?>
                            <div class="notif-time"><?= $n['created_at'] ?></div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="notif-empty">No new notifications</div>
                <?php endif; ?>
            </div>
        </div>

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
    function toggleNotif() {
        document.getElementById('notifDropdown').classList.toggle('show');
        document.getElementById('profileDropdown').classList.remove('show');
    }

    function toggleDropdown() {
        document.getElementById('profileDropdown').classList.toggle('show');
    }

    window.addEventListener('click', function (e) {
        if (!e.target.closest('.profile-wrapper')) {
            document.getElementById('profileDropdown').classList.remove('show');
        }
        if (!e.target.closest('.notif-wrapper')) {
            document.getElementById('notifDropdown').classList.remove('show');
        }
    });
</script>