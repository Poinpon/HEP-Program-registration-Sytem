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

// Fetch paperwork form data
$sql = "SELECT * FROM uploaded_paperwork WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $submission_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$formData = mysqli_fetch_assoc($result);

$uploadDate = '';
if (!empty($formData['upload_date'])) {
    // Convert '2025-07-02 00:17:53' → '2025-07-02'
    $uploadDate = substr($formData['upload_date'], 0, 10);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Paperwork Submission</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="navbar">
    <img src="assets/uitm logo.jpeg" alt="Logo" class="logo">
    <div class="nav-title">HEP Program Registration System - Admin View</div>
    <div class="nav-user">Logged in as Admin: <strong><?= $_SESSION['user_login'] ?></strong></div>
</div>

<div class="form-section">
    <h2 style="text-align:center; color:#2a2675;">Paperwork Form (Read-Only)</h2>
    <form class="form-grid">
        <label>Program Title:</label>
        <input type="text" value="<?= htmlspecialchars($formData['title'] ?? '') ?>" disabled>

        <label>Upload Date:</label>
        <input type="date" value="<?= htmlspecialchars($uploadDate) ?>" disabled>

        <label>Paperwork File:</label>
        <?php if (!empty($formData['file_path'])): ?>
            <a href="<?= htmlspecialchars($formData['file_path']) ?>" target="_blank" class="button" style="width:auto; margin-top:5px;">View File</a>
        <?php else: ?>
            <input type="text" value="No file uploaded" disabled>
        <?php endif; ?>
    </form>

    <div class="form-buttons" style="text-align:center; margin-top:20px;">
        <a href="view_honorarium.php?id=<?= $submission_id ?>&user=<?= $user_id ?>" class="button">← Back</a>
        <a href="review_submissions.php" class="button" style="margin-left: 10px;">Return to Review Page</a>
    </div>
</div>
</body>
</html>
