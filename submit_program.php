<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$isCFormDone = isset($_SESSION['submit_c_form']) && $_SESSION['submit_c_form'] === true;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Program</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .form-nav-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            margin-top: 30px;
        }

        .form-box {
            display: block;
            width: 80%;
            max-width: 600px;
            background-color: #2a2675;
            color: white;
            text-align: center;
            padding: 15px;
            border-radius: 10px;
            font-size: 18px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .form-box:hover {
            background-color: #4b45c2;
        }

        .form-box.disabled {
            pointer-events: none;
            opacity: 0.5;
            background-color: #ccc;
            color: #666;
        }

        .top-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: url('assets/navbar-bg.jpeg') no-repeat center center;
            background-size: cover;
            color: white;
            padding: 10px 20px;
        }

        .top-nav h2 {
            margin: 0;
        }

        .top-nav p {
            margin: 0;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <img src="assets/uitm logo.jpeg" alt="Logo" class="logo">
        <div class="menu-toggle" onclick="toggleNav()">
            <div class="bar"></div><div class="bar"></div><div class="bar"></div>
        </div>
        <div class="nav-title">HEP Program Registration System</div>
        <div class="nav-user">Logged in as: <strong><?php echo $_SESSION['user_login']; ?></strong></div>
    </div>

    <!-- Side Nav -->
    <div id="sideNav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="toggleNav()">×</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="submit_program.php">Submit Program</a>
        <a href="my_submissions.php">My Submissions</a>
        <a href="logout.php">Log Out</a>
    </div>

    <!-- Program submission form list -->
    <div class="form-section">
        <div class="form-nav-container">
            <a href="c_form.php" class="form-box">✅ C Form (Required)</a>

            <?php if ($isCFormDone): ?>
                <a href="e_merit_form.php" class="form-box">E-Merit Form</a>
                <a href="certificate_form.php" class="form-box">Certificate Form</a>
                <a href="book_hall_form.php" class="form-box">Book Hall Form</a>
                <a href="honorarium_form.php" class="form-box">Honorarium Form</a>
                <a href="upload_paperwork.php" class="form-box">Upload Paperwork</a>
            <?php else: ?>
                <div class="form-box disabled">E-Merit Form (Fill C Form First)</div>
                <div class="form-box disabled">Certificate Form</div>
                <div class="form-box disabled">Book Hall Form</div>
                <div class="form-box disabled">Honorarium Form</div>
                <div class="form-box disabled">Upload Paperwork</div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function toggleNav() {
            const sideNav = document.getElementById("sideNav");
            const burger = document.querySelector(".menu-toggle");
            if (sideNav.style.width === "250px") {
                sideNav.style.width = "0";
                burger.style.display = "flex";
            } else {
                sideNav.style.width = "250px";
                burger.style.display = "none";
            }
        }
    </script>
</body>
</html>
