<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid ID.");
}

$user_id = $_SESSION['user_id'];
$submission_id = (int)$_GET['id'];

// Ensure the submission belongs to the user
$sql = "DELETE FROM c_form_submissions WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $submission_id, $user_id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: my_submissions.php");
    exit();
} else {
    echo "Error deleting submission: " . mysqli_error($conn);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
