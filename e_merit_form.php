<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$editing = false;
$submissionData = [];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $submission_id = (int)$_GET['id'];
    $sql = "SELECT * FROM emerit_submissions WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $submission_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $editing = true;
        $submissionData = $row;
    } else {
        echo "<script>alert('Submission not found or not yours.'); window.location.href='my_submissions.php';</script>";
        exit();
    }
    mysqli_stmt_close($stmt);
} else {
    echo "<script>alert('Submission ID missing.'); window.location.href='my_submissions.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>E-Merit Form</title>
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
        <a href="logout.php">Log Out</a>
    </div>

    <!-- Tabs Navigation -->
    <div class="form-tabs">
        <a href="c_form.php" class="tab">C Form</a>
        <a href="e_merit_form.php" class="tab active">E-Merit</a>
        <a href="certificate_form.php" class="tab">Certificate</a>
        <a href="book_hall_form.php" class="tab">Book Hall</a>
        <a href="honorarium_form.php" class="tab">Honorarium</a>
        <a href="upload_paperwork.php" class="tab">Paperwork</a>
    </div>

    <!-- E-Merit Form Section -->
    <div class="form-section">
        <form action="submit_emerit_form.php" method="POST" class="form-grid" id="emeritForm">
            <?php if ($editing): ?>
                <input type="hidden" name="submission_id" value="<?php echo htmlspecialchars($submissionData['id']); ?>">
            <?php endif; ?>

            <label for="program_name">Program Name:</label>
            <input type="text" id="program_name" name="program_name" required
                value="<?php echo htmlspecialchars($editing ? $submissionData['program_name'] : ''); ?>">

            <label for="program_date">Date:</label>
            <input type="date" id="program_date" name="program_date" required
                value="<?php echo htmlspecialchars($editing ? $submissionData['program_date'] : ''); ?>">

            <label for="venue">Venue:</label>
            <input type="text" id="venue" name="venue" required
                value="<?php echo htmlspecialchars($editing ? $submissionData['venue'] : ''); ?>">

            <label for="organizer">Organizer:</label>
            <input type="text" id="organizer" name="organizer" required
                value="<?php echo htmlspecialchars($editing ? $submissionData['organizer'] : ''); ?>">

            <label for="coupon">Total Coupons:</label>
            <input type="number" id="coupon" name="coupon" required
                value="<?php echo htmlspecialchars($editing ? $submissionData['coupon'] : ''); ?>">
        </form>

        <!-- Form buttons consistent with C Form -->
        <div class="form-buttons">
            <a href="c_form.php<?php echo $editing ? '?id=' . $submissionData['id'] : ''; ?>" class="button back-button">← Back</a>
            <button type="submit" form="emeritForm" class="button save-button" name="save">Save</button>
            <a href="certificate_form.php<?php echo $editing ? '?id=' . $submissionData['id'] : ''; ?>" class="button next-button">Next →</a>
        </div>

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
    </script>

</body>
</html>