<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <img src="assets/uitm logo.jpeg" alt="Logo" class="logo">
        <div class="menu-toggle" onclick="toggleNav()">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
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
        <a href="logout.php" onclick="return confirmLogout();">Log Out</a>
    </div>

    <!-- Main Section -->
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Welcome to the HEP System Dashboard</h1>
            <p>You are logged in as: <strong><?php echo $_SESSION['user_login']; ?></strong></p>
        </div>

        <div class="dashboard-grid">
            <a href="dashboard.php" class="button">Dashboard</a>
            <a href="submit_program.php" class="button">Submit Program</a>
            <a href="my_submissions.php" class="button">My Submissions</a>
            <a href="logout.php" class="button" onclick="return confirmLogout();">Logout</a>
        </div>
    </div>

    <script>
        function toggleNav() {
            const sideNav = document.getElementById("sideNav");
            const burger = document.querySelector(".menu-toggle");
            if (sideNav.style.width === "250px") {
                sideNav.style.width = "0";
                burger.style.display = "flex";
                burger.classList.remove("active");
            } else {
                sideNav.style.width = "250px";
                burger.style.display = "none";
                burger.classList.add("active");
            }
        }

        function confirmLogout() {
            return confirm("Are you sure you want to log out?");
        }
    </script>
</body>
</html>
