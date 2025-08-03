<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.html");
    exit();
}

include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('Invalid submission ID.'); window.location.href='review_submissions.php';</script>";
    exit();
}

$submission_id = (int)$_GET['id'];

// Fetch C Form data
$sql = "SELECT * FROM c_form_submissions WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $submission_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$submissionData = mysqli_fetch_assoc($result)) {
    echo "<script>alert('Submission not found.'); window.location.href='review_submissions.php';</script>";
    exit();
}

$user_id = $submissionData['user_id']; // Save for navigation use

// Extract soft skills array
$softSkillsArray = [];
if (!empty($submissionData['soft_skills'])) {
    $softSkillsArray = array_map('trim', explode(',', $submissionData['soft_skills']));
}

// Ordered list of forms
$formSequence = [
    'c_form'      => 'c_form_submissions',
    'e_merit'     => 'emerit_submissions',
    'certificate' => 'certificate_submissions',
    'bookhall'    => 'book_hall_submissions',
    'honorarium'  => 'honorarium_form',
    'paperwork'   => 'paperwork_submissions'
];

$formKeys = array_keys($formSequence);
$currentKey = array_search('c_form', $formKeys);

// Next & Back Links
$nextLink = '';
$backLink = '';

for ($i = $currentKey + 1; $i < count($formKeys); $i++) {
    $key = $formKeys[$i];
    $table = $formSequence[$key];
    $checkSql = "SELECT * FROM $table WHERE id = ? AND user_id = ?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "ii", $submission_id, $user_id);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);
    $formData = mysqli_fetch_assoc($checkResult);
    if ($formData && array_filter($formData, fn($v) => $v !== null && $v !== '')) {
        $nextLink = "view_" . $key . ".php?id=$submission_id&user=$user_id";
        break;
    }
}

for ($j = $currentKey - 1; $j >= 0; $j--) {
    $key = $formKeys[$j];
    $table = $formSequence[$key];
    $checkSql = "SELECT * FROM $table WHERE id = ? AND user_id = ?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "ii", $submission_id, $user_id);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);
    $formData = mysqli_fetch_assoc($checkResult);
    if ($formData && array_filter($formData, fn($v) => $v !== null && $v !== '')) {
        $backLink = "view_" . $key . ".php?id=$submission_id&user=$user_id";
        break;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View C Form Submission</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="navbar">
    <img src="assets/uitm logo.jpeg" alt="Logo" class="logo">
    <div class="nav-title">HEP Program Registration System - Admin View</div>
    <div class="nav-user">Logged in as Admin: <strong><?= $_SESSION['user_login'] ?></strong></div>
</div>

<div class="form-section">
    <h2 style="text-align:center; color:#2a2675;">C Form Submission (Read-Only)</h2>
    <form class="form-grid">
        <label>Association Code:</label>
        <input type="text" value="<?= htmlspecialchars($submissionData['association_code']) ?>" disabled>

        <label>Association Name:</label>
        <input type="text" value="<?= htmlspecialchars($submissionData['association_name']) ?>" disabled>

        <label>Activity Name:</label>
        <input type="text" value="<?= htmlspecialchars($submissionData['activity_name']) ?>" disabled>

        <label>Activity Level:</label>
        <input type="text" value="<?= htmlspecialchars($submissionData['activity_level']) ?>" disabled>

        <label>Activity Category:</label>
        <input type="text" value="<?= htmlspecialchars($submissionData['activity_category']) ?>" disabled>

        <label>Joint Organizer:</label>
        <input type="text" value="<?= htmlspecialchars($submissionData['joint_organizer']) ?>" disabled>

        <label>Activity Venue:</label>
        <input type="text" value="<?= htmlspecialchars($submissionData['activity_venue']) ?>" disabled>

        <label>Activity Date:</label>
        <input type="date" value="<?= htmlspecialchars($submissionData['activity_date']) ?>" disabled>

        <label>Participants:</label>
        <div class="participants-row">
            <div class="participant-field">
                <label>Male</label>
                <input type="number" value="<?= $submissionData['participants_male'] ?>" disabled>
            </div>
            <div class="participant-field">
                <label>Female</label>
                <input type="number" value="<?= $submissionData['participants_female'] ?>" disabled>
            </div>
            <div class="participant-field">
                <label>Total</label>
                <input type="number" value="<?= $submissionData['participants_total'] ?>" disabled>
            </div>
        </div>

        <label>Estimated Cost (RM):</label>
        <input type="number" value="<?= $submissionData['estimated_cost'] ?>" disabled>

        <label>Estimated Sponsorship (RM):</label>
        <input type="number" value="<?= $submissionData['estimated_sponsorship'] ?>" disabled>

        <label>Estimated Revenue (RM):</label>
        <input type="number" value="<?= $submissionData['estimated_revenue'] ?>" disabled>

        <label>Impact on Students:</label>
        <input type="text" value="<?= htmlspecialchars($submissionData['impact_students']) ?>" disabled>

        <label>Impact on University:</label>
        <input type="text" value="<?= htmlspecialchars($submissionData['impact_university']) ?>" disabled>

        <label>Impact on Community:</label>
        <input type="text" value="<?= htmlspecialchars($submissionData['impact_community']) ?>" disabled>

        <label>Soft Skills:</label>
        <div class="radio-group">
            <?php
            $skills = ["Communication", "Critical Thinking", "Teamwork", "Leadership", "Ethics"];
            foreach ($skills as $skill): ?>
                <label>
                    <input type="checkbox" disabled <?= in_array($skill, $softSkillsArray) ? 'checked' : '' ?>>
                    <?= $skill ?>
                </label>
            <?php endforeach; ?>
        </div>
    </form>

    <div class="form-buttons" style="text-align:center; margin-top:20px;">
        <?php if (!empty($backLink)): ?>
            <a href="<?= $backLink ?>" class="button">← Back</a>
        <?php else: ?>
            <a href="review_submissions.php" class="button">← Back to Review</a>
        <?php endif; ?>
        <a href="<?= $nextLink ?>" class="button">Next →</a>
    </div>
</div>
</body>
</html>
