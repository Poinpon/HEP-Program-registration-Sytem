<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.html");
    exit();
}

include 'db.php';

// Handle inline update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $status = $_POST['status'];
    $admin_remark = $_POST['admin_remark'];
    $checked_by = $_SESSION['user_id'];

    $updateSql = "UPDATE c_form_submissions SET status = ?, admin_remark = ?, checked_by = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $updateSql);
    mysqli_stmt_bind_param($stmt, "sssi", $status, $admin_remark, $checked_by, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

$sql = "SELECT cfs.*, u.user_id AS user_login
        FROM c_form_submissions cfs
        JOIN users u ON cfs.user_id = u.id
        ORDER BY cfs.submitted_at DESC";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Review Submissions</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #2a2675;
            padding: 10px 20px;
            color: white;
            position: relative;
            height: 40px;
        }

        .logo {
            height: 60px;
        }

        .nav-title {
            font-size: 20px;
            font-weight: bold;
        }

        /* Burger Icon */
        .menu-toggle {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 30px;
            height: 25px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 1001;
        }

        .menu-toggle .bar {
            height: 4px;
            width: 100%;
            background-color:rgb(255, 255, 255);
            border-radius: 3px;
        }

        /* Side Navigation */
        .sidenav {
            height: 100%;
            width: 0;
            position: fixed;
            top: 0;
            right: 0;
            background-color:#2a2675;
            overflow-x: hidden;
            transition: width 0.2s ease;
            padding-top: 60px;
            z-index: 1000;
        }

        .sidenav a {
            padding: 14px 24px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
        }

        .sidenav a:hover {
            background-color: #444;
        }

        .sidenav .closebtn {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 28px;
            color: white;
        }

        .review-container {
            max-width: 95%;
            margin: 40px auto;
            background-color: #fff3f8;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 15px;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #2a2675;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #fdf7f9;
        }

        select, input[type="text"] {
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            width: 200px;
        }

        .form-title {
            text-align: center;
            font-size: 26px;
            color: #2a2675;
            margin-bottom: 25px;
        }

        .button {
            padding: 6px 12px;
            background-color: #2a2675;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .checked-by-label {
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <img src="assets/uitm logo.jpeg" alt="Logo" class="logo">
        <div class="nav-title">HEP Program Registration System - Admin</div>
        <div class="menu-toggle" onclick="toggleNav()">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
    </div>

    <!-- Burger Menu Icon -->
    <div class="menu-toggle" onclick="toggleNav()">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
    </div>

    <!-- Side Navigation -->
    <div id="sideNav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="toggleNav()">×</a>
        <a href="logout.php" onclick="return confirm('Are you sure you want to log out?');">Log Out</a>
    </div>


    <!-- Content -->
    <div class="review-container">
        <h2 class="form-title">Admin - Review Program Submissions</h2>
        <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <tr>
                <th>No</th>
                <th>Program Name</th>
                <th>Submitted By</th>
                <th>Date</th>
                <th>Status</th>
                <th>Remark</th>
                <th>Checked By</th>
                <th>Action</th>
            </tr>
            <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <form method="POST" action="review_submissions.php">
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row['activity_name']) ?></td>
                    <td><?= htmlspecialchars($row['user_login']) ?></td>
                    <td><?= htmlspecialchars($row['submitted_at']) ?></td>
                    <td>
                        <select name="status">
                            <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="Approved" <?= $row['status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
                            <option value="Rejected" <?= $row['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                    </td>
                    <td><input type="text" name="admin_remark" value="<?= htmlspecialchars($row['admin_remark']) ?>"></td>
                    <td><span class="checked-by-label"><?= htmlspecialchars($_SESSION['user_login']) ?></span></td>
                    <td style="display: flex; flex-direction: column; gap: 6px;">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" class="button">Update</button>
                        <a href="view_c_form.php?id=<?= $row['id'] ?>&user=<?= $row['user_id'] ?>" class="button">View Details</a>
                    </td>
                </form>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
            <p style="text-align:center;">No submissions available.</p>
        <?php endif; ?>
    </div>

    <script>
        function toggleNav() {
            const sideNav = document.getElementById("sideNav");
            sideNav.style.width = sideNav.style.width === "250px" ? "0" : "250px";
        }
    </script>
</body>
</html>
