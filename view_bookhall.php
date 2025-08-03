<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.html");
    exit();
}

include 'db.php';

if (!isset($_GET['id']) || !isset($_GET['user']) || !is_numeric($_GET['id']) || !is_numeric($_GET['user'])) {
    echo "<script>alert('Invalid submission.'); window.location.href='review_submissions.php';</script>";
    exit();
}

$submission_id = (int)$_GET['id'];
$user_id = (int)$_GET['user'];

// Fetch Book Hall data
$sql = "SELECT * FROM book_hall_form WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $submission_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$formData = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

$nextLink = "view_honorarium.php?id=$submission_id&user=$user_id";
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Book Hall Submission</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="navbar">
    <img src="assets/uitm logo.jpeg" alt="Logo" class="logo">
    <div class="nav-title">HEP Program Registration System - Admin View</div>
    <div class="nav-user">Logged in as Admin: <strong><?= $_SESSION['user_login'] ?></strong></div>
</div>

<div class="form-section">
    <h2 style="text-align:center; color:#2a2675;">Book Hall Form (Read-Only)</h2>
    <form class="form-grid">
        <label>Organizer Name:</label>
        <input type="text" value="<?= htmlspecialchars($formData['name'] ?? '') ?>" disabled>

        <label>Program Name:</label>
        <input type="text" value="<?= htmlspecialchars($formData['program_name'] ?? '') ?>" disabled>

        <label>Booking Date:</label>
        <input type="date" value="<?= htmlspecialchars($formData['bookhall_date'] ?? '') ?>" disabled>

        <label>Requested Equipment:</label>
        <input type="text" value="<?= htmlspecialchars($formData['request_equip'] ?? '') ?>" disabled>
    </form>

    <div class="form-buttons" style="text-align:center; margin-top:20px;">
        <a href="view_certificate.php?id=<?= $submission_id ?>&user=<?= $user_id ?>" class="button">← Back</a>
        <a href="<?= $nextLink ?>" class="button">Next →</a>
    </div>
</div>
</body>
</html>
