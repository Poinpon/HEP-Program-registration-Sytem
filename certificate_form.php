<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// ✅ Get submission ID
$submission_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// ✅ Default values
$data = [
    'association' => '',
    'program_name' => '',
    'date' => '',
    'venue' => '',
    'report_date' => ''
];

// ✅ Load existing data if submission_id is present
if ($submission_id) {
    include 'db.php';

    $sql = "SELECT * FROM certificate_submissions WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $submission_id, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Certificate Form</title>
    <link rel="stylesheet" href="assets/style.css">
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
        <div class="nav-user">Logged in as: <strong><?php echo $_SESSION['user_login']; ?></strong></div>
    </div>

    <div id="sideNav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="toggleNav()">×</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="submit_program.php">Submit Program</a>
        <a href="my_submissions.php">My Submissions</a>
        <a href="logout.php">Log Out</a>
    </div>

    <div class="form-tabs">
        <a href="c_form.php?id=<?php echo $submission_id; ?>" class="tab">C Form</a>
        <a href="e_merit_form.php?id=<?php echo $submission_id; ?>" class="tab">E-Merit</a>
        <a href="certificate_form.php?id=<?php echo $submission_id; ?>" class="tab active">Certificate</a>
        <a href="book_hall_form.php?id=<?php echo $submission_id; ?>" class="tab">Book Hall</a>
        <a href="honorarium_form.php?id=<?php echo $submission_id; ?>" class="tab">Honorarium</a>
        <a href="upload_paperwork.php?id=<?php echo $submission_id; ?>" class="tab">Paperwork</a>
    </div>

    <div class="form-section">
        <form action="submit_certificate_form.php?id=<?php echo $submission_id; ?>" method="POST" class="form-grid" id="certificateForm">
            <label for="association">Association / Club:</label>
            <input type="text" id="association" name="association" value="<?php echo htmlspecialchars($data['association']); ?>" required>

            <label for="program_name">Program Name:</label>
            <input type="text" id="program_name" name="program_name" value="<?php echo htmlspecialchars($data['program_name']); ?>" required>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" value="<?php echo $data['date']; ?>" required>

            <label for="venue">Venue:</label>
            <input type="text" id="venue" name="venue" value="<?php echo htmlspecialchars($data['venue']); ?>" required>

            <label for="report_date">Report Submission Date:</label>
            <input type="date" id="report_date" name="report_date" value="<?php echo $data['report_date']; ?>" required>
        </form>

        <div class="form-buttons">
            <a href="e_merit_form.php?id=<?php echo $submission_id; ?>" class="button back-button">← Back</a>
            <button type="submit" form="certificateForm" class="button save-button" name="save">Save</button>
            <a href="book_hall_form.php?id=<?php echo $submission_id; ?>" class="button next-button">Next →</a>
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
    </script>
</body>
</html>
