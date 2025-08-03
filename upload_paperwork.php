<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$submission_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

$data = [
    'title' => '',
    'file_path' => ''
];

if ($submission_id) {
    include 'db.php';

    $sql = "SELECT title, file_path FROM uploaded_paperwork WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $submission_id, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $data['title'] = $row['title'];
        $data['file_path'] = $row['file_path'];
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload Paperwork</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .danger-button {
            background-color: #e74c3c;
            color: white;
            padding: 6px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 0.9em;
            margin-left: 10px;
        }
        .danger-button:hover {
            background-color: #c0392b;
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
        <a href="book_hall_form.php?id=<?php echo $submission_id; ?>" class="tab">Book Hall</a>
        <a href="honorarium_form.php?id=<?php echo $submission_id; ?>" class="tab">Honorarium</a>
        <a href="upload_paperwork.php?id=<?php echo $submission_id; ?>" class="tab active">Paperwork</a>
    </div>

    <div class="form-section">
        <form action="submit_paperwork.php?id=<?php echo $submission_id; ?>" method="POST" enctype="multipart/form-data" class="form-grid" id="paperworkForm">
            <label for="paperwork_title">Title/Description:</label>
            <input type="text" name="paperwork_title" id="paperwork_title" value="<?php echo htmlspecialchars($data['title']); ?>" required>

            <label for="paperwork_file">Select File (PDF, DOCX, JPG, PNG):</label>
            <input type="file" name="paperwork_file" id="paperwork_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">

            <?php if (!empty($data['file_path']) && file_exists($data['file_path'])): ?>
                <p><strong>Last uploaded file:</strong>
                    <a href="<?php echo $data['file_path']; ?>" target="_blank">
                        <?php echo basename($data['file_path']); ?>
                    </a>
                    <form action="delete_paperwork.php" method="POST" style="display:inline;">
                        <input type="hidden" name="submission_id" value="<?php echo $submission_id; ?>">
                        <button type="submit" class="danger-button" onclick="return confirm('Are you sure you want to delete this file?')">Delete</button>
                    </form>
                </p>
            <?php endif; ?>
        </form>

        <div class="form-buttons">
            <a href="honorarium_form.php?id=<?php echo $submission_id; ?>" class="button back-button">← Back</a>
            <button type="submit" form="paperworkForm" class="button save-button" name="upload">Upload</button>
            <a href="my_submissions.php" class="button next-button">Next →</a>
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
