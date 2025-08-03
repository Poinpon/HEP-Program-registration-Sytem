<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$submission_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$title = mysqli_real_escape_string($conn, $_POST['paperwork_title']);
$upload_dir = 'uploads/';

if (!isset($_FILES['paperwork_file']) || $_FILES['paperwork_file']['error'] !== 0) {
    echo "<script>alert('No file uploaded. Please choose a file before submitting.'); window.history.back();</script>";
    exit();
}

$filename = basename($_FILES['paperwork_file']['name']);
$target_file = $upload_dir . time() . "_" . $filename;

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if (move_uploaded_file($_FILES['paperwork_file']['tmp_name'], $target_file)) {
    // Delete any existing record with the same ID and user
    $delete_sql = "DELETE FROM uploaded_paperwork WHERE id = ? AND user_id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_sql);
    mysqli_stmt_bind_param($delete_stmt, "ii", $submission_id, $user_id);
    mysqli_stmt_execute($delete_stmt);
    mysqli_stmt_close($delete_stmt);

    // Insert new record
    $sql = "INSERT INTO uploaded_paperwork (id, user_id, title, file_path) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iiss", $submission_id, $user_id, $title, $target_file);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('File uploaded successfully!'); window.location.href='upload_paperwork.php?id={$submission_id}';</script>";
    } else {
        echo "Database error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
} else {
    echo "<script>alert('Failed to move uploaded file.'); window.history.back();</script>";
}

mysqli_close($conn);
?>
