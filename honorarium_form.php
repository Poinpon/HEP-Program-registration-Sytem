<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$submission_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

$data = [
    'contact_num' => '',
    'faculty_unit' => '',
    'application_date' => '',
    'purpose_use' => '',
    'use_date' => '',
    'use_time' => '',
    'expected_num' => '',
    'request_facilities' => '',
    'other_facility' => ''
];

$checked_items = [];

if ($submission_id) {
    include 'db.php';

    $sql = "SELECT * FROM honorarium_submissions WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $submission_id, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        $checked_items = array_map('trim', explode(',', $data['request_facilities']));

        // Handle "Others: something" so checkbox & text box show correctly
        foreach ($checked_items as $i => $item) {
            if (stripos($item, "Others:") === 0) {
                $data['other_facility'] = trim(substr($item, strlen("Others:")));
                $checked_items[$i] = "Others"; // normalize for checkbox comparison
            }
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Honorarium Form</title>
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
        <div class="nav-user">Logged in as: <strong><?php echo htmlspecialchars($_SESSION['user_login']); ?></strong></div>
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
        <a href="book_hall_form.php?id=<?php echo $submission_id; ?>" class="tab">Book Hall</a>
        <a href="honorarium_form.php?id=<?php echo $submission_id; ?>" class="tab active">Honorarium</a>
        <a href="upload_paperwork.php?id=<?php echo $submission_id; ?>" class="tab">Paperwork</a>
    </div>

    <div class="form-section">
        <form action="submit_honorarium_form.php?id=<?php echo $submission_id; ?>" method="POST" class="form-grid" id="honorariumForm">

            <label for="contact_num">Contact Number:</label>
            <input type="text" id="contact_num" name="contact_num" value="<?php echo htmlspecialchars($data['contact_num']); ?>" required>

            <label for="faculty_unit">Faculty / Unit / Club:</label>
            <input type="text" id="faculty_unit" name="faculty_unit" value="<?php echo htmlspecialchars($data['faculty_unit']); ?>" required>

            <label for="application_date">Date of Application:</label>
            <input type="date" id="application_date" name="application_date" value="<?php echo htmlspecialchars($data['application_date']); ?>" required>

            <label for="purpose_use">Purpose of Use:</label>
            <input type="text" id="purpose_use" name="purpose_use" value="<?php echo htmlspecialchars($data['purpose_use']); ?>" required>

            <label for="use_date">Date of Use:</label>
            <input type="date" id="use_date" name="use_date" value="<?php echo htmlspecialchars($data['use_date']); ?>" required>

            <label for="use_time">Time of Use:</label>
            <input type="time" id="use_time" name="use_time" value="<?php echo htmlspecialchars($data['use_time']); ?>" required>

            <label for="expected_num">Expected Number of Participants:</label>
            <input type="text" id="expected_num" name="expected_num" value="<?php echo htmlspecialchars($data['expected_num']); ?>" required>

            <label>Requested Facilities:</label>
            <div class="checkbox-group">
                <?php
                $facilities = [
                    "P.A System", "Microphone", "Projector", "Sofa / Settee",
                    "Banquet Chair", "Long Table", "Coffee Table", "Rostrum", "Others"
                ];
                foreach ($facilities as $f) {
                    $isChecked = in_array($f, $checked_items) ? "checked" : "";
                    $safeF = htmlspecialchars($f, ENT_QUOTES, 'UTF-8');
                echo "<label><input type='checkbox' name='request_facilities[]' value=\"{$safeF}\" {$isChecked}> {$safeF}</label>";
                }
                ?>
                <input type="text" name="other_facility" placeholder="Please specify" value="<?php echo htmlspecialchars($data['other_facility'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
        </form>

        <div class="form-buttons">
            <a href="book_hall_form.php?id=<?php echo $submission_id; ?>" class="button back-button">← Back</a>
            <button type="submit" form="honorariumForm" class="button save-button" name="save">Save</button>
            <a href="upload_paperwork.php?id=<?php echo $submission_id; ?>" class="button next-button">Next →</a>
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
