<?php
session_start();
include("../includes/auth.php");
include("../config/db.php");

if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

// Fetch all companies
$companies = $conn->query("SELECT * FROM companies");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Companies</title>

    <style>
        body {
            font-family: Arial;
            background: #f5f6fa;
        }

        .container {
            width: 90%;
            margin: 30px auto;
        }

        .search-box {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .company-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            cursor: pointer;
        }

        .company-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .company-left {
            line-height: 1.6;
        }

        .company-right {
            text-align: right;
        }

        .past-interns {
            display: none;
            margin-top: 15px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            font-size: 14px;
        }

        table th {
            background: #f0f0f0;
        }

        .toggle-btn {
            margin-top: 10px;
            padding: 6px 12px;
            border: none;
            background: #2d89ef;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>

    <script>
        function toggleInterns(id) {
            var el = document.getElementById("interns-" + id);
            if (el.style.display === "none" || el.style.display === "") {
                el.style.display = "block";
            } else {
                el.style.display = "none";
            }
        }
        function handleEnter(event) {
            if (event.key === "Enter") {
                searchCompany();
            }
        }

        function searchCompany() {
            let input = document.getElementById("searchBox").value.toLowerCase();
            let cards = document.getElementsByClassName("company-card");

            for (let i = 0; i < cards.length; i++) {
                let name = cards[i].innerText.toLowerCase();

                if (name.includes(input)) {
                    cards[i].style.display = "block";
                } else {
                    cards[i].style.display = "none";
                }
            }
        }
    </script>
</head>

<body>

    <?php include("../includes/navbar.php"); ?>
<div class="container">

   <h2 style="font-size:30px; color:#333; margin-bottom:20px; text-align:center;">Companies🏭</h2>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">

            <a href="dashboard.php"
                style="display:inline-block; padding:9px 18px; background:white; border:1px solid #dbdbdb; border-radius:8px; color:#333; font-size:14px; font-weight:bold; text-decoration:none;"
                onmouseover="this.style.borderColor='#0095f6'; this.style.color='#0095f6';"
                onmouseout="this.style.borderColor='#dbdbdb'; this.style.color='#333';">
                ← Back to Dashboard
            </a>

        </div>

    <input type="text" id="searchBox" class="search-box" placeholder="Search company name..."
    onkeypress="handleEnter(event)">
    <button onclick="searchCompany()" style="
    padding:10px 14px;
    border-radius:8px;
    border:1px solid #ccc;
    background:white;
    cursor:pointer;
    ">
    Search
    </button>

    <?php while ($c = $companies->fetch_assoc()): ?>

        <?php
        $cid = $c['company_id'];

        // total interns
        $res1 = $conn->query("SELECT COUNT(*) AS total FROM internships WHERE company_id=$cid");
        $total = $res1->fetch_assoc()['total'];

        // average score
        $res2 = $conn->query("
            SELECT AVG(a.final_mark) AS avg_score
            FROM assessments a
            JOIN internships i ON a.internship_id = i.internship_id
            WHERE i.company_id = $cid
        ");
        $avg = $res2->fetch_assoc()['avg_score'];
        if (!$avg) $avg = 0;

        // past interns list
        $interns = $conn->query("
            SELECT s.student_name, s.programme, i.year, i.semester, a.final_mark
            FROM internships i
            JOIN students s ON i.student_id = s.student_id
            LEFT JOIN assessments a ON a.internship_id = i.internship_id
            WHERE i.company_id = $cid
        ");
        ?>

        <div class="company-card">

            <div class="company-header">

                <div class="company-left">
                    <strong>🏢 <?= $c['company_name'] ?></strong><br>
                    Company ID: <?= $cid ?>
                </div>

                <div class="company-right">
                    👥 <?= $total ?> Interns<br>
                    ⭐ <?= number_format($avg, 2) ?> Avg Score
                </div>

            </div>

            <button class="toggle-btn" onclick="toggleInterns(<?= $cid ?>)">
                View Past Interns ▼
            </button>

            <div class="past-interns" id="interns-<?= $cid ?>">

                <table>
                    <tr>
                        <th>Student</th>
                        <th>Programme</th>
                        <th>Year</th>
                        <th>Semester</th>
                        <th>Final Score</th>
                    </tr>

                    <?php while ($i = $interns->fetch_assoc()): ?>
                        <tr>
                            <td><?= $i['student_name'] ?></td>
                            <td><?= $i['programme'] ?></td>
                            <td><?= $i['year'] ?></td>
                            <td><?= $i['semester'] ?></td>
                            <td><?= $i['final_mark'] ?></td>
                        </tr>
                    <?php endwhile; ?>

                </table>

            </div>

        </div>

    <?php endwhile; ?>

</div>

</body>
</html>