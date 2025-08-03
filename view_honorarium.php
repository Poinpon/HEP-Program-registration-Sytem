<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.html");
    exit();
}

include 'db.php';

$submission_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$user_id = isset($_GET['user']) ? (int)$_GET['user'] : null;

if (!$submission_id || !$user_id) {
    echo "<script>alert('Invalid submission.'); window.location.href='review_submissions.php';</script>";
    exit();
}

// Fetch honorarium data
$sql = "SELECT * FROM honorarium_submissions WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $submission_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$formData = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Honorarium Submission</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="navbar">
    <img src="assets/uitm logo.jpeg" alt="Logo" class="logo">
    <div class="nav-title">HEP Program Registration System - Admin View</div>
    <div class="nav-user">Logged in as Admin: <strong><?= htmlspecialchars($_SESSION['user_login']) ?></strong></div>
</div>

<div class="form-section">
    <h2 style="text-align:center; color:#2a2675;">Honorarium Form (Read-Only)</h2>
    <form class="form-grid">
        <label>Contact Number:</label>
        <input type="text" value="<?= htmlspecialchars($formData['contact_num'] ?? '') ?>" disabled>

        <label>Faculty / Unit / Club:</label>
        <input type="text" value="<?= htmlspecialchars($formData['faculty_unit'] ?? '') ?>" disabled>

        <label>Date of Application:</label>
        <input type="date" value="<?= htmlspecialchars($formData['application_date'] ?? '') ?>" disabled>

        <label>Purpose of Use:</label>
        <input type="text" value="<?= htmlspecialchars($formData['purpose_use'] ?? '') ?>" disabled>

        <label>Date of Use:</label>
        <input type="date" value="<?= htmlspecialchars($formData['use_date'] ?? '') ?>" disabled>

        <label>Time of Use:</label>
        <input type="time" value="<?= htmlspecialchars($formData['use_time'] ?? '') ?>" disabled>

        <label>Expected Number of Participants:</label>
        <input type="text" value="<?= htmlspecialchars($formData['expected_num'] ?? '') ?>" disabled>

        <label>Requested Facilities:</label>
        <textarea disabled><?= htmlspecialchars($formData['request_facilities'] ?? '') ?></textarea>
    </form>

    <div class="form-buttons" style="text-align:center; margin-top:20px;">
        <a href="view_bookhall.php?id=<?= $submission_id ?>&user=<?= $user_id ?>" class="button">← Back</a>
        <a href="view_paperwork.php?id=<?= $submission_id ?>&user=<?= $user_id ?>" class="button">Next →</a>
    </div>
</div>
</body>
</html>
