<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.html");
    exit();
}

include 'db.php';

$sql = "SELECT * FROM c_form_submissions ORDER BY submitted_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Review Submissions</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .table-container {
            padding: 20px;
            max-width: 95%;
            margin: 30px auto;
            background: #fff3f8;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
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
            background-color: #f9f9f9;
        }

        form {
            margin: 0;
        }

        select, input[type="text"] {
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            width: 100%;
        }

        input[readonly] {
            background-color: #eee;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
            color: #2a2675;
        }
    </style>
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
    <div class="nav-user">Admin: <strong><?php echo $_SESSION['user_id']; ?></strong></div>
</div>

<!-- Side Navigation -->
<div id="sideNav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="toggleNav()">×</a>
    <a href="review_submissions.php">Review Submissions</a>
    <a href="logout.php" onclick="return confirmLogout();">Log Out</a>
</div>

<!-- Main Section -->
<div class="table-container">
    <h2>Admin - Review Program Submissions</h2>

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
                <form method="POST" action="update_submission_status.php">
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row['activity_name']) ?></td>
                    <td><?= htmlspecialchars($row['user_id']) ?></td>
                    <td><?= htmlspecialchars($row['submitted_at']) ?></td>
                    <td>
                        <select name="status">
                            <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="Approved" <?= $row['status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
                            <option value="Rejected" <?= $row['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="admin_remark" value="<?= htmlspecialchars($row['admin_remark']) ?>">
                    </td>
                    <td>
                        <input type="text" name="checked_by" value="<?= htmlspecialchars($_SESSION['user_id']) ?>" readonly>
                    </td>
                    <td>
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" class="button">Update</button>
                    </td>
                </form>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="text-align: center;">No submissions available.</p>
    <?php endif; ?>
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
