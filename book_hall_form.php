<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// ✅ Get submission ID
$submission_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// ✅ Default empty data
$data = [
    'name' => '',
    'program_name' => '',
    'bookhall_date' => '',
    'request_equip' => ''
];

// ✅ Load existing submission if ID is provided
if ($submission_id) {
    include 'db.php';

    $sql = "SELECT * FROM book_hall_form WHERE id = ? AND user_id = ?";
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

// ✅ Convert saved equipment list into array for checkbox checking
$checked_items = array_map('trim', explode(',', $data['request_equip']));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Hall Form</title>
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
        <a href="certificate_form.php?id=<?php echo $submission_id; ?>" class="tab">Certificate</a>
        <a href="book_hall_form.php?id=<?php echo $submission_id; ?>" class="tab active">Book Hall</a>
        <a href="honorarium_form.php?id=<?php echo $submission_id; ?>" class="tab">Honorarium</a>
        <a href="upload_paperwork.php?id=<?php echo $submission_id; ?>" class="tab">Paperwork</a>
    </div>

    <div class="form-section">
        <!-- ✅ Corrected form action to match actual file name -->
        <form action="submit_book_hall_form.php?id=<?php echo $submission_id; ?>" method="POST" class="form-grid" id="bookHallForm">
            <label for="bookhall_name">Name:</label>
            <input type="text" id="bookhall_name" name="bookhall_name" value="<?php echo htmlspecialchars($data['name']); ?>" required>

            <label for="program_name">Program Name:</label>
            <input type="text" id="program_name" name="program_name" value="<?php echo htmlspecialchars($data['program_name']); ?>" required>

            <label for="bookhall_date">Date:</label>
            <input type="date" id="bookhall_date" name="bookhall_date" value="<?php echo $data['bookhall_date']; ?>" required>

            <label>Requested Equipment:</label>
            <div class="checkbox-group">
                <?php
                $equipments = ["LCD", "Laptop", "Microphone", "Cordless Microphone", "Microphone Stand"];
                foreach ($equipments as $equip) {
                    $isChecked = in_array($equip, $checked_items) ? "checked" : "";
                    echo "<label><input type='checkbox' name='request_equip[]' value='{$equip}' {$isChecked}> {$equip}</label>";
                }
                ?>
            </div>
        </form>

        <div class="form-buttons">
            <a href="certificate_form.php?id=<?php echo $submission_id; ?>" class="button back-button">← Back</a>
            <button type="submit" form="bookHallForm" class="button save-button" name="save">Save</button>
            <a href="honorarium_form.php?id=<?php echo $submission_id; ?>" class="button next-button">Next →</a>
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
