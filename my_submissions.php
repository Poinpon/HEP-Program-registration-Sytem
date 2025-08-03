<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM c_form_submissions WHERE user_id = ? ORDER BY submitted_at DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Submissions</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }
        .action-button {
            border: none;
            background: none;
            cursor: pointer;
            font-size: 16px;
            color: #2a2675;
            transition: transform 0.2s;
            position: relative;
        }
        .action-button:hover {
            transform: scale(1.2);
        }
        .action-button::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: white;
            padding: 4px 6px;
            border-radius: 4px;
            font-size: 12px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
            white-space: nowrap;
        }
        .action-button:hover::after {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <img src="assets/uitm logo.jpeg" alt="Logo" class="logo">
        <div class="menu-toggle" onclick="toggleNav()">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
        <div class="nav-title">HEP Program Registration System</div>
        <div class="nav-user">Logged in as: <strong><?php echo htmlspecialchars($_SESSION['user_login']); ?></strong></div>
    </div>

    <div id="sideNav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="toggleNav()">×</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="submit_program.php">Submit Program</a>
        <a href="my_submissions.php">My Submissions</a>
        <a href="logout.php" onclick="return confirmLogout();">Log Out</a>
    </div>

    <div class="form-section">
        <h2>My Submissions</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div style="overflow-x:auto;">
            <table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse: collapse;">
                <tr style="background-color:#2a2675; color:white;">
                    <th>No</th>
                    <th>Program Name</th>
                    <th>Submission Date</th>
                    <th>Status</th>
                    <th>Remark</th>
                    <th>Checked By</th>
                    <th>Actions</th>
                </tr>
                <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                <tr style="background-color: <?php echo $i % 2 == 0 ? '#f2f2f2' : '#fff'; ?>">
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($row['activity_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['submitted_at']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td><?php echo htmlspecialchars($row['admin_remark']); ?></td>
                    <td><?php echo htmlspecialchars($row['checked_by']); ?></td>
                    <td class="action-buttons">
                        <button type="button" class="action-button" data-tooltip="Update Submission" onclick="window.location.href='update_submission.php?id=<?php echo $row['id']; ?>'">&#9998;</button>
                        <button type="button" class="action-button" data-tooltip="Delete Submission" onclick="confirmDelete(<?php echo $row['id']; ?>)">&#128465;</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
            </div>
        <?php else: ?>
            <p>You haven't submitted any programs yet.</p>
        <?php endif; ?>
    </div>

    <script>
        function toggleNav() {
            const sideNav = document.getElementById("sideNav");
            const burger = document.querySelector(".menu-toggle");
            if (sideNav.style.width === "250px") {
                sideNav.style.width = "0";
                burger.style.display = "flex"; // show burger again
                burger.classList.remove("active");
            } else {
                sideNav.style.width = "250px";
                burger.style.display = "none"; // hide burger when sidebar is open
                burger.classList.add("active");
            }
        }

        function confirmLogout() {
            return confirm("Are you sure you want to log out?");
        }

        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this submission?")) {
                window.location.href = 'delete_submission.php?id=' + id;
            }
        }
    </script>

</body>
</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
